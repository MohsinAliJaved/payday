<?php


namespace App\Http\Requests\Tenant\Recruitment;

use App\Http\Requests\BaseRequest;
use App\Models\Tenant\Recruitment\JobStage;

class JobStageRequest extends BaseRequest
{
    public function rules()
    {
        return $this->initRules(new JobStage());
    }

}