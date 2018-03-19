<?php

namespace App\Batch\Services;


use App\Batch\BatchHelpers;
use Carbon\Carbon;

class ExtraSession
{
    public function handle($data, $batch)
    {
        if (isset($data['session_date']) && isset($data['session_time'])) {
            $extra_session['heading'] = $data['session_heading'];
            $extra_session['topic'] = $data['session_topics'];
            $extra_session['date'] = Carbon::parse($data['session_date'])->format("Y-m-d");
            $extra_session['time'] = $data['session_time'];
            $batch->sessions()->create($extra_session);
            return $batch;
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
        $extra_session = [];
        $i = 0;
        $sessionlist = $batch->sessions()->get();
        $after_session = null;
        $sessions_array = null;

        if (isset($data['after_session_id'])) {
            $after_session = $sessionlist->firstWhere("_id", $data['after_session_id']);
        } else {
            $after_session = $sessionlist->last();
        }

        if ($after_session) {
            $week_day = $weekMap[Carbon::parse($after_session['date'])->dayOfWeek];
            $j = 0;
            foreach ($batch['days'] as $day) {
                if ($j == count($batch['days'])) {
                    $j = 0;
                }
                $j++;
                if ($day['day'] == $week_day) {
                    info('break');
                    break;
                }
            }
            if ($j == count($batch['days'])) {
                $j = 0;
            }

            $extra_session['heading'] = $data['session_heading'];
            $extra_session['topic'] = $data['session_topics'];
            $extra_session['date'] = Carbon::parse($after_session['date'])->modify("this " . $batch['days'][$j]['day'])->format("Y-m-d");
            $extra_session['time'] = $batch['days'][$j]['time'];

            if ($j + 1 == count($batch['days'])) {
                $j = 0;
            }


            $new_days = BatchHelpers::shift_key($batch['days'], $batch['days'][$j]);
            $new_start_date = Carbon::parse($extra_session['date'])->modify("this " . $batch['days'][$j]['day'])->format("Y-m-d");

            foreach ($batch['sessions'] as $index => $sessions) {

                if ($sessions['_id'] == $after_session['_id']) {
                    $after_session = BatchHelpers::split_array($batch['sessions'], $index + 1);
                    if (count($after_session[1])) {
                        $after_session_array = BatchHelpers::getSessions($after_session[1], $new_start_date, $new_days, null, $batch['days'][$j + 1]['day']);
                        foreach ($after_session_array as $afs) {
                            $afs->save();
                        }
                    }
                    $batch->sessions()->create($extra_session);
                    break;
                }
            }
        }
        return $batch->fresh();

    }

}