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
Route::get('/profile/{username}', "UserProfileController@viewprofile");
Route::get('/profile', "UserProfileController@index");
Route::post('/profile','UserProfileController@update_avatar');
Route::post('/profile/edit', 'UserProfileController@update_profile');

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

Route::get('/live_user_search/grid', 'Search\LiveUserSearch@getGridView');
Route::get('/live_user_search/action', 'Search\LiveUserSearch@grid')->name('live_user_search.grid');


Route::get('/video_details', 'ViewVideo@getView')->name('video_details');

Route::post('/video_details', 'ViewVideo@postHandler')->name('postHandler');

Route::get('/my_videos', 'ViewVideo@getMyVideosView')->name('my_videos');

/*****************************************************************************/

Route::get('/inbox',  'InboxController@getView')->name('view_inbox');

Route::get('/chat',  'ChatController@getView')->name('chat_window');

Route::get('/chat/addremove/{id}', "ChatController@remove_add_Friend");

Route::post('/chat', 'ChatController@postHandler')->name('postMessageHandler');


/************************************************************************/
Route::get('posts', 'PostController@posts')->name('posts');

Route::post('posts', 'PostController@postPost')->name('posts.post');

Route::get('posts/{id}', 'PostController@show')->name('posts.show');




/*Sydney's Adds*/
Route::get('/watch/{video_id}', 'WatchVideo@getView')->name('watch');
Route::get('/watch/{video_id}/season/{season_number}/episode/{episode_number}', 'WatchVideo@getEpisodeView')->name('watch');
//Route::get('/watch/{video_id}/episode1', 'WatchVideo@getFirstView')->name('watch'); //deprecated route->Episodes list now exists 4/22
Route::get('/view/{video_id}', 'ListEpisodesController@getView')->name('Episodes');

//Route::get('/loggedin', 'HomeController@index')->name('loggedin');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
