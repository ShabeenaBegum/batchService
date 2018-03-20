<?php

namespace App\Batch\Services\Batch;


use App\BaseService;
use App\Batch\Models\Batch;

class View implements BaseService
{

    public function handle($data)
    {
        if ($data["batch_ids"]) {
            $id_array = explode(',', $data["batch_ids"]);
            return resOk(Batch::whereIn('_id', $id_array)->get());
        }
        return resOk(Batch::paginate(10));
    }
}