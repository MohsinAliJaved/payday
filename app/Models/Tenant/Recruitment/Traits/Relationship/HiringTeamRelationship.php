<?php


namespace App\Models\Tenant\Recruitment\Traits\Relationship;

use App\Models\Core\Auth\User;
use App\Models\App\Applicant\Attendee;
use App\Models\Tenant\JobPost\JobPost;

trait HiringTeamRelationship
{
    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    public function attendees()
    {
        return $this->hasMany(Attendee::class, 'hiring_team_id');
    }
}