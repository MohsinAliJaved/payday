<?php


namespace App\Http\Requests\Tenant\Recruitment;

use App\Http\Requests\BaseRequest;
use App\Models\Tenant\Recruitment\Todo;

class TodoRequest extends BaseRequest
{
    public function rules()
    {
        return $this->initRules( new Todo());
    }

}