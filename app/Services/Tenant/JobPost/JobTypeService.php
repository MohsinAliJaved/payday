<?php

namespace App\Services\Tenant\JobPost;

use App\Models\Tenant\JobPost\JobType;
use App\Services\Tenant\TenantService;

class JobTypeService extends TenantService
{
    public function __construct(JobType $jobType)
    {
        $this->model = $jobType;
    }
}