<?php
/**
 * Created by PhpStorm.
 * User: shabe
 * Date: 2/23/2018
 * Time: 7:29 PM
 */

namespace App\Batch\Services;


use App\Batch\BatchHelpers;
use App\Batch\Models\Batch;
use Carbon\Carbon;

class StatusChangeService
{
    /**
     * @return mixed
     */
    public function handle($request, $session_id)
    {
        $batch = Batch::where("sessions._id",$session_id)->first();
        $cancelArr = [];
        $cancel['requested_by'] = $request->has('requested_by') ? $request->get('requested_by') : "";
        $cancel['approved_by'] = $request->has('approved_by') ? $request->get('approved_by') : "";
        $cancel['reason'] = $request->has('reason') ? $request->get('reason') : "";
        $cancel['cancelled_on'] = (string)Carbon::now();

        $session = collect($batch->sessions()->all())->where('_id',$session_id)->first();
        if($request->get('change_date') == 'false' ){
            $cancelArr = $session->cancellation;
            $cancelArr[] = $cancel;
            $session->cancellation = $cancelArr;
            $session->status = "cancel";
            $session->save();
            return $batch->fresh();
        }
        if($request->has('session_date') && $request->has('session_time') ) {
            $cancelArr = $session->cancellation;
            $cancelArr[] = $cancel;
            $session->cancellation = $cancelArr;
            $session->status = "cancel";
            $session->date = Carbon::parse($request->get('session_date'))->format('Y-m-d');
            $session->time = $request->get('session_time');
            $session->save();
            return $batch->fresh();
        }

        foreach ($batch['sessions'] as $index=>&$value)
        {
            if($value['_id']==$session_id){
                $session_split =  BatchHelpers::split_array($batch['sessions'], $index);
            }
        }
        $weekMap = [
            0 => 'sunday',
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
        ];
        $week_day = $weekMap[Carbon::parse($session['date'])->dayOfWeek];
        $j = 0;
        foreach ($batch['days'] as $day) {
            if ($j == count($batch['days'])) {
                $j = 0;
            }
            $j++;
            if ($day['day'] == $week_day) {
                break;
            }
        }
        if ($j == count($batch['days'])) {
            $j = 0;
        }
        $new_days = BatchHelpers::shift_key($batch['days'], $batch['days'][$j]);
        $new_start_date = Carbon::parse($session['date'])->modify("this " . $batch['days'][$j]['day'])->format("Y-m-d");

        if (count($session_split[1])) {
            if ($j + 1 == count($batch['days'])) {
                $j = 0;
            }
            $after_session_array = BatchHelpers::getSessions($session_split[1], $new_start_date, $new_days, null, $batch['days'][$j + 1]['day']);
            foreach ($after_session_array as $afs) {
                $afs->status = "cancel";
                $cancelArr = $session->cancellation;
                $cancelArr[] = $cancel;
                $afs->cancellation = $cancelArr;
                $afs->save();
            }
        }
        $batch->save();
        return $batch;
    }
}