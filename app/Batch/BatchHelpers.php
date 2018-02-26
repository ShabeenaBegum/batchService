<?php
/**
 * Created by PhpStorm.
 * User: shabe
 * Date: 2/22/2018
 * Time: 4:38 PM
 */

namespace App\Batch;


use Carbon\Carbon;

class BatchHelpers
{
    public static function getSessions(array $modules, $start_date, array $days, $mentor=null)
    {
        $total_days_in_week = count($days);
        $i = 0;
        $j = 0;
        $temp = "";
        foreach ($modules as &$csd) {
            $csd["_id"] = getUuid();
            foreach ($csd['session_list'] as &$sessions) {
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
                $sessions['meeting'] = "";
                $sessions['mentor'] = array_key_exists("mentor", $sessions) ? ($sessions['mentor'] != $mentor) ? $mentor : $sessions['mentor'] : $mentor;
                $sessions['_id'] = getUuid();
                $i++;
                $j++;
            }
        }

        return $modules;
    }
}