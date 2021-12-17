<?php

namespace App\Http\Requests\Tenant\JobPost;

use App\Http\Requests\BaseRequest;
use App\Models\Tenant\JobPost\JobType;

class JobTypeRequest extends BaseRequest
{
    public function rules()
    {
        return $this->initRules( new JobType());
    }
}
