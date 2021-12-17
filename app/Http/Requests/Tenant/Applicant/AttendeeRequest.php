<?php

namespace App\Http\Requests\App\Applicant;

use App\Http\Requests\App\AppRequest;
use App\Models\App\Applicant\Attendee;

class AttendeeRequest extends AppRequest
{
    public function rules()
    {
        return $this->initRules( new Attendee());
    }
}
