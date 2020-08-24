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

Route::get('download/file/plan_week', 'LettersController@planWeek')->name('download.plan.week');

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
    Route::put('users/{user_id}', 'UserController@update')->name('users.update');
    Route::delete('users/{user_id}','UserController@destroy')->name('users.destroy');
    Route::get('users/{user_id}','UserController@show')->name('users.show');
    Route::post('users/admin', 'UserController@createAdmin')->name('users.create.admin');
    Route::get('users/me','UserController@me')->name('users.me');

    Route::get('clients','ClientController@index')->name('clients.index');
    Route::patch('clients/{user_id}','ClientController@update')->name('clients.update');
    Route::get('clients/{user_id}','ClientController@show')->name('clients.show');
    Route::post('clients/{user_id}/product-relation','ClientController@addProduct')->name('clients.add.products');
    Route::get('clients/{user_id}/product-relation','ClientController@listProduct')->name('clients.list.products');
    Route::get('clients/{user_id}/product-relation/{product_id}','ClientController@detailProduct')->name('clients.detail.products');

    Route::get('services','ServicesController@index')->name('services.index');
    Route::post('services','ServicesController@store')->name('services.store');
    Route::get('services/{service_id}','ServicesController@show')->name('services.show');
    Route::patch('services/{service_id}','ServicesController@update')->name('services.update');
    Route::delete('services/{service_id}','ServicesController@destroy')->name('services.destroy');

    Route::get('services/{service_id}/reports/{report_id}','ServicesController@reportShow')->name('services.report.show');
    Route::patch('services/{service_id}/reports/{report_id}','ServicesController@reportUpdate')->name('services.report.update');

    Route::get('services/{service_id}/messages','MessageController@index')->name('service.message.index');
    Route::post('services/{service_id}/messages','MessageController@store')->name('service.message.store');
    Route::put('services/{service_id}/messages/{message_id}','MessageController@update')
    ->name('service.message.update');
    Route::delete('services/{service_id}/messages/{message_id}','MessageController@destroy')->name('service.message.destroy');


    Route::post('files', 'FileController@upload')->name('files.upload');
    Route::delete('files/{file_id}', 'FileController@delete')->name('files.delete');

    Route::get('repairs','RepairsController@index')->name('repairs.index');
    Route::post('repairs','RepairsController@store')->name('repair.store');
    Route::put('repairs/{repair_id}','RepairsController@update')->name('repair.update');
    Route::get('repairs/products', 'RepairsController@products')->name('repair.products');
    Route::get('repairs/categories', 'RepairsController@categories')->name('repair.categories');

    Route::get('products','ProductsController@index')->name('products.index');
    Route::post('products','ProductsController@store')->name('products.store');
    Route::put('products/{product_id}','ProductsController@update')->name('products.update');
    Route::delete('products/{product_id}','ProductsController@destroy')->name('service.message.destroy');

    Route::post('download/file/reception', 'LettersController@reception')->name('download.reception');
    Route::post('download/file/warranty', 'LettersController@warranty')->name('download.warranty');
    Route::post('download/file/plan_week', 'LettersController@planWeek')->name('download.plan.week');

    Route::get('todo','TodoController@index')->name('todo.index');
    Route::post('todo','TodoController@store')->name('todo.store');
    Route::patch('todo/{todo_id}','TodoController@update')->name('todo.update');
});
