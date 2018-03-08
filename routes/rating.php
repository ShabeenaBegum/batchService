<?php

use Illuminate\Support\Facades\Route;

Route::get("/batch/{batch}/rating", "RatingController@batch")->name("batch.rating");

Route::get("/session/{session}/rating", "RatingController@session")->name("session.rating");

Route::apiResource('session.rating', 'RatingController', ['only' => ['index', 'store']]);