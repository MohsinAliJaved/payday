<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\NavigationController;
use App\Http\Controllers\App\Roles\RoleController;
use App\Http\Controllers\App\Users\UserController;
use App\Http\Controllers\App\Users\UserRoleController;
use App\Http\Controllers\Core\Users\AppUserController;
use App\Http\Controllers\App\Users\UserSocialLinkController;
use App\Http\Controllers\App\Auth\AuthenticateUserController;
use App\Http\Controllers\App\Notification\NotificationController;
use App\Http\Controllers\App\Settings\ReCaptchaSettingController;

// Route::get('/user/registration', [AuthenticateUserController::class, 'registerView']);
// Route::get('/reset/password', [AuthenticateUserController::class, 'resetPasswordView']);
// Route::view('/my-profile', 'user.profile');
// Route::view('/users-and-roles', 'user-roles.user-roles')->name('user-role.list');

// // User

// // Update user name
// Route::group(['as' => 'app.', 'middleware' => ['permission']], function (Router $route) {
//     Route::post('/update-user-name/{id}', [UserController::class, 'updateUserName'])->name('name.update_user');
//     Route::resource('user-list', UserController::class);
// });

// // Role
// Route::get('users/{role}', [RoleController::class, 'getUsersByRole']);

// // User
// Route::get('all-users', [UserController::class, 'getUsers']);

// Users
Route::get('get/users', [AppUserController::class, 'index']);

// // Role_user
// Route::post('attach-user/{role}', [UserRoleController::class, 'store']);
// // Route::delete('attach-user/{id}',[UserRoleController::class,'destroy']);

// // Sample Pages Routes
// Route::view('/blank-page', 'sample-pages.sample');

// // Roles
// Route::get('all-roles', [RoleController::class, 'getAllRoles']);

// // ALl Notifications page
// Route::get('/all-notifications', [NotificationController::class, 'view']);

// Route::get('login-user', [AuthenticateUserController::class, 'show'])
//     ->name('user.login-user');

// Route::post('change-social-link', [UserSocialLinkController::class, 'update'])
//     ->name('user.change-social-link');

// // Get captcha
// Route::get('/get-re-captcha-setting', [ReCaptchaSettingController::class, 'getReCaptchaSettings'])
//     ->name('re-captcha-settings.get');


// // Blade files
// Route::view('/candidates', 'candidates.index')->name('candidates');
// Route::view('/all-events', 'all-events.index')->name('all_events');
