<?php

namespace App\Models\Tenant\JobPost;

use App\Models\Core\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Tenant\JobPost\Traits\Rules\JobPostRules;
use App\Models\Tenant\JobPost\Traits\Relationship\JobPostRelationship;

class JobPost extends BaseModel
{
    use HasFactory, JobPostRules, JobPostRelationship;

    protected $fillable = [
        'company_location_id',
        'department_id',
        'job_type_id',
        'status_id',
        'stages',
        'posted_by',
        'name',
        'salary',
        'description',
        'responsibilities',
        'slug',
        'last_submission_date',
        'job_post_settings',
        'apply_form_settings',
    ];

    protected $casts = [
        'company_location_id' => 'integer',
        'department_id' => 'integer',
        'job_type_id' => 'integer',
        'status_id' => 'integer',
        'posted_by' => 'integer',
        'last_submission_date' => 'datetime:Y-m-d',
        'job_post_settings' => 'object',
        'apply_form_settings' => 'object',
    ];
}