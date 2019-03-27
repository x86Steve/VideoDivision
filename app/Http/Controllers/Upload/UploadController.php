<?php

namespace App\Http\Controllers\Upload;

use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request;
use DB;

class UploadController extends Controller
{

    function loadPage($status)
    {
        $actors = DB::table('Actor')->orderBy('First_Name', 'asc')->get();
        return view('upload', ['status' => $status, 'actors' => $actors]);
    }

    function index()
    {
        //loadData();
        return self::loadPage(0);
    }

    function uploadFile()
    {
        if (Request::hasFile('video'))
        {
            $file = Request::file('video');
            $filename = $file->getClientOriginalName();

            // use laravel Storage facade method to store file,
            // using the private option so the URL cannot be discovered
            $path = Storage::putFileAs('videos', $file, $filename, 'private');
        }

        // Display File Size
//        echo 'File Size: ' . $file->getSize();
//        echo '<br>';
        //Move Uploaded File
        //$destinationPath = 'uploads';
        //$file->move($destinationPath, $file->getClientOriginalName());
        //$file->move($path);
        
        // get video metadata
        $title = Request::get('title');
        $year = Request::get('year');
        $summary = Request::get('summary');
        $sub = Request::get('subscription');
        $mediatype = Request::get('mediatype');
        $runtime = Request::get('duration');
        $showId = Request::get('showId');
        $seasonNumber = Request::get('seasonNumber');
        $episodeNumber = Request::get('episodeNumber');
        //$newShowId = -1;
        $videoId = null;
        $episodeId = null;
        
        
        //echo "Hello world!";
        //echo Request::get('duration');
        //$addNew = 'actorSelect' . $count;
        //echo $addNew;
        //echo Request::get('addNew1');

        // enter video info in DB
        if ($mediatype != "episode")
        {
            $videoId = DB::table('Video')->insertGetId(
                    ['Title' => $title, 'Year' => $year, 'Summary' => $summary, 'Subscription' => $sub, 'IsMovie' => ($mediatype == "movie") ? 1 : 0]);
            if ($mediatype == "show")
            {
                //echo DB::table('video')->select('Video_ID')->where('Title', $title)->get();
                //$newShowId = $videoId; // DB::table('video')->select('Video_ID')->where('Title', $title)->get()->toArray()->first();
            }
        }

        // enter movie info in DB
        if ($mediatype == "movie")
        {
            DB::table('Movie')->insertGetId(
                    ['Movie_ID' => $videoId, 'File_Path' => $path, 'Length' => $runtime]);
        }
        // enter episode info in DB
        else if ($mediatype == "episode")
        {
            $episodeId = DB::table('Episode')->insertGetId(
                    ['Show_ID' => $showId, 'Season_Number' => $seasonNumber, 'Episode_Number' => $episodeNumber,
                        'Episode_Title' => $title, 'File_Path' => $path, 'Length' => $runtime, 'Episode_Summary' => $summary]);
        }

        // enter cast info in DB
        $count = 1;
        $actor = null;
        //while ($actor = Request::get('actorSelect' . $count) != null)
        //{
            $actor = Request::get('actorSelect' . $count);
            if (Request::get('addNew' . $count) == 'on')
            {
                // use the custom actor name
                $fn = Request::get('actorInputFirst' . $count);
                $ln = Request::get('actorInputLast' . $count);
            }
            else
            {
                // use the selected actor name

            }
            $actorId = DB::table('Actor')->insertGetId(
                ['First_Name' => $fn, 'Last_Name' => $ln]
            );
            DB::table('Cast')->insert(
                ['IsMovie' => ($mediatype == "movie"), 'Episode_ID' => $episodeId, 'Actor_ID' => $actorId, 'Movie_ID' => $videoId]
            );
            $count++;
        //}

        echo $count;

        return self::loadPage(1);
//        } else {
//            return view('upload', ['status' => 0, 'newShowId' => -1]);
//        }
    }

}

?>