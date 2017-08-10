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
})->middleware('jwt.auth');


Route::group(['prefix' => 'jackpot','middleware' => ['cors','token.auth','throttle:600,10']],function () {
    Route::get('/','ApiController@jackpot');
    Route::get('/results/{provider}','ApiController@results');
    Route::get('/results/all/last','ApiController@lastResult');
    Route::get('/info','ApiController@info');
});
