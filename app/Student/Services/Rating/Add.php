<?php
/**
 * Created by PhpStorm.
 * User: pankaj
 * Date: 19/03/18
 * Time: 10:54 PM
 */

namespace App\Student\Services\Rating;


use App\BaseService;
use App\Student\Models\StudentBatch;

class Add implements BaseService
{

    public function handle($data)
    {
        $studentBatch = StudentBatch::where("enroll_id", $data["enroll_id"])
            ->where("sessions.session_id", $data["session_id"])->first();
        $session = $studentBatch->sessions->where("session_id", $data["session_id"])->first();
        $rating = $data['rating'];
        $rating['rated_on'] = utcnow();
        $session->rating = $rating;
        $session->save();
        return $session;
    }
}