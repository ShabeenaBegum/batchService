<?php


Route::get('/', function () {
    return view('welcome');
});

Route::get("test", function () {
});
Route::view("passport", "passport");

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
