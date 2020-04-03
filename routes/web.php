<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('home');
//});

Route::redirect('/', '/home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
Route::get('/test', 'HomeController@test')->name('test');

/***** Users ****/
//Route::get('/users', 'UserController@home')->name('user.index')->middleware('auth')->middleware('permissions:user,read');
Route::get('/users', 'UserController@home')->name('user.index')->middleware('auth');
//Route::get('/user/create', 'UserController@create')->name('user.create')->middleware('auth')->middleware('permissions:user,create');
Route::get('/user/create', 'UserController@create')->name('user.create')->middleware('auth');
//Route::post('/user', 'UserController@store')->name('user.store')->middleware('auth')->middleware('permissions:user,create');
Route::post('/user', 'UserController@store')->name('user.store')->middleware('auth');
//Route::get('/user/{user}/edit', 'UserController@edit')->name('user.edit')->middleware('auth')->middleware('permissions:user,edit');
Route::get('/user/{user}/edit', 'UserController@edit')->name('user.edit')->middleware('auth');
//Route::post('/user/{user}/edit', 'UserController@update')->name('user.update')->middleware('auth')->middleware('permissions:user,edit');
Route::post('/user/{user}/edit', 'UserController@update')->name('user.update')->middleware('auth');

/***** Engagements ****/
Route::get('/engagements', 'EngagementController@home')->name('engagement.index')->middleware('auth');
Route::get('/engagements/{engagement}', 'EngagementController@show')->name('engagement.show')->middleware('auth');
Route::get('/search', 'EngagementController@search')->name('engagement.search')->middleware('auth');

/***** Echelons ****/
Route::get('/echelons', 'EchelonController@home')->name('echelon.index')->middleware('auth');

/***** Providers ****/
Route::get('/providers', 'ProviderController@home')->name('provider.index')->middleware('auth');



