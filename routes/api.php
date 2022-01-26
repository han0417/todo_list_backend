<?php

use Illuminate\Support\Facades\Route;

Route::namespace('App\Http\Controllers')->group(function () {
    Route::post('/login', 'AdminStoreAccountController@login');
    Route::post('/logout', 'AdminStoreAccountController@logout');
    Route::post('/accounts', 'AdminStoreAccountController@create');

    //過jwt middleware
    Route::group(['middleware' => ['jwt']], function () {
        Route::get('/todos', 'TodoController@index');
        Route::get('/todos/{todo_id}', 'TodoController@show');
        Route::post('/todos', 'TodoController@create');

        Route::put('/todos/{todo_id}', 'TodoController@update');
        Route::delete('/todos/{todo_id}', 'TodoController@delete');
    });
});
