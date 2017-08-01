<?php
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::get('/', 'HomeController@index');
Route::get('/plans', 'HomeController@plans');

Route::get('/verifyemail/{token}', 'Auth\RegisterController@verify');

