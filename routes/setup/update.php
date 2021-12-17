<?php


use App\Http\Controllers\Setup\AppUpdateController;

Route::get('updates', [AppUpdateController::class, 'index'])
    ->middleware('can:check_for_updates')
    ->name('updates.index');

Route::post('updates/install/{version}', [AppUpdateController::class, 'update'])
    ->middleware('can:update_app')
    ->name('updates.install');
