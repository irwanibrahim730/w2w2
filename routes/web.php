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

//user
Route::get('/user', 'UserController@index');
Route::get('/user/{id}', 'UserController@show');
Route::post('/user/add', 'UserController@store');
Route::post('/user/update', 'UserController@update');
Route::delete('/user/delete/{id}', 'UserController@destroy');

//package
Route::get('/packages', 'PackageController@index');
Route::get('/packages/{id}', 'PackageController@show');
Route::post('/packages/add', 'PackageController@store');
Route::post('/packages/update/{id}', 'PackageController@update');
Route::delete('/packages/delete/{id}', 'PackageController@destroy');

//news
Route::get('/news', 'NewsController@index');
Route::get('/news/{id}', 'NewsController@show');
Route::post('/news/add', 'NewsController@store');
Route::post('/news/update/{id}', 'NewsController@update');
Route::delete('/news/delete/{id}', 'NewsController@destroy');

//login
Route::post('/login', 'LoginController@signin');



//product
Route::get('/product', 'ProductController@index');
Route::get('/product/{id}', 'ProductController@show');
Route::post('/product/add','ProductController@store');
Route::post('/product/update/{id}','ProductController@update');
Route::delete('/product/delete/{id}','ProductController@destroy');

//admin
Route::post('/admin/approve/{id}','AdminController@approve');
Route::get('/admin/list','AdminController@listapproval');










 
