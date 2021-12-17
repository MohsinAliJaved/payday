<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\Applicant\NoteController;
use App\Http\Controllers\Tenant\Settings\EventController;
use App\Http\Controllers\App\Applicant\AttendeeController;
use App\Http\Controllers\Tenant\Settings\EventTypeController;
use App\Http\Controllers\Tenant\Applicant\ApplicantController;
use App\Http\Controllers\Tenant\Applicant\JobApplicantController;
use App\Http\Controllers\App\Applicant\ApplicantDetailsController;

Route::group(['prefix' => 'app', 'as' => 'app_permission.'], function (Router $route) {
    Route::post('applicant/check-email', [ApplicantController::class, 'checkEmail'])
        ->name('applicant.check_email');;

    Route::get('job-applicant/{job_applicant_id}/get-timeline', [JobApplicantController::class, 'getTimeline'])
        ->name('job_applicant_timeline.list');

    $route->patch('job-applicant/{job_applicant_id}/change-stage', [JobApplicantController::class, 'changeStage'])
        ->name('job_applicant.change_stage');

    $route->patch('job-applicant/{job_applicant_id}/change-review', [JobApplicantController::class, 'changeReview'])
        ->name('job_applicant.change_review');
    $route->patch('job-applicant/{job_applicant}/disqualify', [JobApplicantController::class, 'disqualify'])
        ->name('job_applicant.disqualify');
    $route->patch('job-applicant/{job_applicant}/update-disqualification-note', [JobApplicantController::class, 'updateDisqualificationNote'])
        ->name('job_applicant.update_disqualification_note');

    $route->patch('job-applicant/{job_applicant}/send-email', [JobApplicantController::class, 'sendEmailToApplicant'])
        ->name('job_applicant.send_email');
});

Route::group(['prefix' => 'app', 'as' => 'app_permission.', 'middleware' => ['permission']], function () {
    Route::apiResource('applicant', ApplicantController::class);
    Route::apiResource('job-applicant', JobApplicantController::class);
    Route::apiResource('event', EventController::class);
    Route::apiResource('event-type', EventTypeController::class);
    // Route::apiResource('note', NoteController::class);
    // Route::apiResource('attendee', AttendeeController::class)->only(['store', 'destroy']);
});
