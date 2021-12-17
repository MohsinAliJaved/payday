<?php

namespace App\Services\Tenant\Recruitment;

use App\Services\Tenant\TenantService;
use App\Models\Tenant\Recruitment\Stage;

class StageService extends TenantService{
    
    public function __construct(Stage $stage)
    {
        $this->model = $stage;
    }
}