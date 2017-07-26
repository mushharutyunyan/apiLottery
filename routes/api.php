<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


//Route::group(['prefix' => 'jackpot','middleware' => 'auth:api'],function () {
//    Route::get('/','ApiController@jackpot');
//    Route::get('/results/{provider}','ApiController@results');
//});
