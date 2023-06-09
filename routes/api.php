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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => '/v1'], function () {
    Route::post('/register', 'Auth\RegisterController@register')->name('register');
    Route::post('/verify/otp', 'Auth\OtpController@verify')->name('verify.otp');
    Route::group(['middleware' => 'auth-jwt'], function () {
        Route::get('/messages', 'Api\V1\ChatController@index')->name('messages');
    });
});
