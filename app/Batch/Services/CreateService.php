<?php

namespace App\Batch\Services;

use App\Batch;
use App\Batch\BatchHelpers;
use Carbon\Carbon;


/**
 *
 */
class CreateService
{
    public function handle($data)
    {
        $data ['batch_name'] = $data ['start_date'];
        $data['course_session_details']['modules'] = BatchHelpers::getSessions(
            $data['course_session_details']['modules'],
            $data['start_date'],
            $data['days'],
            $data['mentor']);
        return Batch::create($data);
    }

}

?>