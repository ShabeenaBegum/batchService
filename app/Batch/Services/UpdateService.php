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
        $batch['cancel'] = [] ;
        info(gettype($batch['cancel']));
        $batch['cancel'][] = [[]=>$type];

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

        $modules = collect($batch['course_session_details']['modules']);
        $module = null;
        if($data->has("module_id")){
            $module = $modules->where("_id", $data->get("module_id"))->first();
        }else{
           $module=  $modules->first();
        }

        $sessionlist = collect($module['session_list']);


        $session = $sessionlist->firstWhere("_id", $data->get("after_session_id"));
        if($session){
            $week_day = $weekMap[Carbon::parse($session['date'])->dayOfWeek];
            $j = 0;
            foreach ($batch['days'] as $day){
                if($day['day'] == $week_day){
                    info('break');
                    break;
                }
                if($j == count($batch['days'])){
                    $j=0;
                    info('j=0');
                }
                $j++;
                info($j);
            }
//            return $j;
            if($j == count($batch['days'])){
                $j=0;
            }
            $extra_session['_id'] = getUuid();
            $extra_session['heading'] = $data['session_heading'];
            $extra_session['topic'] = $data['session_topics'];
            $extra_session['date'] = Carbon::parse($session['date'])->modify("this " . $batch['days'][$j]['day'])->format("Y-m-d");
            $extra_session['time'] = $batch['days'][$j]['time'];
        }

            $sessionlist->push($extra_session);
        $module['session_list'] = $sessionlist;

        return $module;
        /*list($beforeSessions, $afterSession) = $sessionlist->partition(function ($i) use($data){
            return $i['_id']  == $data->get("after_session_id");
        });
        $beforeSessions->push($extra_session);

        return array_merge($beforeSessions->toArray(),$afterSession->toArray());*/


//        foreach ($batch['course_session_details']['modules'] as $csd) {
//            foreach ($csd['session_list'] as &$sessions) {
//                if(isset($data['after_session_id'])){
//                    if($sessions['_id'] == $data['after_session_id']){
//                        $i=1;
//                        $week_day = $weekMap[Carbon::parse($sessions['date'])->dayOfWeek];
//                        $j = 0;
//                        foreach ($batch['days'] as $day){
//                            if($j == count($batch['days'])){
//                                $j=0;
//                            }
//                            if($day['day'] == $week_day){
//                                break;
//                            }
//                            $j++;
//                        }
//                        $extra_session['_id'] = "dffld";
//                        $extra_session['heading'] = $data['session_heading'];
//                        $extra_session['topic'] = $data['session_topics'];
//                        $extra_session['date'] = Carbon::parse($sessions['date'])->modify("this " . $batch['days'][$j]['day'])->format("Y-m-d");
//                        $extra_session['time'] = $batch['days'][$j]['time'];
////
//                    }
//                }
//                if($i == 1){
////                    return $sessions;
//                    $csd['session_list'][] = $extra_session;
////                   / return $csd['session_list'];
//                }
//            }
//
//        }
//        return $batch['course_session_details']['modules'];
    }
}