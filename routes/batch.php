<?php

use Illuminate\Support\Facades\Route;

Route::patch('batch/status/{batch}', 'BatchController@BatchStatusChange')->name('batch.status');

Route::patch('batch/extrasession/{batch}', 'BatchController@BatchExtraSession')->name('batch.extrasession');

Route::apiResource('batch', 'BatchController');