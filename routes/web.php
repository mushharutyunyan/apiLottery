<?php
Auth::routes();


Route::group(['middleware' => 'checkAuth'],function () {
    Route::get('/', 'HomeController@index');
    Route::group(['middleware' => 'admin'],function () {
        Route::resource('/plan', 'PlansController');
        Route::resource('/user', 'UsersController');
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
Route::group(['prefix' => 'jackpot','middleware' => ['token.auth']],function () {
    Route::get('/','ApiController@jackpot');
    Route::get('/results/{provider}','ApiController@results');
    Route::get('/results/all/last','ApiController@lastResult');
    Route::get('/info','ApiController@info');
});
Route::get('/verifyemail/{token}', 'Auth\RegisterController@verify');

