<?php

use Illuminate\Support\Facades\Route;

Route::patch('/batch/status/{batch}', 'BatchController@BatchStatusChange');

Route::patch('/batch/extrasession/{batch}', 'BatchController@BatchExtraSession');

Route::apiResource('batch', 'BatchController');