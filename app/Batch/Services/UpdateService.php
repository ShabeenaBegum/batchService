<?php
/**
 * Created by PhpStorm.
 * User: shabe
 * Date: 2/21/2018
 * Time: 9:51 AM
 */

namespace App\Batch\Services;


use App\Batch;
use Carbon\Carbon;

class UpdateService
{

    public function handle($data, $batch_data){

        //$batch_data = Batch::getBatchDetails($id);

        $batch_data->start_date         = $data ['start_date'];
        $batch_data->status             = $data ['status'];
//        $batch_data->created_by         = $data ['created_by'];
        $batch_data->mode_of_training   = $data ['mode_of_training'];
        $batch_data->course_plan_id     = $data ['course_plan_id'];
        if($batch_data->course_plan_id != $data ['course_plan_id']){
            $i=0;
            $j = 0;
            $total_days_in_week = count($batch_data['days']);
            foreach ($batch_data['course_session_details']['modules'] as &$csd){
                foreach ($csd['session_list'] as &$sessions){
                    if($i == $total_days_in_week){
                        $i=0;
                    }
                    if($j==0){
                        $sessions['date'] = $data['start_date'];
                        $sessions['time'] = $data['days'][$j]['time'];
                        $temp = $data['start_date'];
                    } else {
                        $current_date = $temp;
                        $temp = Carbon::parse($current_date)->modify("this ".$data['days'][$i]['day']);
                        $sessions['date'] = $temp;
                        $sessions['time'] = $data['days'][$i]['time'];
                    }
                    $sessions['status'] = "pending";
                    $sessions['meeting'] = "";
//                    $sessions['mentor'] = $data['mentor'];
                    $i++;
                    $j++;
                }
            }
        }
        $batch_data->updated_by = auth()->user()->_id;
        $updated = $batch_data->save();
        if($updated)
            return "success";
        else
            return "error";
    }
}