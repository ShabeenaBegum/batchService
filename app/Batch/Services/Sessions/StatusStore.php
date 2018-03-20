<?php
/**
 * Created by PhpStorm.
 * User: pankaj
 * Date: 19/03/18
 * Time: 11:37 PM
 */

namespace App\Batch\Services\Sessions;


use App\BaseService;
use App\Batch\Models\Batch;

class StatusStore implements BaseService
{

    public function handle($data)
    {
        $batch = Batch::where("sessions._id", $data["session_id"])->firstOrFail();
        $session = $batch->sessions->where("_id", $data["session_id"])->first();
        $session->status = config('constant.session.status.completed');
        $date = utcnow();
        if($data['completed_date']){
            $date = utcnow($data['completed_date']);
        }
        $session->completed_date = $date;
        $session->save();
        return $session;

    }
}