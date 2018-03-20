<?php

namespace App\Batch\Services\Batch;


use App\BaseService;
use App\Batch\Models\Batch;
use App\Batch\Repositories\BatchRepository;

class View implements BaseService
{
    public $bathRepo;

    /**
     * StatusChange constructor.
     * @param $bathRepo
     */
    public function __construct(BatchRepository $bathRepo)
    {
        $this->bathRepo = $bathRepo;
    }

    public function handle($data)
    {
        if ($data["batch_ids"]) {
            $id_array = explode(',', $data["batch_ids"]);
            $batches = $this->bathRepo->findMany($id_array);
            return resOk($batches);
        }
        return resOk($this->bathRepo->findMany());
    }
}