<?php

use Illuminate\Support\Facades\File;

const ROUTES_API_DIR = "routes/api";

foreach (File::allFiles(base_path(ROUTES_API_DIR)) as $file){
    require_once((string)$file);
}