<?php

namespace App\Models\Tenant\Company;

use App\Models\Core\BaseModel;
use App\Models\Tenant\Company\Traits\Rules\DepartmentRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends BaseModel
{
    use HasFactory, DepartmentRules;

    protected $fillable = ['name'];
}