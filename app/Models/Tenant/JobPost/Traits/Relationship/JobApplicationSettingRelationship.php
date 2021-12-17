<?php

namespace App\Models\Tenant\JobPost\Traits\Relationship;

use App\Models\Tenant\JobPost\JobPost;

trait JobApplicationSettingRelationship
{
    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }
}
