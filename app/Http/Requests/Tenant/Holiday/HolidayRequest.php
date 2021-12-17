<?php

namespace App\Http\Requests\Tenant\Holiday;


use App\Http\Requests\BaseRequest;
use App\Models\Tenant\Holiday\Holiday;

class HolidayRequest extends BaseRequest
{
    public function rules()
    {
        return $this->initRules( new Holiday());
    }
}
