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

class DueServiceSession implements BaseService
{
    public function handle($data)
    {
        $batch_submission = Batch::where('sessions._id',$data['session_id'])->first();
        $session = $batch_submission->sessions->where('_id',$data['session_id'])->first();

        $due_date = isset($batch_submission['due_date'])? $batch_submission['due_date'] : 45;
        $today = Carbon::today();
        if($session){
            if($session['status'] == config('constant.session.status.completed')) {
                if ($today > ((string)$session['completed_date']->addDays($due_date))) {
                    $assignment_ids = collect($session['assignments'])->pluck('_id');
                    $project_ids = collect($session['projects'])->pluck('_id');
                    $assignment_id[] = $assignment_ids;
                    $project_id[] = $project_ids;
                } else {
                    return "Due date Not Crossed";
                }
            } else {
                return "session not completed";
            }
        }
        if(isset($data['enroll_id'])){
            $student_submission = collect(StudentBatch::where('enroll_id',$data['enroll_id'])->where('sessions.session_id',$data['session_id'])->first());
            return $this->getStudentDueSubmissions($assignment_id, $project_id, $student_submission, $batch_submission, $data['enroll_id']);
        } else if(isset($data['session_id'])){
            $student_batches = collect(StudentBatch::where('batch_id',$batch_submission['_id'])->get());

            foreach ($student_batches as $student_batch){
                $student[] = $this->getStudentDueSubmissions($assignment_id, $project_id, $student_batch, $batch_submission, $student_batch['enroll_id']);
            }
            return $student;
        }
    }
    public function getStudentDueSubmissions($assignment_id, $project_id, $student_submission, $batch_submission,$enroll_id)
    {
        if(count($student_submission['sessions']) >0) {

            foreach ($student_submission['sessions'] as $student) {
                $assignment_id_submitted[] = collect($student['assignments'])->pluck(['assignments_id']);
                $project_id_submitted[] = collect($student['projects'])->pluck(['projects_id']);
            }
            $assignment_not_submitted = collect($assignment_id)->flatten()->diff(collect($assignment_id_submitted)->flatten())->values()->toArray();

            $project_not_submitted = collect($project_id)->flatten()->diff(collect($project_id_submitted)->flatten())->values()->toArray();
            $assignment_details = array();

            $project_details = array();
            foreach ($batch_submission['sessions'] as $batch) {
                $assignment_details[] = collect($batch['assignments'])->whereIn('_id', $assignment_not_submitted)->all();
                $project_details[] = collect($batch['projects'])->whereIn('_id', $project_not_submitted);
            }
            $due_submission[$enroll_id]['assignment'] = $assignment_details;
            $due_submission[$enroll_id]['project'] = $project_details;
            return $due_submission;
        } else {
            return;
        }

    }

}