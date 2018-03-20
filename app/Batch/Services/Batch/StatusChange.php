<?php
namespace App\Batch\Services\Batch;


use App\BaseService;
use App\Batch\Repositories\BatchRepository;

class StatusChange implements BaseService
{

    public function handle($data)
    {
        $batch = $data['batch_details'];
        $batch->status = $data['type'];
        $type['by'] = $data['by'];
        $type['reason'] = $data['reason'];
        info(gettype($batch['cancel']));
        if(isset($batch['cancel'])){
            $cancels = $batch['cancel'];
        }
        $batch['cancel'] = $cancels[] = $type;
        $batch->save();
        return $batch;
    }
}