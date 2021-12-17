<?php

namespace App\Services\App\Applicant;

use App\Models\App\Applicant\Attendee;
use App\Services\App\AppService;

class AttendeeService extends AppService
{
    public function __construct(Attendee $attendee)
    {
        $this->model = $attendee;
    }
}
