<?php
/**
 * Created by PhpStorm.
 * User: pankaj
 * Date: 19/03/18
 * Time: 5:07 PM
 */

namespace App\Student\Attendance;


use App\BaseService;
use App\Student\Models\StudentBatch;

class Add implements BaseService
{

    public function handle($data)
    {
        $studentBatch = StudentBatch::where("enroll_id", $data['enroll_id'])
            ->where("sessions.session_id", $data['session_id'])->firstOrFail();
        $session = $studentBatch->sessions->where("session_id", $data['session_id'])->first();
        $session->attendance = strtoupper($data['attendance']);
        $session->attendance_date = utcnow();
        $session->save();
        return $session;
    }
}