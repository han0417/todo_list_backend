<?php

use Illuminate\Support\Facades\Route;

Route::namespace('App\Http\Controllers')->group(function () {
    Route::post('/login', 'AdminStoreAccountController@login');
    Route::post('/logout', 'AdminStoreAccountController@logout');
    Route::post('/accounts', 'AdminStoreAccountController@create');


    Route::group(['middleware' => ['jwt']], function () {

    });
});
