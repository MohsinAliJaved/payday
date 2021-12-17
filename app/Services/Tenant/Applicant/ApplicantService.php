<?php

namespace App\Services\Tenant\Applicant;

use App\Services\Tenant\TenantService;
use App\Models\Tenant\Applicant\Applicant;

class ApplicantService extends TenantService
{
    public function __construct(Applicant $applicant)
    {
        $this->model = $applicant;
    }
}
