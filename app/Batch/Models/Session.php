<?php

namespace App\Batch\Models;


use App\AgModel;
use App\Student\Models\StudentBatch;

class Session extends AgModel
{
    public function enrolls()
    {
        $batchId = $this->parentRelation->value("_id");
        return StudentBatch::where("batch_id", $batchId)
                ->where("status", config('constant.Student_batch.status.active'))
                ->get();
    }
}