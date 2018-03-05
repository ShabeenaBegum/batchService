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

    Route::get('/user/profile', 'HomeController@index');

});

require 'batch.php';

Route::apiResource('enroll.batches', 'StudentBatchController');

require 'session-status.php';

require 'attendance.php';

Route::apiResource('session', 'SessionController');

Route::apiResource('studentsession', 'StudentSessionController');

