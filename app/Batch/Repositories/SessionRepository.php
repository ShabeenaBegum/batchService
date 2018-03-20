<?php

namespace App\Batch\Repositories;


use App\Batch\Models\Session;
use App\Repositories\MEloquentRepository;

class SessionRepository extends MEloquentRepository
{
    /**
     * BatchRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(new Session());
    }
}