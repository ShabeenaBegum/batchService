<?php
/**
 * Created by PhpStorm.
 * User: shabe
 * Date: 2/21/2018
 * Time: 9:51 AM
 */

namespace App\Batch\Services;


use App\Batch;
use App\Batch\BatchHelpers;
use Carbon\Carbon;

class UpdateService
{

    public function handle($data, $batch_data)
    {
        $batch_data->status             = $data ['status'];
        $batch_data->mode_of_training   = $data ['mode_of_training'];
        $batch_data->batch_urgency      = $data ['batch_urgency'];
        $batch_data->mock_interview     = $data ['mock_interview'];
        $batch_data->location           = $data ['location'];
        if(isset($data['course_plan_id'])){
            if(($batch_data->course_plan_id != $data ['course_plan_id']) ||($batch_data->start_date != $data ['start_date']) ){
                if(($batch_data->start_date != $data ['start_date'])&&($batch_data->course_plan_id != $data ['course_plan_id'])) {
                    $modules = $data['course_session_details']['modules'];;
                    $batch_data->start_date = $data['start_date'];
                    $start_date = $data['start_date'];
                    $batch_data->course_plan_id     = $data ['course_plan_id'];
                }
                else if($batch_data->course_plan_id != $data ['course_plan_id']){
                    $batch_data->course_plan_id     = $data ['course_plan_id'];
                    $modules = $data['course_session_details']['modules'];
                    $start_date = $batch_data->start_date;
                }
                else if($batch_data->start_date != $data ['start_date']) {
                    $modules = $batch_data->course_session_details['modules'];
                    $batch_data->start_date = $data['start_date'];
                    $start_date = $data['start_date'];
                }
                $updated_date = BatchHelpers::getSessions($modules,$start_date,$batch_data->days,$batch_data->mentor);
                $batch_data['course_session_details'] = ['modules' => $updated_date];
            }
        }
        $batch_data->updated_by = auth()->user()->_id;
        $batch_data->save();
        return $batch_data;

    }
}