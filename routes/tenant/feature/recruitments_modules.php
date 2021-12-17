<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\Applicant\NoteController;
use App\Http\Controllers\Tenant\Recruitment\StageController;
use App\Http\Controllers\Tenant\Recruitment\JobStageController;
use App\Http\Controllers\Tenant\Recruitment\HiringTeamController;

Route::group(['prefix' => 'app', 'as' => 'app_permission.'], function (Router $route) {
    $route->get('get-teams-by-job/{job_post_id}', [HiringTeamController::class, 'getTeamsByJob'])
        ->name('by_job_post.teams.get');
//     //Custom Aggregate Route Goes Here
    $route->patch('job-stage/{job_stage_id}/move_applicant', [JobStageController::class, 'moveApplicants'])
        ->name('job_stage.move_applicant');
});

Route::group(['prefix' => 'app', 'as' => 'app_permission.', 'middleware' => ['permission']], function () {
    Route::apiResource('stage', StageController::class);
    Route::apiResource('job-stage', JobStageController::class)->except('store', 'show', 'index');
    // Route::apiResource('note', NoteController::class);
    Route::apiResource('hiring-team', HiringTeamController::class)->except(['update']);
});
