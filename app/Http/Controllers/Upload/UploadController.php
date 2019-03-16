<?php

namespace App\Http\Controllers\Upload;

use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;
use DB;

class UploadController extends Controller {

    function index() {
        return view('upload', ['status' => 0]);
    }

    function uploadFile() {
        if (Request::hasFile('video')) {
            $file = Request::file('video');
            $filename = $file->getClientOriginalName();
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
            $path = Storage::putFileAs('videos', $file, $filename, 'private');

            // get video metadata
            $title = Request::get('title');
            $year = Request::get('year');
            $summary = Request::get('summary');
            $sub = Request::get('subscription');
            $ismovie = Request::get('videotype');
            $runtime = Request::get('hours') . ':' . Request::get('minutes') . ':' . Request::get('seconds');

            // enter video info in DB
            DB::table('video')->insertGetId(
                    ['Title' => $title, 'Year' => $year, 'Summary' => $summary, 'Subscription' => $sub, 'IsMovie' => $ismovie],
                    'Video_ID');
            
            // enter movie info in DB
            if ($ismovie == 1)
            {
                DB::table('movie')->insertGetId(
                        ['File_Path' => $path, 'Length' => $runtime]);
            }
            // enter episode info in DB
            else
            {
                
            }
            
            return view('upload', ['status' => 1]);
        } else {
            return view('upload', ['status' => 0]);
        }
    }

}

?>