<?php


namespace App\Models\Tenant\Applicant\Traits\Rules;


trait NoteRules
{
    public function createdRules()
    {
        return [
            'job_applicant_id' => 'required|exists:job_applicants,id',
            'description' => 'required|string',
        ];
    }

    public function updatedRules()
    {
        return [
            'description' => 'required|string',
        ];
    }

}