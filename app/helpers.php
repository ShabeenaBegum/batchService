<?php

use Ramsey\Uuid\Uuid;

function resOk($data, $status = 200){
    return response()->json([
        "success" => true,
        "data" => $data
    ], $status);
}

function resError($data = ["message" => "Something went wrong"], $status = 500){
    return response()->json([
        "success" => false,
        "data" => $data
    ], $status);
}

function getUuid(){
    /*if(env("APP_ENV") == "testing"){
        return "123-456-789";
    }*/
    return Uuid::uuid4()->toString();
}