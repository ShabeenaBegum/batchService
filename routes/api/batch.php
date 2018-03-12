<?php

use Illuminate\Support\Facades\Route;

Route::post('/enroll/{enroll}/transfer', 'BatchTransferController@transfer')->name('enroll.transfer');

Route::patch('batch/status/{batch}', 'BatchController@BatchStatusChange')->name('batch.status');

Route::patch('batch/extrasession/{batch}', 'BatchController@BatchExtraSession')->name('batch.extrasession');

Route::apiResource('batch', 'BatchController');