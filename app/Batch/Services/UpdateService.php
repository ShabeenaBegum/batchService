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
                $session_list = $data['session_list'];
                if(($batch_data->start_date != $data ['start_date'])&&($batch_data->course_plan_id != $data ['course_plan_id'])) {
                    $batch_data->start_date = $data['start_date'];
                    $start_date = $data['start_date'];
                    $batch_data->course_plan_id     = $data ['course_plan_id'];
                }
                else if($batch_data->course_plan_id != $data ['course_plan_id']){
                    $batch_data->course_plan_id     = $data ['course_plan_id'];
                    $start_date = $batch_data->start_date;
                }
                else if($batch_data->start_date != $data ['start_date']) {
                    $batch_data->start_date = $data['start_date'];
                    $start_date = $data['start_date'];
                }
                $updated_date = BatchHelpers::getSessions($session_list,$start_date,$batch_data->days,$batch_data->mentor);
                $batch_data['session_list'] = $updated_date;
            }
        }
        $batch_data->updated_by = auth()->user()->_id;
        $batch_data->save();
        return $batch_data;

    }

    /**
     * @param $data
     * @param $batch
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus($data, $batch)
    {
//        info($batch);
        $batch->status = $data['type'];
        $type['by'] = $data['by'];
        $type['reason'] = $data['reason'];
//        $batch['cancel'] = [] ;
        info(gettype($batch['cancel']));
        $batch['cancel'] = [$type];

        $batch->save();
        return $batch;

    }

    public function updateExtraSession($data, $batch)
    {
        $weekMap = [
            0 => 'sunday',
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
        ];
        $extra_session=[];
        $i=0;
        $sessionlist = collect($batch['session_list']);
        info($sessionlist);
        $after_session = null;
        $sessions_array = null;
        if($data->has("after_session_id")){
            $after_session = $sessionlist->firstWhere("_id", $data->get("after_session_id"));
        } else if($data->has("session_date") && $data->has("session_time")) {
            $extra_session['_id'] = getUuid();
            $extra_session['heading'] = $data['session_heading'];
            $extra_session['topic'] = $data['session_topics'];
            $extra_session['date'] = Carbon::parse($data->get("session_date"))->format("Y-m-d");
            $extra_session['time'] = $data->get("session_time");
            $sessionlist->push($extra_session);
            $sessions_array = $sessionlist;
        }else{
            $after_session = $sessionlist->last();
            info($after_session);
        }
        if($after_session){
            $week_day = $weekMap[Carbon::parse($after_session['date'])->dayOfWeek];
            $j = 0;
            foreach ($batch['days'] as $day){
                if($j == count($batch['days'])){
                    $j=0;
                }
                $j++;
                if($day['day'] == $week_day){
                    info('break');
                    break;
                }
            }
            if($j == count($batch['days'])){
                $j=0;
            }
            $extra_session['_id'] = getUuid();
            $extra_session['heading'] = $data['session_heading'];
            $extra_session['topic'] = $data['session_topics'];
            $extra_session['date'] = Carbon::parse($after_session['date'])->modify("this " . $batch['days'][$j]['day'])->format("Y-m-d");
            $extra_session['time'] = $batch['days'][$j]['time'];
            if($j+1 == count($batch['days'])){
                $j=0;
            }
            $new_days = BatchHelpers::shift_key($batch['days'],$batch['days'][$j]);
            $new_start_date = Carbon::parse($extra_session['date'])->modify("this " . $batch['days'][$j]['day'])->format("Y-m-d");
            $day_index = array_search('friday', $batch['days']);
            foreach ($batch['session_list'] as $index => $sessions)
            {
                if($sessions['_id'] == $after_session['_id'])
                {
                    $after_session = BatchHelpers::split_array($batch['session_list'], $index+1);
//                    return $after_session;
                    $after_session_collection = collect($after_session[0])->push($extra_session);
                    $after_session_array = BatchHelpers::getSessions($after_session[1],  $new_start_date, $new_days, null, $batch['days'][$j+1]['day']);
                   break;
                }
            }
            $sessions_array = array_merge($after_session_collection->toArray(),$after_session_array);
        }
        if($sessions_array != null){
            $batch['session_list'] = $sessions_array;
            $batch->save();
        }

        return $batch;



    }

}