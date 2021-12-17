<?php

namespace App\Models\Tenant\Applicant\Traits\Relationship;

use App\Models\Tenant\Applicant\Applicant;
use App\Models\Tenant\Applicant\ApplicationAnswer;
use App\Models\Tenant\Applicant\Event;
use App\Models\Tenant\Applicant\Note;
use App\Models\Tenant\JobPost\JobPost;
use App\Models\Tenant\Recruitment\JobStage;
use App\Models\Core\Status;

trait JobApplicantRelationship
{
    public function appliedBy()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }

    public function currentStage()
    {
        return $this->belongsTo(JobStage::class, 'current_stage_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function answers()
    {
        return $this->hasMany(ApplicationAnswer::class, 'job_applicant_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'job_applicant_id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'job_applicant_id');
    }
}