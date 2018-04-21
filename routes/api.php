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
    Route::get('city/update', 'CityController@updateCities');
    Route::post('checkEmail', 'AuthController@checkEmail');
    Route::post('sendMail', 'AuthController@sendVerificationMail');
});
Route::group(['prefix' => 'v1', 'middleware' => ['cors', 'jwt.auth']], function() {
    //auth
    Route::post('logout', 'AuthController@logout');

    //categories
    Route::get('categories', 'CategoryController@index');
    Route::get('subcategories',['middleware' => 'throttle:1,0', 'uses' => 'CategoryController@getSubCategories']);
    Route::get('subcategory/{id}', 'CategoryController@getSubCategory');
    //user
    Route::get('login/user', 'AuthController@getAuthenticatedUser');
    Route::post('user/update', 'UserController@update');
    Route::post('user/changepassword', 'UserController@updatePassword');

    //service
    Route::get('service/{subcatId}/nearby/{name}/count', 'ServiceController@getServicesCountNearby');
    Route::post('service/update/{serviceId}', 'ServiceController@update');
    Route::delete('service/{serviceId}/tag/{tagName}', 'ServiceController@removeTagFromService');

    //request
    Route::get('request/all', 'RequestController@index');
    Route::get('request/{id}', 'RequestController@get');
    Route::post('request/save', 'RequestController@save');
    Route::put('request/{id}/update', 'RequestController@update');
    Route::delete('request/{id}/delete', 'RequestController@delete');

    //offer
    Route::get('request/{reqid}/offer/{id}', 'OfferController@get');
    Route::get('offer/{id}/messages', 'OfferController@getMessages');
    Route::post('offer/{id}/message', 'OfferController@sendMessage');
});