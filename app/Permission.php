<?php

namespace App;

class Permission extends AgModel
{
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
