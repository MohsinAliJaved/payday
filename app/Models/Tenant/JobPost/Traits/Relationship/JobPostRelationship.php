<?php

namespace App\Models\Tenant\JobPost\Traits\Relationship;


use App\Models\Tenant\Applicant\JobApplicant;
use App\Models\Tenant\Company\CompanyLocation;
use App\Models\Tenant\Company\Department;
use App\Models\Tenant\JobPost\JobType;
use App\Models\Tenant\Recruitment\HiringTeam;
use App\Models\Tenant\Recruitment\JobStage;
use App\Models\Core\Auth\User;
use App\Models\Core\File\File;
use App\Models\Core\Status;

trait JobPostRelationship
{
    public function location()
    {
        return $this->belongsTo(CompanyLocation::class, 'company_location_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function jobType()
    {
        return $this->belongsTo(JobType::class, 'job_type_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function jobApplicants()
    {
        return $this->hasMany(JobApplicant::class, 'job_post_id');
    }

    public function totalApplicants()
    {
        return $this->jobApplicants()
            ->selectRaw('job_post_id, count(*) as count')
            ->groupBy('job_post_id');
    }

    public function jobStages()
    {
        return $this->hasMany(JobStage::class, 'job_post_id');
    }

    public function recruiters()
    {
        return $this->hasMany(HiringTeam::class, 'job_post_id');
    }

    public function jobPostThumbnail()
    {
        return $this->morphOne(File::class, 'fileable')
            ->where('type','job_post_thumbnail_image');
    }
}
