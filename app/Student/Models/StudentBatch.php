<?php

namespace App\Student\Models;

use App\AgModel;
use App\Batch\Models\Batch;

class StudentBatch extends AgModel
{
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function sessions()
    {
        return $this->embedsMany(StudentSession::class);
    }
}
