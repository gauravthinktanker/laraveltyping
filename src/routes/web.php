<?php
use Illuminate\Support\Facades\Route;

Route::get('timezones/{timezone}',
'laraveltlr\tlr\TlrController@index');

Route::get('typing',
'laraveltyping\typing\TypingController@index');
Route::post('test2',
'laraveltyping\typing\TypingController@test_ajax');

Route::post('/test_data',
'laraveltyping\typing\TypingController@test_data');
