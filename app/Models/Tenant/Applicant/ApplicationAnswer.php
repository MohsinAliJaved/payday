<?php

namespace App\Models\Tenant\Applicant;

use App\Models\Core\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Tenant\Applicant\Traits\Rules\ApplicationAnswerRules;
use App\Models\Tenant\JobPost\Traits\Relationship\JobApplicationSettingRelationship;

class ApplicationAnswer extends BaseModel
{
    use HasFactory, ApplicationAnswerRules, JobApplicationSettingRelationship;

    protected $fillable = ['job_applicant_id', 'question'];

    protected $casts = [
        'job_applicant_id' => 'integer'
    ];
}
