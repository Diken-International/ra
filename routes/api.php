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

Route::get('files/{path}', 'FileController@show')
    ->where('path', '([/|.|\w|\s|-])*\.(?:jpg|gif|jpeg|png|docx|pdf)')
    ->name('files.show');

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

    Route::get('clients','ClientController@index')->name('clients.index');

    Route::get('services','ServicesController@index')->name('services.index');
    Route::post('services','ServicesController@store')->name('servies.store');
    Route::get('services/{service_id}','ServicesController@show')->name('servies.show');
    Route::patch('services/{service_id}','ServicesController@update')->name('servies.update');
    Route::delete('services/{service_id}','ServicesController@destroy')->name('servies.destroy');

    Route::get('services/{service_id}/messages','MessageController@index')->name('service.message.index');
    Route::post('services/{service_id}/messages','MessageController@store')->name('service.message.store');
    Route::patch('services/{service_id}/messages/{message_id}','MessageController@update')
    ->name('service.message.update');
    Route::delete('services/{service_id}/messages/{message_id}','MessageController@destroy')->name('service.message.destroy');


    Route::post('files', 'FileController@upload')->name('files.upload');

    Route::get('repairs','RepairsController@index')->name('repairs.index');
    Route::post('repairs','RepairsController@store')->name('repair.store');
    Route::put('repairs/{repair_id}','RepairsController@update')->name('repair.update');
    Route::get('repairs/products', 'RepairsController@products')->name('repair.products');
    Route::get('repairs/categories', 'RepairsController@categories')->name('repair.categories');

    Route::get('products','ProductsController@index')->name('products.index');
    Route::post('products','ProductsController@store')->name('products.store');
    Route::put('products/{product_id}','ProductsController@update')->name('products.update');
    Route::delete('products/{product_id}','ProductsController@destroy')->name('service.message.destroy');
});
