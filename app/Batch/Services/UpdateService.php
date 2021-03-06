<?php

namespace App\Batch\Services;

use App\BaseService;
use App\Batch\BatchHelpers;

class UpdateService implements BaseService
{

    public function handle($data)
    {
        $batch_data = $data["batch_details"];
        $batch_data->status = $data ['status'];
        $batch_data->mode_of_training = $data ['mode_of_training'];
        $batch_data->batch_urgency = $data ['batch_urgency'];
        $batch_data->mock_interview = $data ['mock_interview'];
        $batch_data->location = $data ['location'];
        if (isset($data['course_plan_id'])) {
            if (($batch_data->course_plan_id != $data ['course_plan_id']) || ($batch_data->start_date != $data ['start_date'])) {
                $session_list = $data['sessions'];
                if (($batch_data->start_date != $data ['start_date']) && ($batch_data->course_plan_id != $data ['course_plan_id'])) {
                    $batch_data->start_date = $data['start_date'];
                    $start_date = $data['start_date'];
                    $batch_data->course_plan_id = $data ['course_plan_id'];
                } else if ($batch_data->course_plan_id != $data ['course_plan_id']) {
                    $batch_data->course_plan_id = $data ['course_plan_id'];
                    $start_date = $batch_data->start_date;
                } else if ($batch_data->start_date != $data ['start_date']) {
                    $batch_data->start_date = $data['start_date'];
                    $start_date = $data['start_date'];
                }
                $updated_date = BatchHelpers::getSessions($session_list, $start_date, $batch_data->days, $batch_data->mentor);
                $batch_data['sessions'] = $updated_date;
            }
        }
        $batch_data->updated_by = auth()->user()->_id;
        $batch_data->save();
        return $batch_data;

    }
}