<?php

namespace App\Services\Tenant\Company;

use App\Models\Tenant\Company\Department;
use App\Services\App\AppService;
use App\Services\Tenant\TenantService;

/**
 * @method latest()
 */
class DepartmentService extends TenantService
{
    public function __construct(Department $department)
    {
        $this->model = $department;
    }
}