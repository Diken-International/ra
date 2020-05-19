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


Route::group(['middleware' => ['jwt']], function () {

    Route::get('welcome', 'UserController@welcome')->name('users.welcome');

    Route::post('users/admin', 'UserController@createAdmin')->name('users.create.admin');

    Route::post('users','UserController@createRole')->name('users.create.role');

    Route::get('users/me','UserController@me')->name('users.me');

    Route::get('users','UserController@index')->name('users.index');

    Route::post('users/show','UserController@show')->name('users.show');

    Route::patch('users/update/{id}', 'UserController@update')->name('users.update');

    Route::delete('users/delete/{id}','UserController@destroy')->name('users.destroy');


    Route::post('services','ServicesController@createService')->name('servies.create');
});