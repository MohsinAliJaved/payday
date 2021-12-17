<?php


namespace App\Http\Requests\Tenant\Company;


use App\Http\Requests\App\AppRequest;
use App\Http\Requests\BaseRequest;
use App\Models\Tenant\Company\Department;


class DepartmentRequest extends BaseRequest
{
    public function rules()
    {
        return $this->initRules( new Department());
    }

}