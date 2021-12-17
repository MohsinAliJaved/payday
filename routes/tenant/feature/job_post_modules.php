<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\JobPost\JobPostController;
use App\Http\Controllers\Tenant\JobPost\JobTypeController;
use App\Http\Controllers\Tenant\JobPost\SocialSharableController;
use App\Http\Controllers\Tenant\JobPost\AggregateJobController;

Route::group(['prefix' => 'app', 'as' => 'app.app_permission.', 'middleware' => ['permission']], function (Router $route) {
    $route->get('dashboard/job-summery', [AggregateJobController::class, 'summery'])
        ->name('job_summery.view');
    $route->get('dashboard/{jobPostId}/overview', [AggregateJobController::class, 'overview'])
        ->name('job_overview.view');
    $route->get('dashboard/{jobPostId}/applicants', [AggregateJobController::class, 'applicants'])
        ->name('job_overview.applicants.view');
    $route->get('job-post/{job}/generate-sharable-link', [AggregateJobController::class, 'sharableLink'])
        ->name('job_post.sharable_link');    
    $route->patch('job-post/{job}/update-job-post', [AggregateJobController::class, 'updateJobPost'])
        ->name('job_post.update_job_post_setting');
    $route->patch('job-post/{job}/publish-job-post', [AggregateJobController::class, 'publishJobPost'])
        ->name('job_post.publish');
    $route->patch('job-post/{job}/edit-apply-form-setting', [AggregateJobController::class, 'editApplyForm'])
        ->name('job_post.update_apply_form_setting');
    $route->patch('job-post/{job_id}/change-status', [JobPostController::class, 'changeStatus'])
        ->name('status.job_post.change');
    
    // //-----------------Stage Operation-------------------------------------
    Route::patch('job-post/{job_post_id}/add-stages', [JobPostController::class, 'addStages'])
        ->name('to_job_post.add_stage');
    Route::patch('job-post/{job_post_id}/reorder-stages', [JobPostController::class, 'reorderStages'])
        ->name('of_job_post.update_stage');

    //--------------------Custom view files returned--------------------------
    Route::get('job-post/{job_id}/settings', [AggregateJobController::class, 'viewSetting'])
        ->name('setting.job_post.view');
    Route::get('job-post/{job}/edit-job-post', [AggregateJobController::class, 'editJobPost'])
        ->name('view.setting.job_post.allow');
    Route::get('job-post/{job_id}/overview', [AggregateJobController::class, 'viewOverview'])
        ->name('view.job_overview.show');

    // //------------------Social Sharable----------------------------------------------
    Route::get('sharable_thumbnail/{id}', [SocialSharableController::class, 'show'])
        ->name('sharable_thumbnail.view');
    Route::patch('sharable_thumbnail/{job_post}/update', [SocialSharableController::class, 'update'])
        ->name('sharable_thumbnail.edit');
    //--------------------------------------------------------------------------------------


});

Route::group(['prefix' => 'app', 'as' => 'app_permission.', 'middleware' => ['permission']], function () {
    Route::apiResource('job-type', JobTypeController::class);
    Route::apiResource('job-post', JobPostController::class);
});

