<?php

namespace App\Http\Controllers;

use App\Batch\Models\Batch;
use App\Batch\Services\Transfers\DifferentCoursePlan;
use App\Batch\Services\Transfers\SameCoursePlan;
use App\Events\Student\Batch\BatchTransferred;
use App\Http\Requests\Batch\Transfer;
use App\Student\Models\StudentBatch;
use Illuminate\Http\Request;

class BatchTransferController extends Controller
{
    public function transfer(Transfer $request, $enrollId)
    {
        $data = $request->all();
        $data['enroll_id'] = $enrollId;

        $toBatch = Batch::findOrFail($data["to_batch"]);
        $fromBatch = Batch::findOrFail($data["from_batch"]);

        if($toBatch->course_plan_id === $fromBatch->course_plan_id){
            return resOk((new SameCoursePlan)->handle($data));
        }else{
            return resOk((new DifferentCoursePlan)->handle($data));
        }
    }
}
