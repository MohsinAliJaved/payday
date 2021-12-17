<?php

namespace App\Services\Tenant\JobPost;

use App\Models\Tenant\JobPost\JobPost;
use App\Services\Tenant\TenantService;

class JobPostService extends TenantService
{
    public function __construct(JobPost $jobPost)
    {
        $this->model = $jobPost;
    }
}
