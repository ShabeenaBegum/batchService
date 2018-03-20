<?php

namespace App\Http\Controllers;

use App\Events\Student\Session\AttendanceMarked;
use App\Student\Attendance\Add;
use App\Student\Attendance\View;
use App\Student\Models\StudentBatch;
use App\Student\Models\StudentSession;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index($enrollId, $sessionId, View $service)
    {
        $attendance = $service->handle(['enroll_id' => $enrollId, 'session_id' => $sessionId]);
        return resOk($attendance);
    }

    public function store($enrollId, $sessionId, Add $service)
    {
        $data['enroll_id'] = $enrollId;
        $data['session_id'] = $sessionId;
        $data['attendance'] = request("attendance", config('constant.session.attendance.present'));
        $attendance = $service->handle($data);
        event(new AttendanceMarked($attendance));
        return resOk($attendance);
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
