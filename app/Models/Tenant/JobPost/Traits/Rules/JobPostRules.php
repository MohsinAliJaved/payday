<?php

namespace App\Models\Tenant\JobPost\Traits\Rules;

trait JobPostRules
{
    public function createdRules()
    {
        return [
            'company_location_id' => 'required|exists:company_locations,id',
            'department_id' => 'nullable|exists:departments,id',
            'job_type_id' => 'required|exists:job_types,id',
            'name' => 'required',
            'stages' => 'required|string',
            'salary' => 'nullable|string',
            'description' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'job_post_settings' => 'required|array',
            'apply_form_settings' => 'required|array',
            'last_submission_date' => 'required|date|date_format:Y-m-d',
        ];
    }

    public function updatedRules()
    {
        return [
            'company_location_id' => 'nullable|exists:company_locations,id',
            'department_id' => 'nullable|exists:departments,id',
            'job_type_id' => 'nullable|exists:job_types,id',
            'status_id' => 'nullable|exists:statuses,id',
            'name' => 'nullable|string',
            'stages' => 'nullable|string',
            'salary' => 'nullable|string',
            'description' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'job_post_settings' => 'nullable|array',
            'apply_form_settings' => 'nullable|array',
            'last_submission_date' => 'nullable|date|date_format:Y-m-d',
        ];
    }

    public function failedMessage()
    {
        return [
            'company_location_id' => ' Company Location must be inside Location ',
            'department_id' => 'Address is not valid',
            'job_type_id' => 'Job Type not valid',
            'status_id' => 'Status is not a valid status',
            'name' => 'Job Title is not valid',
            'stages' => 'Stages is not valid',
            'salary' => 'Salary is not valid',
            'description' => 'Description is not valid',
            'responsibilities' => 'Responsibilities is not valid',
            'job_post_settings' => 'Job Template Must be json object',
            'apply_form_settings' => 'Apply form setting Must be json object',
            'last_submission_date' => 'must be date formet: YYYY-MM-DD',
        ];
    }
}
