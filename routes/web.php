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

/*Route::get('/', function () {
    return view('welcome');
});*/


Route::get('/', 'JournalController@index');
Route::get('/rooms', 'RoomController@index');
Route::get('/clients', 'ClientController@index');

Route::resource('ajaxclients','ClientController');
Route::resource('ajaxrooms','RoomController');
Route::resource('ajaxjournals','JournalController');
