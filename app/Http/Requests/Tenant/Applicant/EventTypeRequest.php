<?php

namespace App\Http\Requests\Tenant\Applicant;

use App\Http\Requests\BaseRequest;
use App\Models\Tenant\Applicant\EventType;
use Illuminate\Foundation\Http\FormRequest;

class EventTypeRequest extends BaseRequest
{
    public function rules()
    {
        return $this->initRules( new EventType());
    }
}
