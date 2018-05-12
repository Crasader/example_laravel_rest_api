<?php

Route::prefix('auth')
    ->namespace('Auth')
    ->group(function () {
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout')->middleware('auth:api');
    });

// TODO: move route handling logic to the controller when the user resource is expanded
Route::get('user-info', function () {
    return response()->json(['data' => auth()->user()]);
})->middleware('auth:api');

Route::middleware('auth:api')
    ->namespace('API')
    ->group(
        function () {
            Route::post('pdfs/all', 'PdfController@storeAllTypes');
            Route::apiResource('pdfs', 'PdfController', ['except' => ['store']]);
        }
    );
