<?php

use App\Http\Controllers\Auth\RegisterController;



// Registration Routes...
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class,'register']);


