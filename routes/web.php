<?php


Route::group(['prefix' => 'jackpot'],function () {
    Route::get('/','ApiController@jackpot');
    Route::get('/results','ApiController@results');
});

Route::get('/', function () {
    return view('welcome');
});
