<?php
use Illuminate\Support\Facades\Route;
Route::group(['middleware' => 'web'], function () {
Route::get('typing',
'laraveltyping\typing\TypingController@index');
Route::post('test2',
'laraveltyping\typing\TypingController@test_ajax');
Route::post('/test_data',
'laraveltyping\typing\TypingController@test_data');
Route::get('typing-speed',
'laraveltyping\typing\TypingController@typingSpeed')->name('typingSpeed');
});