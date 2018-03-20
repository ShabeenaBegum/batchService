<?php
/**
 * Created by PhpStorm.
 * User: pankaj
 * Date: 19/03/18
 * Time: 10:52 PM
 */

namespace App\Student\Services\Rating;


use App\BaseService;
use App\Batch\Models\Batch;

class Session implements BaseService
{

    public function handle($data)
    {
        $batch = Batch::where("sessions._id", $data['session_id'])->firstOrFail();
        $session = $batch->sessions->where("_id", $data['session_id'])->first();
        return ($session);
    }
}