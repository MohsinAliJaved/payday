<?php

namespace App\Models\Tenant\Applicant;

use App\Models\Core\BaseModel;
use App\Models\Tenant\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Tenant\Applicant\Traits\Rules\JobApplicantRules;
use App\Models\Tenant\Applicant\Traits\Relationship\JobApplicantRelationship;

class JobApplicant extends BaseModel
{
    use HasFactory, JobApplicantRules, JobApplicantRelationship;

    protected $fillable = ['applicant_id', 'review', 'disqualification_reason',
        'job_post_id', 'slug',
        'current_stage_id', 'status_id', 'apply_form_setting'];

    protected $casts = [
        'applicant_id' => 'integer',
        'job_post_id' => 'integer',
        'current_stage_id' => 'integer',
        'status_id' => 'integer',
        'review' => 'string',
        'apply_form_setting' => 'object',
    ];
}
