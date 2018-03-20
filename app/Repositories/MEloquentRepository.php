<?php

namespace App\Repositories;

use Jenssegers\Mongodb\Eloquent\Model;

class MEloquentRepository implements EloquentRepository
{

    public $model;

    /**
     * MEloquentRepository constructor.
     * @param $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function delete($model)
    {
        return $model->delete();
    }

    public function create($attributes)
    {
        return $this->model->create($attributes);
    }

    public function update($model, $attributes)
    {
        return $model->update($attributes);
    }

    public function createAssignment($data)
    {
        $data["type"] = "Assignment";

        return $this->create($data);
    }
}