<?php

namespace App\Models\Tenant\JobPost;

use App\Models\Core\BaseModel;
use App\Models\Tenant\JobPost\Traits\Relationship\JobTypeRelationship;
use App\Models\Tenant\JobPost\Traits\Rules\JobTypeRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobType extends BaseModel
{
    use HasFactory, JobTypeRules, JobTypeRelationship;
    protected $fillable = ['name', 'brief'];

}
