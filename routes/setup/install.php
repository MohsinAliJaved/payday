<?php

use App\Http\Controllers\Setup\EnvironmentController;

Route::get('environment', [EnvironmentController::class, 'index'])
    ->name('environment')
    ->middleware('app.not_install');

Route::post('set-environment', [EnvironmentController::class, 'update'])
    ->name('environment.update')
    ->middleware('app.not_install');