<?php
/**
 * Created by PhpStorm.
 * User: shabe
 * Date: 3/14/2018
 * Time: 3:02 PM
 */

namespace App\Batch\Services;


use App\BaseService;
use App\Batch\Models\Batch;
use App\Student\Models\StudentBatch;
use Carbon\Carbon;

class DueService implements BaseService
{
    /**
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function handle($data){
        if(isset($data['enroll_id'])){
            $student_submission = (StudentBatch::where('enroll_id',$data['enroll_id'])->first());
            $batch_submission = (Batch::where('_id',$student_submission['batch_id'])->first());
        } else if(isset($data['batch_id'])){
            $batch_submission = collect(Batch::where('_id',$data['batch_id'])->first());
        }
        $due_date = isset($batch_submission['due_date'])? $batch_submission['due_date'] : config('constant.batch.default_due_date');
        $today = Carbon::today();
        $assignment_id = null;
        $project_id = null;
        foreach ($batch_submission->sessions as $batch){

            if($batch['status'] == config('constant.session.status.completed')){
                if($today > ((string)$batch['completed_date']->addDays($due_date))){

                    $assignment_ids  = collect($batch['assignments'])->pluck('_id');
                    $project_ids  = collect($batch['projects'])->pluck('_id');
                    $assignment_id[] = $assignment_ids;
                    $project_id[] = $project_ids;
                }
            }
        }
        if($assignment_id != null){
            if(isset($data['enroll_id'])){
                return $this->getStudentDueSubmissions($assignment_id, $project_id, $student_submission, $batch_submission, $data['enroll_id']);
            } else if(isset($data['batch_id'])){

                $student_batches = collect(StudentBatch::where('batch_id',$data['batch_id'])->get());
                foreach ($student_batches as $student_batch){
                    if($student_batch['status'] == config('constant.batch.status.active'))
                        $student[] = $this->getStudentDueSubmissions($assignment_id, $project_id, $student_batch, $batch_submission, $student_batch['enroll_id']);
                }
                return $student;
            }
        }
        throw new \Exception("No Submission Due");
    }
}