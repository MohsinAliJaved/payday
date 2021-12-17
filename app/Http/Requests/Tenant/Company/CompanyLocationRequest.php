<?php


namespace App\Http\Requests\Tenant\Company;


use App\Http\Requests\BaseRequest;
use App\Models\Tenant\Company\CompanyLocation;

class CompanyLocationRequest extends BaseRequest
{
    public function rules()
    {
        return $this->initRules( new CompanyLocation());
    }
}