<?php

namespace App\Http\Requests\App\Applicant;

use App\Http\Requests\App\AppRequest;
use App\Models\App\Applicant\Applicant;

class ApplyJobRequest extends AppRequest
{    
    public function rules()
    {
        return [
            "applicant_id" => "nullable|exists:applicants:id",

            "personal_info" => "nullable|array",
            "personal_info.first_name" => "required|string",
            "personal_info.last_name" => "required|string",
            "personal_info.email" => "required|email|unique:applicants",
            "personal_info.gender" => ['required', Rule::in(['male', 'female','other'])],
            "personal_info.date_of_birth" => "nullable|date|date_format:Y-m-d",
            
            "answers" => "required|array",
            "answers.*.question" => "required|string",
            "answers.*.answer" => "nullable|string",
            "answers.*.attachment" => "nullable|file",

        ];
    }
}
