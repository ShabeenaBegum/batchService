<?php
/**
 * Created by PhpStorm.
 * User: shabe
 * Date: 3/5/2018
 * Time: 6:03 PM
 */

namespace App\Batch\Services;


use App\Student\Models\StudentBatch;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContentService
{
    /**
     * @param $request
     * @return mixed
     */
    public function handle($request,$session,$student_batch,$type)
    {

        $submission = $session->$type;
        $submit[$request->get('content_type').'_id'] = $request->get('content_id');
//        $submit['submission_link'] = $request->get('submission_link');
//        $submit['submission_date'] = (string)Carbon::now();
//        $submit['status'] = "pending";
        $submit['created_at'] = (string)Carbon::now();
        $submit['submission_id'] = $request->get('submission_id');
        $submission[] = ($submit);
        $session->$type = $submission;
        //TODO send Point service data($submit)->link,submission_id, session_id,batch_id,type
        $session->save();
        return $student_batch;
    }
}