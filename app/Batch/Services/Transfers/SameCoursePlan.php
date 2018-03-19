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

class SameCoursePlan implements BaseService
{

    public function handle($data)
    {
        $currentBatch = StudentBatch::where("enroll_id", $data['enroll_id'])
            ->where("batch_id", $data["from_batch"])->firstOrFail();
        $toBatch = Batch::findOrFail($data["to_batch"]);
        $fromBatch = Batch::findOrFail($data["from_batch"]);
        $currentBatch->status = config('constant.batch.status.transferred');
        $currentBatch->save();
        $sessions = [];
        $toBatchSessions = $fromBatch->sessions;

        foreach ($currentBatch->sessions as $index => $session){
            unset($session['session_id']);
            unset($session['_id']);
            $session->transfer_date = utcnow();
            $session->session_id = $toBatchSessions[$index]['_id'];
            $sessions[] = $session;
        }

        $newBatch = StudentBatch::firstOrCreate([
            "user_id" => $currentBatch->user_id,
            "enroll_id" => $data['enroll_id'],
            "batch_id" => $data["to_batch"],
            "status" => config('constant.batch.status.active'),
            "assigned_by" => auth()->user()->_id,
        ]);

        foreach ($sessions as $newSession){
            $newBatch->sessions()->create($newSession->toArray());
        }

        event(new BatchTransferred($currentBatch, $newBatch));

        $transfers = $currentBatch->transfers ?  $currentBatch->transfers : [];
        $temp = [];
        $temp['reason'] = $data["reason"];
        $temp['transferred_by'] = auth()->user()->_id;
        $temp['from_batch'] = $currentBatch->batch_id;
        $temp['to_batch'] = $newBatch->batch_id;

        $transfers[] = $temp;
        $currentBatch->transfers = $transfers;
        $currentBatch->save();
        return [$currentBatch, $newBatch];
    }
}