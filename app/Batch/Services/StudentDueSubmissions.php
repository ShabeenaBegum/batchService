<?php
/**
 * Created by PhpStorm.
 * User: pankaj
 * Date: 19/03/18
 * Time: 11:12 PM
 */

namespace App\Batch\Services;


class StudentDueSubmissions
{
    public static function get($assignment_id, $project_id, $student_submission, $batch_submission,$enroll_id)
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
        }
        return [];
    }

}