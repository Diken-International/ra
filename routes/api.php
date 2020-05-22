<?php

Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');
    Route::post('payload', 'AuthController@payload');
});

/**
 * POST    /modelo/            -> store
 * GET     /modelo/            -> index
 * PATCH   /modelo/{modelo_id} -> update
 * GET     /modelo/{modelo_id} -> show
 * DELETE  /modelo/{modelo_id} -> delete
 */

Route::group(['middleware' => ['jwt']], function () {

    Route::get('users','UserController@index')->name('users.index');
    Route::post('users','UserController@store')->name('users.store');
    Route::patch('users/{user_id}', 'UserController@update')->name('users.update');
    Route::delete('users/{user_id}','UserController@destroy')->name('users.destroy');
    Route::get('users/{user_id}','UserController@show')->name('users.show');
    Route::post('users/admin', 'UserController@createAdmin')->name('users.create.admin');
    Route::get('users/me','UserController@me')->name('users.me');

    Route::get('services','ServicesController@index')->name('services.index');
    Route::post('services','ServicesController@store')->name('servies.store');
    Route::get('services/{service_id}','ServicesController@show')->name('servies.show');
    Route::patch('services/{service_id}','ServicesController@update')->name('servies.update');
    Route::delete('services/{service_id}','ServicesController@destroy')->name('servies.destroy');
    
});