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

Route::group(['middleware' => 'cors', 'prefix' => 'v1'], function(){
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('login/refresh', 'AuthController@refreshToken');

    Route::post('recover', 'AuthController@recover');
    Route::get('city', 'CityController@search');
    Route::post('checkEmail', 'AuthController@checkEmail');
    Route::post('sendMail', 'AuthController@sendVerificationMail');
});
Route::group(['prefix' => 'v1', 'middleware' => ['cors', 'jwt.auth']], function() {
    //auth
    Route::post('logout', 'AuthController@logout');

    //categories
    Route::get('categories', 'CategoryController@index');

    //user
    Route::get('login/user', 'AuthController@getAuthenticatedUser');
    Route::post('user/update', 'UserController@update');
    Route::post('user/changepassword', 'UserController@updatePassword');

    //service
    Route::post('service/update/{serviceId}', 'ServiceController@update');
});