<?php

namespace App\Models\Tenant\Company;

use App\Models\App\AppModel;
use App\Models\Core\BaseModel;
use App\Models\Tenant\Company\Traits\Rules\CompanyLocationRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyLocation extends BaseModel
{
    use HasFactory, CompanyLocationRules;

    protected $fillable = ['address'];

}