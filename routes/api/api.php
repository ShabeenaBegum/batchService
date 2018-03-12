<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return resOk($request->user());
    });
    Route::get('/user/profile', 'HomeController@index');
});

Route::apiResource('enroll.batches', 'StudentBatchController');

Route::apiResource('session', 'SessionController');

Route::apiResource('content', 'ContentController');