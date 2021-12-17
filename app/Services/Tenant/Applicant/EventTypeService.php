<?php

namespace App\Services\Tenant\Applicant;

use App\Models\Tenant\Applicant\EventType;
use App\Services\Tenant\TenantService;

class EventTypeService extends TenantService
{
    public function __construct(EventType $eventType)
    {
        $this->model = $eventType;
    }
}
