<?php

Route::prefix('auth')
    ->namespace('Auth')
    ->group(function () {
        Route::post('login', 'AuthController@login');

        Route::middleware('auth:api')
            ->group(function () {
                Route::post('logout', 'AuthController@logout');
                Route::get('user-info', 'AuthController@getUserInfo');
            });
    });

Route::middleware('auth:api')
    ->namespace('API')
    ->group(function () {
        Route::post('pdfs/all', 'PdfController@storeAllTypes');
        Route::apiResource('pdfs', 'PdfController', ['except' => ['store']]);
    });
