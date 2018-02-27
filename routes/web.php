<?php


use App\Batch\Models\Batch;

Route::get('/', function () {
    return view('welcome');
});

 Route::get("test", function(){
     return Batch::first();
 });
Route::view("passport", "passport");

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
