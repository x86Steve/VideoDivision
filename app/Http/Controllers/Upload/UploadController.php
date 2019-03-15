<?php

namespace App\Http\Controllers\Upload;

use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request;
use DB;

class UploadController extends Controller {

    function index() {
        return view('upload');
    }

    function uploadFile(Request $request) 
    {
        if (Request::hasFile('video')) 
        {
            $file = Request::file('video');
            $filename = $file->getClientOriginalName();
            //$path = public_path() . '/uploads';

            //Display File Name
            echo 'File Name: ' . $file->getClientOriginalName();
            echo '<br>';

            //Display File Extension
            echo 'File Extension: ' . $file->getClientOriginalExtension();
            echo '<br>';

            //Display File Real Path
            echo 'File Real Path: ' . $file->getRealPath();
            echo '<br>';

            //Display File Size
            echo 'File Size: ' . $file->getSize();
            echo '<br>';

            //Display File Mime Type
            echo 'File Mime Type: ' . $file->getMimeType();
            echo '<br>';

            //Move Uploaded File
            //$destinationPath = 'uploads';
            //$file->move($destinationPath, $file->getClientOriginalName());

            //$file->move($path);

            // use laravel Storage facade method to store file,
            // using the private option so the URL cannot be discovered
            $path = Storage::putFile('videos', $file, 'private');
            echo 'Server path: ' . $path;

            // enter URL in DB
            // $title
        }
        else
        {
            echo 'No file chosen!';
        }
    }

}

?>