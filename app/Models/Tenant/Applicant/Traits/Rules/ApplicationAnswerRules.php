<?php

namespace App\Models\Tenant\Applicant\Traits\Rules;


trait ApplicationAnswerRules
{
    public function createdRules()
    {
        return [
            'job_applicant_id' => "required|exists:job_applicants,id",
            'application_answer_record' => "required|array"

        ];
    }

    public function updatedRules()
    {
        return [

            'job_applicant_id' => "required|exists:job_applicants,id",
            'application_answer_record' => "required|array"
        ];
    }
}
