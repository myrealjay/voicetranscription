<?php


/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('/', 'VoicetranscriptionController@index');
Route::post('upload', 'VoicetranscriptionController@upload');