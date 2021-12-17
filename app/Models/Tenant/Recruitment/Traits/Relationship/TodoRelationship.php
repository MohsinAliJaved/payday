<?php

namespace App\Models\Tenant\Recruitment\Traits\Relationship;

use App\Models\Core\Auth\User;
use App\Models\Core\Status;

trait TodoRelationship
{
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'status_id');
    }
}