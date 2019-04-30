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

Route::get('/', 'SiteController@home')->name('home');
Route::get('singers/{i}', 'SiteController@singers')->name('singers');
Route::get('songs/{i}', 'SiteController@songs')->name('songs');
Route::get('{singer_slug}-songs', 'SiteController@singerSongs')
    ->where(['singer_slug' => '[a-z0-9-]+'])->name('singer_songs');
Route::get('{singer_slug}/{song_slug}-lyrics', 'SiteController@songView')
    ->where(['singer_slug' => '[a-z0-9-]+', 'song_slug' => '[a-z0-9-]+'])->name('song_view');
Route::get('random-lyrics', 'SiteController@randomSongView')->name('random_lyrics');


Route::prefix('admin')->namespace('Admin')->group(function () {
    Auth::routes(['register' => false]);

    Route::get('/', 'SiteController@index')->name('admin.home');

    Route::resource('singer', 'SingerController');
    Route::resource('song', 'SongController');
});