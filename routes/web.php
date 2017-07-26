<?php


Route::group(['prefix' => 'jackpot'],function () {
    Route::get('/','ApiController@jackpot');
    Route::get('/results/{provider}','ApiController@results');
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
