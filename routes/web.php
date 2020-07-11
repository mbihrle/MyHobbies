<?php

use Illuminate\Support\Facades\Route;

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
    return view('startseite');
});

Route::get('/info', function () {
    return view('info');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('hobby', 'HobbyController');
Route::resource('tag', 'TagController');
Route::resource('user', 'UserController');

Route::get('/hobby/tag/{tag_id}', 'hobbyTagController@getFilteredHobbies')->name('hobby_tag');

Route::get('/hobby/{hobby_id}/tag/{tag_id}/attach', 'hobbyTagController@attachTag')
->name('hobby_tag_attach')
->middleware('auth');
Route::get('/hobby/{hobby_id}/tag/{tag_id}/detach', 'hobbyTagController@detachTag')
->name('hobby_tag_detach')
->middleware('auth');

// Bilder vom Hobby löschen
Route::get('/delete-image/hobby/{hobby_id}', 'hobbyController@deleteImages');
// Bilder vom User löschen
Route::get('/delete-image/user/{user_id}', 'userController@deleteImages');
