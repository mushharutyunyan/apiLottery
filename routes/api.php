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



Route::group(['middleware' => 'checkAuth'],function () {
    Route::get('/', 'HomeController@index');
    Route::group(['middleware' => 'admin'],function () {
        Route::resource('/plan', 'PlansController');
        Route::resource('/user', 'UserController');
        Route::get('/plan/makeMain/{id}', 'PlansController@makeMain');
    });
    Route::get('/plans', 'HomeController@plans');
    Route::get('/payments', 'HomeController@payments');
    Route::group(['prefix' => 'payment'],function () {
        Route::post('/payWithPaypal', 'PaymentController@payWithPaypal');
        Route::get('/paypal/error', 'PaymentController@error');
        Route::get('/paypal/{response}', 'PaymentController@getPaypalPaymentStatus');
    });
});

Route::get('/verifyemail/{token}', 'Auth\RegisterController@verify');

// API ROUTES
Route::group(['prefix' => 'jackpot','middleware' => ['token.auth']],function () {
    Route::get('/','ApiController@jackpot');
    Route::get('/results/{provider}','ApiController@results');
    Route::get('/results/all/last','ApiController@lastResult');
    Route::get('/info','ApiController@info');
});
