<?php

namespace App\Batch\Services;
use App\Batch;
use Carbon\Carbon;


/**
* 
*/
class CreateService
{
    public function handle($data)
	{
		$total_days_in_week = count($data['days']);
		$i=0;
        $j = 0;
        $data ['batch_name'] = $data ['start_date'];
        foreach ($data['course_session_details']['modules'] as &$csd){
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
                    $sessions['date'] = $temp->format("Y-m-d");
                    $sessions['time'] = $data['days'][$i]['time'];
                }
                $sessions['status'] = "pending";
                $sessions['meeting'] = "";
                $sessions['mentor'] = $data['mentor'];
                $i++;
                $j++;
            }
        }
	    return Batch::create($data);
	}

	public function getSessionDate($start_date, $day)
    {
        $gg = "this ".$day;
        info($gg);
        $session['date'] = Carbon::parse($start_date)->parse("this ".$day);
        $temp = $session['date'];
        return $session['date'];
        info($temp);

    }


}
?>