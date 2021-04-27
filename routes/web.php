<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->get('/', function () use ($router) {
    return $router->app->version();
});


// Without Oauth
Route::group([
    'prefix' => 'auth'

], function ($router) {
    Route::post('login', 'AuthController@login');
});


// With Oauth
Route::group(['middleware' => 'auth:api','prefix' => 'auth'], function ($router) {
    Route::post('logout', 'AuthController@logout');
    Route::post('payload', 'AuthController@payload');
    Route::post('me', 'AuthController@me');
});

// With Oauth without prefix
Route::group([ 'middleware' => ['auth:api', 'xssClean'], ], function ($router) {
    Route::post('category-list',    'ProductCategoryController@list');
    Route::post('product-list',     'ProductController@list');
    Route::post('product-details',  'ProductController@details');

    Route::post('add-to-cart',      'UserCartController@addToCart');
    Route::get('get-cart',          'UserCartController@getCart');
});