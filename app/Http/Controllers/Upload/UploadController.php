<?php

namespace App\Http\Controllers\Upload;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request;
use Auth;
use DB;

class UploadController extends Controller
{

    function loadPage($status)
    {
        // get list of actors
        $actors = DB::table('Actor')
            ->orderBy('First_Name', 'asc')
            ->get();

        // get a list of shows
        $shows = DB::table('Video')
            ->select('Video_ID', 'Title')
            ->where('IsMovie', 0)
            ->orderBy('Title', 'asc')
            ->get();

        // get list of genres
        $genres = DB::table('Genre')
            ->select('Genre_ID', 'Name')
            ->orderBy('Name', 'asc')
            ->get();

        // get list of directors
        $directors = DB::table('Director')
            ->get()
            ->unique('Director_ID');

        // return the upload page and pass it the data
        return view(
            'upload',
            [
                'status' => $status, 'actors' => $actors, 'shows' => $shows,
                'genres' => $genres, 'directors' => $directors
            ]
        );
    }

    function index()
    {
        // if guest tries to access form, suggest they log in
        if (Auth::guest())
            return redirect()->route('login');
        // if non-admin tries to access form, redirect to home
        else if (!Auth::user()->isAdmin)
            return redirect()->route('home');

        return self::loadPage(0);
    }

    function submit()
    {
        // user may have been logged out; redirect to home
        if (Auth::guest())
            return redirect()->route('home');
        // if non-admin somehow sends a post request to this page,
        // redirect to home
        else if (!Auth::user()->isAdmin)
            return redirect()->route('home');

        // get form fields
        $title = Request::get('title');
        $year = Request::get('year');
        $summary = Request::get('summary');
        $sub = Request::get('subscription');
        $mediatype = Request::get('mediatype');
        $isMovie = ($mediatype == "movie");
        $runtime = Request::get('duration');
        $seasonNumber = Request::get('seasonNumber');
        $episodeNumber = Request::get('episodeNumber');
        $addNewShow = (Request::get('addNewShow') == "on");
        $newShowName = Request::get('showInput');
        $newShowSummary = Request::get('showSummary');
        $showId = Request::get('showSelect');
        $videoId = null;
        $episodeId = null;

        // enter video info in DB
        if ($isMovie || (!$isMovie && $addNewShow)) {
            $videoId = DB::table('Video')->insertGetId(
                ['Title' => $isMovie ? $title : $newShowName, 'Year' => $year, 'Summary' => $isMovie ? $summary : $newShowSummary, 'Subscription' => $sub, 'IsMovie' => $isMovie ? 1 : 0]
            );
            $showId = $videoId;
        } else {
            $videoId = $showId;
        }

        // upload video
        $videoFile = Request::file('video');
        $filename = $videoId . $videoFile->getClientOriginalName();
        // get the lowercase name of the show
        if (!$isMovie) {
            if ($addNewShow) {
                $showDir = strtolower($newShowName);
            } else {
                $existingShowName = DB::table('Video')->select('Title')->where('Video_ID', $showId)->first()->Title;
                $showDir = strtolower($existingShowName);
            }
        }
        $videoDir = ($isMovie) ? "movies" : "tv-shows/" . $showDir . "/Season" . $seasonNumber;

        // for episodes, ensure we are inserting a unique file name.
        // movies don't have this issue because they have a unique video ID
        // and we don't know the episode ID until insert into DB
        if (!$isMovie) {
            $prefixNum = 1;
            while (self::episodeFilePathExists("/assets/videos/" . $videoDir . '/' . $prefixNum . $filename)) {
                $prefixNum++;
            }
            $filename = $prefixNum . $filename;
        }

        // use laravel Storage facade method to store file
        Storage::disk('videos')->putFileAs($videoDir, $videoFile, $filename);
        //$path = base_path('assets/videos/' . $videoDir . '/' . $filename);
        $path = "/assets/videos/" . $videoDir . '/' . $filename;

        // enter movie info in DB
        if ($isMovie) {
            DB::table('Movie')->insert(
                ['Movie_ID' => $videoId, 'File_Path' => $path, 'Length' => $runtime]
            );
        }

        // enter episode info in DB
        else if (!$isMovie) {
            $episodeId = DB::table('Episode')->insertGetId(
                [
                    'Show_ID' => $showId, 'Season_Number' => $seasonNumber, 'Episode_Number' => $episodeNumber,
                    'Episode_Title' => $title, 'File_Path' => $path, 'Length' => $runtime, 'Episode_Summary' => $summary
                ]
            );
        }

        // enter cast info in DB
        $count = 1;
        $actorId = null;
        while (null != ($actorId = Request::get('actorSelect' . $count)) || null != ($fn = Request::get('actorInputFirst' . $count))) {
            //$actor = Request::get('actorSelect' . $count);
            if (Request::get('addNew' . $count) == 'on') {
                $ln = Request::get('actorInputLast' . $count);
                $actorId = DB::table('Actor')->insertGetId(
                    ['First_Name' => $fn, 'Last_Name' => $ln]
                );
            }

            DB::table('Cast')->insert(
                [
                    'IsMovie' => $isMovie,
                    'Episode_ID' => $isMovie ? null : $episodeId,
                    'Actor_ID' => $actorId,
                    'Movie_ID' => $isMovie ? $videoId : null
                ]
            );
            $count++;
        }

        // enter director info in DB
        $count = 1;
        $directorId = null;
        while (($directorId = Request::get('directorSelect' . $count)) != null || ($fn = Request::get('directorInputFirst' . $count)) != null) {
            if (Request::get('addNewD' . $count) == 'on') {
                $ln = Request::get('directorInputLast' . $count);
                $directorId = DB::table('Director')->insertGetId(
                    ['First_Name' => $fn, 'Last_Name' => $ln]
                );
            }
            DB::table('Directors')->insert(
                [
                    'IsMovie' => $isMovie,
                    'Episode_ID' => $isMovie ? null : $episodeId,
                    'Director_ID' => $directorId,
                    'Movie_ID' => $isMovie ? $videoId : null
                ]
            );
            $count++;
        }

        if ($isMovie || $addNewShow) {
            // enter genre info in DB
            $count = 1;
            $genreId = null;
            while (($genreId = Request::get('genreSelect' . $count)) != null) {
                DB::table('Genres')->insert(
                    [
                        'Genre_ID' => $genreId,
                        'Video_ID' => $videoId,
                    ]
                );
                $count++;
            }
        }

        // upload thumbnail
        if (Request::hasFile('thumbnail')) {
            $thumbnailFile = Request::file('thumbnail');
            Storage::disk('thumbnails')->putFileAs('', $thumbnailFile, $videoId . ".jpg");
        }

        return self::loadPage(1);
    }

    // checks if a filepath exists in an entry in the Episode table
    function episodeFilePathExists(string $testPath)
    {
        $filePaths = DB::table('Episode')->select('File_Path')->get();
        return $filePaths->contains("File_Path", $testPath);
    }
}
