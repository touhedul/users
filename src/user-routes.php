<?php

use Illuminate\Support\Arr;

Route::group(['prefix' => '/', 'middleware' => 'web'], function(){
    $middleware = config('properos_users.router.default.middleware');
    $namespace = 'Properos\Users\Controllers';

    Route::get('/ru', $namespace.'\UserController@returnUser');

    Route::group(['prefix' => 'admin', 'middleware' => Arr::get($middleware, 'admin', Arr::get($middleware, 'private', []))], function () use($namespace) {
        Route::get('/users', $namespace.'\UserController@index')->middleware(['role:admin']);
        Route::group(['prefix' => '/users'], function () use($namespace) {
            Route::get('/create', $namespace.'\UserController@createUser')->middleware(['role:admin']);
            Route::get('/edit/{id}', $namespace.'\UserController@editUser')->middleware(['role:admin']);
        });
        Route::get('/my-profile', $namespace.'\UserController@profile');
    });

    Route::group(['prefix' => 'api/admin', 'middleware' => Arr::get($middleware, 'api/admin', Arr::get($middleware, 'private', []))], function () use($namespace) {
        Route::get('/su/{user_id}', $namespace.'\UserController@setUser');
        Route::group(['prefix' => '/users'], function () use($namespace) {
            Route::post('/search', $namespace.'\UserController@usersSearch');
            Route::post('/store-user', $namespace.'\ApiUserController@storeUser')->middleware(['role:admin']);
            Route::post('/update-user', $namespace.'\ApiUserController@updateUser')->middleware(['role:admin']);
            Route::post('/remove-role', $namespace.'\ApiUserController@removeRole')->middleware(['role:admin']);
            Route::put('/change-user-password/{id}', $namespace.'\ApiUserController@changeUserPassword')->middleware(['role:admin|sales']);
            Route::post('/update-profile', $namespace.'\ApiUserController@updateProfile');
            Route::post('/ledgers/search', $namespace.'\ApiUserController@searchLedgers');
        });
    });

    Route::get('/login', $namespace.'\LoginController@showLoginForm')->name('login');
    Route::post('/login', $namespace.'\LoginController@login');
    Route::match(['get','post'],'/logout', $namespace.'\LoginController@logout')->name('logout');
    Route::post('/password/email', $namespace.'\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('/password/reset', $namespace.'\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('/password/reset', $namespace.'\ResetPasswordController@reset');
    Route::get('/password/reset/{token}', $namespace.'\ResetPasswordController@showResetForm')->name('password.reset');
    Route::get('/register', $namespace.'\RegisterController@showRegistrationForm')->name('register');
    Route::post('/register', $namespace.'\RegisterController@register');

    Route::get('auth/facebook', $namespace.'\SocialRegisterController@redirectToFacebookProvider');
    Route::get('auth/facebook/callback', $namespace.'\SocialRegisterController@handleFacebookProviderCallback');
    Route::get('auth/google', $namespace.'\SocialRegisterController@redirectToGoogleProvider');
    Route::get('auth/google/callback', $namespace.'\SocialRegisterController@handleGoogleProviderCallback');

});

Route::group(['prefix' => '/api/v1', 'middleware' => 'api'], function () {
    $middleware = config('properos_users.router.default.middleware');
    $namespace = 'Properos\Users\Controllers';

    Route::post('login', $namespace.'\LoginController@apiLogin');
    Route::post('register', $namespace.'\RegisterController@apiRegister');
    
    Route::post('password/email', $namespace.'\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', $namespace.'\ResetPasswordController@reset');

    Route::group(['prefix' => '', 'middleware' => 'auth:api'], function () use($namespace) {
        Route::post('logout', $namespace.'\LoginController@apiLogout');
    
        Route::post('/update-profile', $namespace.'\ApiUserController@updateProfile');
        Route::put('/change-user-password/{id}', $namespace.'\ApiUserController@changeUserPassword');
    });
});







