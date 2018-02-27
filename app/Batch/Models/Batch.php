<?php

namespace App\Batch\Models;

use App\AgModel;

class Batch extends AgModel
{
    public function sessions()
    {
        return $this->embedsMany(Session::class);
    }
}
