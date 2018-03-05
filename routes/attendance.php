<?php

use Illuminate\Support\Facades\Route;

Route::get("/session/{session}/attendance", "AttendanceController@session")
        ->name("session.attendance.index");

Route::get("/batch/{batch}/attendance", "AttendanceController@batch")
        ->name("batch.attendance.index");

Route::get("/enroll/{enroll}/attendance", "AttendanceController@enroll")
    ->name("enroll.attendance.index");

Route::apiResource('enroll.session.attendance', 'AttendanceController', [
    'only' => [ 'index', 'store']
]);