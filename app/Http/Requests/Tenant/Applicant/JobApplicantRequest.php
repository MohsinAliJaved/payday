<?php

namespace App\Http\Requests\Tenant\Applicant;

use App\Http\Requests\BaseRequest;
use App\Models\Tenant\Applicant\JobApplicant;

class JobApplicantRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->initRules( new JobApplicant());
    }
}
