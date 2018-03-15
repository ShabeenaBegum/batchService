<?php

use Illuminate\Support\Facades\File;

foreach (File::allFiles(base_path("routes/api")) as $file){
    require((string)$file);
}
