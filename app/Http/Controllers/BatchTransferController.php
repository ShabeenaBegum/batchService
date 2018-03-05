<?php

namespace App\Http\Controllers;

use App\Batch\Models\Batch;
use App\Events\Student\Batch\BatchTransferred;
use App\Student\Models\StudentBatch;
use Illuminate\Http\Request;

class BatchTransferController extends Controller
{
    public function transfer(Request $request, $enrollId)
    {

        $this->validate($request, [
            "from_batch" => "required|exists:batches,_id|different:to_batch",
            "to_batch" => "required|exists:batches,_id|different:from_batch",
            "reason" => "required"
        ]);

        $toBatch = Batch::findOrFail($request->get("to_batch"));
        $fromBatch = Batch::findOrFail($request->get("from_batch"));

        if($toBatch->course_plan_id === $fromBatch->course_plan_id){
            $this->transferToSameCoursePlan($enrollId, $request);
        }else{
            $this->transferToDifferentCoursePlan($enrollId, $request);
        }

    }

    private function transferToSameCoursePlan($enrollId, $request)
    {
        $currentBatch = StudentBatch::where("enroll_id", $enrollId)->where("batch_id", $request->get("from_batch"))->firstOrFail();

        $toBatch = Batch::findOrFail($request->get("to_batch"));
        $fromBatch = Batch::findOrFail($request->get("from_batch"));

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
            "enroll_id" => $enrollId,
            "batch_id" => $request->get("to_batch"),
            "status" => config('constant.batch.status.active'),
            "assigned_by" => auth()->user()->_id,
        ]);

        foreach ($sessions as $newSession){
            $newBatch->sessions()->create($newSession->toArray());
        }

        event(new BatchTransferred($currentBatch, $newBatch));

        $transfers = $currentBatch->transfers ?  $currentBatch->transfers : [];
        $temp = [];
        $temp['reason'] = $request->get("reason");
        $temp['transferred_by'] = auth()->user()->_id;
        $temp['from_batch'] = $currentBatch->batch_id;
        $temp['to_batch'] = $newBatch->batch_id;

        $transfers[] = $temp;
        $currentBatch->transfers = $transfers;
        $currentBatch->save();

        return [$currentBatch, $newBatch];
    }

    private function transferToDifferentCoursePlan($enrollId, $request)
    {
        $currentBatch = StudentBatch::where("enroll_id", $enrollId)->where("batch_id", $request->get("from_batch"))->firstOrFail();
        $currentBatch->status = config('constant.batch.status.transferred');
        $currentBatch->save();

        $toBatch = Batch::findOrFail($request->get("to_batch"));

        $transfers = $currentBatch->transfers ?  $currentBatch->transfers : [];
        $temp = [];
        $temp['reason'] = $request->get("reason");
        $temp['transferred_by'] = auth()->user()->_id;
        $temp['from_batch'] = $currentBatch->batch_id;
        $temp['to_batch'] = $toBatch->batch_id;

        $transfers[] = $temp;
        $currentBatch->transfers = $transfers;
        $currentBatch->save();


        $newBatch = StudentBatch::firstOrCreate([
            "user_id" => $currentBatch->user_id,
            "enroll_id" => $enrollId,
            "batch_id" => $request->get("to_batch"),
            "status" => config('constant.batch.status.active'),
            "assigned_by" => auth()->user()->_id,
        ]);
        //TODO: Check for finished sessions and add them to sessions array

        event(new BatchTransferred($currentBatch, $newBatch));

        return resOk($newBatch);
    }
}
