<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Core\LanguageController;
use App\Http\Controllers\Tenant\JobPost\JobController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use App\Http\Controllers\Tenant\Recruitment\TodoController;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Tenant\Settings\SettingsApiController;
use App\Http\Controllers\Tenant\GlobalModules\CareerPageController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {


    include  __DIR__ . '/public_routes_module.php';

    Route::redirect('/', url('admin/users/login'));
    Route::get('/get-basic-setting-data', [SettingsApiController::class, 'getBasicSettingData']);

    Route::get("doc/core/components", [\App\Http\Controllers\DocumentationController::class, 'index']);
    Route::get("doc/core/components/{component_name}", [\App\Http\Controllers\DocumentationController::class, 'show']);


    // Switch between the included languages
    Route::get('lang/{lang}', [LanguageController::class, 'swap'])->name('language.change');
    Route::get('languages', [LanguageController::class, 'index'])->name('languages.index');

    /*
 * All login related route will be go there
 * Only guest user can access this route
 */

    Route::group(['middleware' => ['app.installed', 'guest'], 'prefix' => 'users'], function () {
        include_route_files(__DIR__ . '/user/');
    });

    Route::group(['middleware' => ['app.installed', 'guest'], 'prefix' => 'admin/users'], function () {
        include_route_files(__DIR__ . '/login/');
    });

    /**
     * This route is only for brand redirection
     * And for some additional route
     */
    Route::group(['prefix' => 'admin', 'middleware' => ['app.installed', 'auth', 'authorize']], function () {
        include  __DIR__ . '/additional.php';
    });

    Route::group(['middleware' => ['auth', 'authorize'], 'as' => 'support.'], function () {
        include_route_files(__DIR__ . '/support/');
    });

    Route::group(['as' => 'tenant.', 'middleware' => ['app.installed', 'add.tenant']], function () {
        include __DIR__ . '/tenant/index.php';
        Route::view('/candidates', 'candidates.index')->name('candidates');
        Route::get('jobs', [JobController::class, 'index'])->name('jobs');
        Route::patch("career-page", [CareerPageController::class, 'update'])->name('career-page.update');
        Route::get("career-page", [CareerPageController::class, 'showCareerPage'])->name('career-page.show');
    });

    /**
     * Backend Routes
     * Namespaces indicate folder structure
     * All your route in sub file must have a name with not more than 2 index
     * Example: brand.index or dashboard
     * See @var PermissionMiddleware for more information
     */
    Route::group(['prefix' => 'admin', 'middleware' => 'admin', 'as' => 'core.'], function () {

        /*
     * (good if you want to allow more than one group in the core,
     * then limit the core features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     * These routes can not be hit if the password is expired
     */
        include_route_files(__DIR__ . '/core/');
    });

    Route::group(['prefix' => 'app', 'middleware' => 'auth', 'as' => 'app.'], function () {
        // Todo
        Route::put('todo/clear-all', [TodoController::class, 'clearAll'])
            ->name('todo.clear_all');
        Route::patch('todo/{todo_id}/change-status', [TodoController::class, 'changeStatus'])
            ->name('todo.change_status');
        Route::apiResource('todo', TodoController::class)->except(['update', 'show']);
    });
});
