<?php

function resOk($data, $status = 200){
    return response()->json([
        "success" => true,
        "data" => $data
    ], $status);
}

function resError($data, $status = 500){
    return response()->json([
        "success" => false,
        "data" => $data
    ], $status);
}