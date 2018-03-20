<?php

namespace App\Repositories;

interface EloquentRepository
{
    public function find($id);
    public function delete($model);
    public function create($attributes);
    public function update($id, $attributes);
}