<?php
/**
 * Created by PhpStorm.
 * User: pankaj
 * Date: 19/03/18
 * Time: 10:08 PM
 */

namespace App\Batch\Services\Transfers;


use App\BaseService;
use App\Batch\Models\Batch;
use App\Events\Student\Batch\BatchTransferred;
use App\Student\Models\StudentBatch;

class DifferentCoursePlan implements BaseService
{

    public function handle($data)
    {

        $currentBatch = StudentBatch::where("enroll_id", $data['enroll_id'])
            ->where("batch_id", $data["from_batch"])->firstOrFail();
        $currentBatch->status = config('constant.batch.status.transferred');
        $currentBatch->save();

        $toBatch = Batch::findOrFail($data["to_batch"]);

        $transfers = $currentBatch->transfers ?  $currentBatch->transfers : [];
        $temp = [];
        $temp['reason'] = $data["reason"];
        $temp['transferred_by'] = auth()->user()->_id;
        $temp['from_batch'] = $currentBatch->batch_id;
        $temp['to_batch'] = $toBatch->batch_id;

        $transfers[] = $temp;
        $currentBatch->transfers = $transfers;
        $currentBatch->save();

        $newBatch = StudentBatch::firstOrCreate([
            "user_id" => $currentBatch->user_id,
            "enroll_id" => $data['enroll_id'],
            "batch_id" => $data["to_batch"],
            "status" => config('constant.batch.status.active'),
            "assigned_by" => auth()->user()->_id,
        ]);
        //TODO: Check for finished sessions and add them to sessions array

        event(new BatchTransferred($currentBatch, $newBatch));

        return $newBatch;
    }
}