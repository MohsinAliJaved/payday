<?php

namespace App\Models\Tenant\Recruitment;

use App\Models\Core\BaseModel;
use App\Models\Tenant\Recruitment\Traits\Rules\StageRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stage extends BaseModel
{
    use HasFactory, StageRules;

    protected $fillable = ['name'];

    protected $casts = [
    ];
}
