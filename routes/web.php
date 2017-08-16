<?php

$this->get('/api/login', 'Auth\AuthController@showLoginForm');
$this->post('login', 'Auth\AuthController@login');
$this->get('logout', 'Auth\AuthController@logout');

// Registration Routes...
$this->get('register', 'Auth\AuthController@showRegistrationForm');
$this->post('register', 'Auth\AuthController@register');

// Password Reset Routes...
$this->get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
$this->post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
$this->post('password/reset', 'Auth\PasswordController@reset');
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

Route::get('/verifyemail/{token}', 'Auth\RegisterController@verify');

