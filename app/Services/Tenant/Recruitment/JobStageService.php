<?php


namespace App\Services\Tenant\Recruitment;

use App\Models\Tenant\Recruitment\JobStage;
use App\Services\Tenant\TenantService;

class JobStageService extends TenantService
{
    public function __construct(JobStage $jobStage)
    {
        $this->model = $jobStage;
    }
}