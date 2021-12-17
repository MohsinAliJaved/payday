<?php

namespace App\Models\Tenant\JobPost\Traits\Relationship;

use App\Models\Tenant\JobPost\JobPost;

trait JobTypeRelationship
{
    public function jobs()
    {
        return $this->hasMany(JobPost::class, 'applicant_id');
    }
}