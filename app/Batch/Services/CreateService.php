<?php

namespace App\Batch\Services;

use App\BaseService;
use App\Batch\BatchHelpers;
use App\Batch\Models\Batch;

class CreateService implements BaseService
{
    public function handle($data)
    {
        $data ['batch_name'] = $data ['start_date'];
        $sessionList = BatchHelpers::getSessions(
            $data['sessions'],
            $data['start_date'],
            $data['days'],
            $data['mentor']);
        unset($data['sessions']);
        $batch =  Batch::create($data);
        foreach ($sessionList as $session){
            $batch->sessions()->create($session);
        }
        return $batch;
    }

}

?>