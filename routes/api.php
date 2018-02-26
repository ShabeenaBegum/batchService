<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BatchController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::middleware('auth:api')->group(function () {

    Route::get('/user', function (Request $request) {
        return resOk($request->user());
    });

    Route::get('user/profile', 'HomeController@index');

});
Route::patch('batch/status/{batch}', 'BatchController@BatchStatusChange');

Route::patch('batch/extrasession/{batch}', 'BatchController@BatchExtraSession');

Route::apiResource('batch', 'BatchController');

