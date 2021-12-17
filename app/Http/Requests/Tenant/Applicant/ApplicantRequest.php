<?php

namespace App\Http\Requests\Tenant\Applicant;

use App\Http\Requests\BaseRequest;
use App\Models\Tenant\Applicant\Applicant;

class ApplicantRequest extends BaseRequest
{
    public function rules()
    {
        return $this->initRules( new Applicant());
    }
}
