<?php


namespace App\Student\Services;


use App\Student\Models\StudentBatch;

class BatchAssign
{
    public function handle($enrollId, $data)
    {
        return StudentBatch::create([
            "user_id" => $data->get("user_id"),
            "enroll_id" => $enrollId,
            "batch_id" => $data->get("batch_id"),
            "status" => config('constant.batch.status.active'),
            "assigned_by" => auth()->user()->_id,
            "sessions" => []
        ]);
    }
}
