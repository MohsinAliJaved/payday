<?php


namespace App\Models\Tenant\Recruitment\Traits\Rules;


trait HiringTeamRules
{
    public function createdRules()
    {
        return [
            'job_post_id' => 'required|exists:job_posts,id',
            'recruiters' => 'required|array',
            'recruiters.*.recruiter_id' => 'required|exists:users,id',
        ];
    }

    public function updatedRules()
    {
        return $this->createdRules();
    }
}