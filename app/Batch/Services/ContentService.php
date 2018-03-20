<?php
/**
 * Created by PhpStorm.
 * User: shabe
 * Date: 3/5/2018
 * Time: 6:03 PM
 */

namespace App\Batch\Services;


use App\BaseService;
use App\Student\Models\StudentBatch;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContentService implements BaseService
{
    /**
     * @param $request
     * @return mixed
     */
    public function handle($data)
    {

        $submission = $data['session'][$data['type']];
        $submit[$data['content_type'].'_id'] = $data['content_id'];
//        $submit['submission_link'] = $request->get('submission_link');
//        $submit['submission_date'] = (string)Carbon::now();
//        $submit['status'] = "pending";
        $submit['created_at'] = (string)Carbon::now();
        $submit['submission_id'] = $data['submission_id'];
        $submission[] = ($submit);
        $data['session'][$data['type']] = $submission;
        //TODO send Point service data($submit)->link,submission_id, session_id,batch_id,type
        $data['session']->save();
        return $data['student_batch'];
    }
}