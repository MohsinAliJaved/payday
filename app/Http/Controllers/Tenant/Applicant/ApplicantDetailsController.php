<?php

namespace App\Http\Controllers\App\Applicant;

use App\Http\Controllers\Controller;
use App\Models\App\Applicant\Applicant;

class ApplicantDetailsController extends Controller
{
    public function applicantDetails(Applicant $applicant)
    {
        return $applicant->setRelations(['jobApplicants', 'jobApplicants.jobPost'])
            ->load([
                'jobApplicants.jobPost',
                'jobApplicants.currentStage',
                'jobApplicants.status',
                'jobApplicants.appliedBy',
                'jobApplicants.answers',
                'jobApplicants.jobPost.location',
                'jobApplicants.jobPost.jobType',
                'jobApplicants.jobPost.department',
            ]);
    }
}
