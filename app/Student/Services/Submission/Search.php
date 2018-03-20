<?php
/**
 * Created by PhpStorm.
 * User: pankaj
 * Date: 19/03/18
 * Time: 11:22 PM
 */

namespace App\Student\Services\Submission;


use App\BaseService;
use App\Student\Models\StudentBatch;

class Search implements BaseService
{

    public function handle($data)
    {
        $student_batch = StudentBatch::where('enroll_id',$data['enroll_id'])->firstOrFail();
        if($data['search'] == "all"){
            if($data['enroll_id'] && $data['session_id']){
                return $student_batch['sessions']->where('session_id',$data['session_id'])->first();
            } else if(isset($data['enroll_id'])) {
                return $student_batch['sessions'];
            }
        } else {
            if(isset($data['enroll_id']) && isset($data['session_id'])){
                return $student_batch['sessions']->where('session_id',$data['session_id'])->pluck($search);
            }
        }
        return [];
    }
}