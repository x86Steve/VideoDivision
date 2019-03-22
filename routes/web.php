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

Route::get('/login', function () {
    return view('login');
});

Route::get('/search', 'Search\SearchController@basicSearch');

Route::get('/live_search/grid', 'Search\LiveSearch@getGridView');
Route::get('/live_search/table', 'Search\LiveSearch@getTableView');

Route::get('/live_search/action', 'Search\LiveSearch@grid')->name('live_search.grid');
Route::get('/live_search/action2', 'Search\LiveSearch@table')->name('live_search.table');


Route::get('/video_details', 'ViewVideo@getView')->name('video_details');



