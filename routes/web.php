<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('home');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/loggedin', function () {
    return view('auth.loggedin');
});

Route::get('/registered', function () {
    return view('auth.registered');
});

Route::get('/contact', "ContactController@view");

Route::post('/contact', "ContactController@mail");

Route::get('/search', 'Search\SearchController@basicSearch');
                  
Route::get('/upload', 'Upload\UploadController@index');

Route::post('/upload', 'Upload\UploadController@uploadFile');

Auth::routes();

/* Syndey's adds */

Route::get('/videoexample', function () {
    $video = "http://videodivision.net";
    $movie = DB::table('Movie')->first()->File_Path;
    $video .= $movie;
    $title = DB::table('Video')->first()->Title;

    return view('videoexample')->with(compact('video','title'));
});

Route::get('/video/{filename}', function ($filename) {

    $videosDir = base_path('resources/assets/videos');

    if (file_exists($filePath = $videosDir."/".$filename)) {
        $stream = new \App\Http\VideoStream($filePath);

        return response()->stream(function() use ($stream) {
            $stream->start();
        });
    }

    return response("File doesn't exists", 404);
});

//Route::get('/loggedin', 'HomeController@index')->name('loggedin');
