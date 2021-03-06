<?php
/**
 * Created by PhpStorm.
 * User: pankaj
 * Date: 19/03/18
 * Time: 10:49 PM
 */

namespace App\Student\Services\Rating;


use App\BaseService;
use App\Student\Models\StudentBatch;

class Show implements BaseService
{

    public function handle($data)
    {
        $studentBatch = StudentBatch::where("enroll_id", $data['enroll_id'])
            ->where("sessions.session_id", $data['session_id'])
            ->firstOrFail();

        $session =  $studentBatch->sessions->where("session_id", $data['session_id'])->first();

        return $session;
    }
}