<?php

namespace App\Http\Controllers\Upload;

use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request;
use DB;

class UploadController extends Controller {

    function index() {
        return view('upload', ['status' => 0, 'newShowId' => -1]);
    }

    function uploadFile() {
        if (Request::hasFile('video')) {
            $file = Request::file('video');
            $filename = $file->getClientOriginalName();
            $path = Storage::putFileAs('videos', $file, $filename, 'private');
        }
            //$path = public_path() . '/uploads';
            //Display File Name
//            echo 'File Name: ' . $file->getClientOriginalName();
//            echo '<br>';
            //Display File Extension
//            echo 'File Extension: ' . $file->getClientOriginalExtension();
//            echo '<br>';
            //Display File Real Path
//            echo 'File Real Path: ' . $file->getRealPath();
//            echo '<br>';
            //Display File Size
//            echo 'File Size: ' . $file->getSize();
//            echo '<br>';
            //Display File Mime Type
//            echo 'File Mime Type: ' . $file->getMimeType();
//            echo '<br>';
            //Move Uploaded File
            //$destinationPath = 'uploads';
            //$file->move($destinationPath, $file->getClientOriginalName());
            //$file->move($path);
            // use laravel Storage facade method to store file,
            // using the private option so the URL cannot be discovered

            // get video metadata
            $title = Request::get('title');
            $year = Request::get('year');
            $summary = Request::get('summary');
            $sub = Request::get('subscription');
            $mediatype = Request::get('mediatype');
            $runtime = Request::get('hours') . ':' . Request::get('minutes') . ':' . Request::get('seconds');
            $showId = Request::get('showId');
            $seasonNumber = Request::get('seasonNumber');
            $episodeNumber = Request::get('episodeNumber');
            $newShowId = -1;

            // enter video info in DB
            if ($mediatype != "episode") {
                $id = DB::table('Video')->insertGetId(
                        ['Title' => $title, 'Year' => $year, 'Summary' => $summary, 'Subscription' => $sub, 'IsMovie' => ($mediatype == "movie") ? 1 : 0]);
                if ($mediatype == "show") {
                    //echo DB::table('video')->select('Video_ID')->where('Title', $title)->get();
                    $newShowId = $id; // DB::table('video')->select('Video_ID')->where('Title', $title)->get()->toArray()->first();
                }
            }

            // enter movie info in DB
            if ($mediatype == "movie") {
                DB::table('Movie')->insertGetId(
                        ['File_Path' => $path, 'Length' => $runtime]);
            }
            // enter episode info in DB
            else if ($mediatype == "episode") {
                DB::table('Episode')->insertGetId(
                        ['Show_ID' => $showId, 'Season_Number' => $seasonNumber, 'Episode_Number' => $episodeNumber, 
                            'Episode_Title' => $title, 'File_Path' => $path, 'Length' => $runtime, 'Episode_Summary' => $summary]);
            }


            return view('upload', ['status' => 1, 'newShowId' => $newShowId]);
//        } else {
//            return view('upload', ['status' => 0, 'newShowId' => -1]);
//        }
    }

}

?>