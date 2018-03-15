<?php


use App\Student\Models\StudentBatch;

Route::get('/', function () {
    return view('welcome');
});

Route::get("test", function () {

    $a1=array(
        "sdjasbdjasb342-4543",
        "sdfsdfsd-4543"
    );
    $a2=array("sdf");

    $result=array_diff($a1,$a2);
    print_r($result);
});
Route::view("passport", "passport");

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
