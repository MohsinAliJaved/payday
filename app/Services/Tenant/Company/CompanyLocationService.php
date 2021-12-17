<?php


namespace App\Services\Tenant\Company;


use App\Models\Tenant\Company\CompanyLocation;
use App\Services\Tenant\TenantService;

class CompanyLocationService extends TenantService
{
    public function __construct(CompanyLocation $companyLocation)
    {
        $this->model = $companyLocation;
    }
}