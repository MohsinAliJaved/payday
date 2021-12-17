<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\GlobalModules\CareerPageController;
use App\Http\Controllers\Tenant\GlobalModules\ApplicationFormController;

Route::group(['prefix' => 'app/global', 'as'=>'app.app_permission.global.', 'middleware' => ['permission']], function () {
    //Custom Aggregate Route Goes Here
    
    Route::get("application-form", [ApplicationFormController::class,'show'])->name('show-application-form');
    Route::patch("application-form", [ApplicationFormController::class,'update'])->name('update-application-form');
    
});
// Route::group(['prefix' => 'app/', 'middleware' => ['permission'], 'as'=>'app.app_permission.'], function () {
//     //Custom Aggregate Route Goes Here
//     Route::patch("career-page", [CareerPageController::class,'update'])->name('career-page.update');
// });

// Route::group(['middleware' => ['app.installed', 'add.tenant'], 'as' => 'tenant.'], function () {
//     Route::get("career-page", [CareerPageController::class,'showCareerPage'])->name('career-page.show');
// });

// Route::group(['as' => 'tenant.', 'middleware' => ['app.installed', 'add.tenant']], function () {
//     Route::get("career-page", [CareerPageController::class,'showCareerPage'])->name('career-page.show');
// });