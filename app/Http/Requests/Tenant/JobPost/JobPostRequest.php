<?php

namespace App\Http\Requests\Tenant\JobPost;

use App\Http\Requests\BaseRequest;
use App\Models\Tenant\JobPost\JobPost;

class JobPostRequest extends BaseRequest
{
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->initRules(new JobPost());
    }
}
