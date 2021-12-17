<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\Candidate\CandidateController;
use App\Http\Controllers\Tenant\JobPost\AggregateJobController;


Route::group(['prefix' => 'public'], function () {
    Route::get('career', [CandidateController::class, 'showCareerPage'])->name('candidate.show_career_page');
    Route::get('job-post/{job_slug}/display', [AggregateJobController::class, 'showJobPost'])->name('public.jobPost.show_pob_post');
    Route::get('job-post/{job_slug}/apply', [AggregateJobController::class, 'applyJobPost'])->name('public.jobPost.apply_job_post');
    Route::post('job-post/{job_slug}/apply', [CandidateController::class, 'applyJobPost'])->name('public.jobPost.apply_job_post.submit');
    Route::post('candidate/check-email', [CandidateController::class, 'checkEmail'])->name('candidate.check_email');
    Route::get('job-applicant/{job_applicant_slug}/show-job-application', [CandidateController::class, 'showJobApplicantDetails'])
        ->name('candidate.show_job_application');
});