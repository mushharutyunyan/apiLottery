<?php
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::get('/home', 'HomeController@index');

Route::get('/verifyemail/{token}', 'Auth\RegisterController@verify');

