<?php

namespace App\Batch\Repositories;


use App\Batch\Models\Batch;
use App\Repositories\MEloquentRepository;

class BatchRepository extends MEloquentRepository
{


    /**
     * BatchRepository constructor.
     */
    public function __construct(Batch $batch)
    {
        parent::__construct($batch);
    }

    public function findBySessionId($sessionId)
    {
        return Batch::where("sessions._id",$sessionId)->firstOrFail();
    }

    public function findMany($ids = [], $noOfItems = 10)
    {
        if(count($ids)){
            return Batch::whereIn("_id", $ids)->paginate($noOfItems);
        }
        return Batch::paginate($noOfItems);
    }
}