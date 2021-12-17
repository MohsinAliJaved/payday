<?php

// use App\Http\Controllers\App\Recruitment\PersonalInfoController;

use App\Http\Controllers\App\Company\BusinessTypeController;
use App\Http\Controllers\App\Company\CareerPageController;
use App\Http\Controllers\App\Company\CompanyDetailController;
use App\Http\Controllers\Tenant\Company\CompanyLocationController;
use App\Http\Controllers\Tenant\Company\DepartmenteController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app', 'middleware' => ['permission'], 'as' => 'app_permission.'], function () {
    // Route::apiResource('business-type', BusinessTypeController::class);
    // Route::apiResource('career-page', CareerPageController::class);
    // Route::apiResource('company-details', CompanyDetailController::class);
    Route::apiResource('company-location', CompanyLocationController::class);
    Route::apiResource('department', DepartmenteController::class);
    
});