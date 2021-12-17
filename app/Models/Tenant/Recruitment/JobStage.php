<?php

namespace App\Models\Tenant\Recruitment;

use App\Models\Core\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Tenant\Recruitment\Traits\Rules\JobStageRules;
use App\Models\Tenant\Recruitment\Traits\Relationship\JobStageRelationship;

class JobStage extends BaseModel
{
    use HasFactory, JobStageRules, JobStageRelationship;

    protected $fillable = ['name', 'job_post_id'];

    protected $casts = [
        'job_post_id' => 'int',
    ];
}