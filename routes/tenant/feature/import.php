<?php

use App\Http\Controllers\Tenant\Import\UserImportController;
use Illuminate\Routing\Router;

Route::group(['prefix' => 'app', ], function (Router $router) {

    $router->post('import/employees',[UserImportController::class,'importEmployees'])->name('employees.import');

});