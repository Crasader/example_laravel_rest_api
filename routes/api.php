<?php

Route::prefix('auth')
    ->namespace('Auth')
    ->group(function () {
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout')->middleware('auth:api');
    });

Route::middleware('auth:api')
    ->namespace('API')
    ->group(
        function () {
            Route::post('pdfs/all', 'PdfController@storeAllTypes');
            Route::apiResource('pdfs', 'PdfController', ['except' => ['store']]);
        }
    );
