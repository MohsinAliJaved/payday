<?php

namespace App\Models\Tenant\Applicant\Traits\Rules;

use Illuminate\Validation\Rule;

trait JobApplicantRules
{
    public function createdRules()
    {
        return [
            'applicant_id' => "required|exists:applicants,id",
            'job_post_id' => "required|exists:job_posts,id",
            'apply_form_setting' => "nullable|array"
        ];
    }

    public function updatedRules()
    {
        return [
            'current_stage_id' => "nullable|exists:job_stages,id",
            'review' => ['nullable', Rule::in(['0', '1', '2', '3', '4', '5'])],
            'status_id' => "nullable|exists:statuses,id"
        ];
    }
}