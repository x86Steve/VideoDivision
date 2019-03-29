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


/*
 * Route::get('/profile', function () {
    return view('profile.profile');
});
 */

Route::get('/profile', "UserProfileController@index");

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

Route::post('/upload', 'Upload\UploadController@submit');

Route::get('/live_search/grid', 'Search\LiveSearch@getGridView');
Route::get('/live_search/table', 'Search\LiveSearch@getTableView');

Route::get('/live_search/action', 'Search\LiveSearch@grid')->name('live_search.grid');
Route::get('/live_search/action2', 'Search\LiveSearch@table')->name('live_search.table');


Route::get('/video_details', 'ViewVideo@getView')->name('video_details');

Auth::routes();


Route::post('/video_details', 'ViewVideo@subscribe');

Route::get('/my_videos', 'ViewVideo@getMyVideosView')->name('my_videos');

Auth::routes();


/*Sydney's Adds*/
Route::get('/watch/{video_id}', 'WatchVideo@getView')->name('watch');
Route::get('/watch/{video_id}/episode/{episode_number}', 'WatchVideo@getEpisodeView')->name('watch');

//Route::get('/loggedin', 'HomeController@index')->name('loggedin');
