<?php

namespace App\Http\Controllers;

use App\Events\Student\Session\AttendanceMarked;
use App\Student\Models\StudentBatch;
use App\Student\Models\StudentSession;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index($enrollId, $sessionId)
    {
        $studentBatch = StudentBatch::where("enroll_id", $enrollId)
                                    ->where("sessions.session_id", $sessionId)->firstOrFail();
        $session = $studentBatch->sessions->where("session_id", $sessionId)->first();
        return resOk($session);
    }

    public function store(Request $request, $enrollId, $sessionId)
    {
        $studentBatch = StudentBatch::where("enroll_id", $enrollId)
            ->where("sessions.session_id", $sessionId)->firstOrFail();
        $session = $studentBatch->sessions->where("session_id", $sessionId)->first();
        $session->attendance = strtoupper($request->get("attendance", config('constant.session.attendance.present')));
        $session->attendance_date = utcnow();
        $session->save();
        event(new AttendanceMarked($session));
        return resOk($session);
    }

    /*
     * return attendance of all enrolls in the given session
     */
    public function session()
    {

    }

    /*
     * return attendance of all enrolls in the given batch
     */
    public function batch()
    {

    }

    /*
     * return attendance of all enrolls in the given enroll id
     */
    public function enroll()
    {

    }

}
