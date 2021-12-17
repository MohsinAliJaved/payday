<?php

use App\Http\Controllers\Tenant\JobPost\JobPostController;
use App\Http\Controllers\Tenant\JobPost\JobTypeController;
use App\Http\Controllers\Tenant\Settings\EventTypeController;
use App\Http\Controllers\Tenant\Employee\DepartmentController;
use App\Http\Controllers\Tenant\Applicant\JobApplicantController;
use App\Http\Controllers\Tenant\Recruitment\HiringTeamController;
use App\Http\Controllers\Tenant\Company\CompanyLocationController;

Route::group(['prefix' => 'app', 'as' => 'app_permission.', 'middleware' => ['permission']], function () {
    Route::get('selectable/departments',[ DepartmentController::class, 'getAllDepartments']);
    Route::get('selectable/company-locations',[ CompanyLocationController::class, 'getAllLocations']);
    Route::get('selectable/event-types', [EventTypeController::class, 'getEventTypes']);
    
    Route::get('selectable/stages', [JobPostController::class, 'selectableStages']);
    Route::get('selectable/job-posts', [JobPostController::class, 'selectableOpenJobPost']);
    Route::get('selectable/job-types', [JobTypeController::class, 'getAllJobTypes']);
    Route::get('selectable/{job_post}/hiring-team', [HiringTeamController::class, 'selectableApplicantAttendees']);
    Route::get('job-applicant/selectable/status', [JobApplicantController::class, 'selectableStatus']);

});