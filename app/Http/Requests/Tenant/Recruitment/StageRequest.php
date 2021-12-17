<?php

namespace App\Http\Requests\Tenant\Recruitment;

use App\Http\Requests\BaseRequest;
use App\Models\Tenant\Recruitment\Stage;
use Illuminate\Foundation\Http\FormRequest;

class StageRequest extends BaseRequest
{
    public function rules()
    {
        return $this->initRules(new Stage());
    }
}
