<?php
/**
 * Created by PhpStorm.
 * User: shabe
 * Date: 2/22/2018
 * Time: 4:38 PM
 */

namespace App\Batch;


use Carbon\Carbon;
use Illuminate\Support\Collection;

class BatchHelpers
{
    public static function getSessions($session_list, $start_date, array $days, $mentor=null, $start_day = null)
    {
        $total_days_in_week = count($days);
        $i = 0;
        $j = 0;
        $temp = "";
        foreach ($session_list as &$sessions) {
            if ($i == $total_days_in_week) {
                $i = 0;
            }
            if ($j == 0) {
                $sessions['date'] = $start_date;
                $sessions['time'] = $days[$j]['time'];
                $temp = $start_date;
            } else {
                $current_date = $temp;
                $temp = Carbon::parse($current_date)->modify("this " . $days[$i]['day']);
                $sessions['date'] = $temp->format("Y-m-d");
                $sessions['time'] = $days[$i]['time'];
            }
            $sessions['status'] = "pending";
            $sessions['meeting'] = [];
            $sessions['mentor'] = array_key_exists("mentor", $sessions) ? ($sessions['mentor'] != $mentor) ? $mentor : $sessions['mentor'] : $mentor;
            $i++;
            $j++;
        }
        return $session_list;
    }

    public static function split_array($array, $offset)
    {
        if($array instanceof Collection){
            $after = $array->splice($offset);
            $before = $array->slice(0, $offset);
            return [$before, $after];
        }else{
            $after = array_slice($array, $offset);
            $before = array_slice($array, 0,$offset);
            return [$before, $after];
        }
    }

    public static function shift_key($array, $key){
        $index = array_search($key, $array);
        if($index){
            list($first, $second) = static::split_array($array, $index);
            return array_merge($second, $first);
        }
        return $array;
    }
}



