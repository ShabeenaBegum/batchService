<?php

use Illuminate\Support\Facades\Route;

Route::get("/batch/{batch}/session/status", "SessionStatusController@batch")->name("batch.sessionstatus.index");

Route::apiResource('session.status', 'SessionStatusController', ['only' => [
    'index', 'store'
]]);