<?php
// use Melipayamak\MelipayamakApi;
use App\Models\User;
use App\Models\student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Password_reset;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\StudentController;
use App\Mail\send_code_reset_password;

// NOTE: ALL ROUTES BEGINS WITH LOCALHOST/API/...

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('industry-boss-reg', 'industryBossRegistration');
    Route::post('login', 'login')->name('login');
    Route::get('user', 'user');
    
    Route::post('forget-password', 'send_reset_password');
    Route::get('check-password', 'check_password');
    Route::get('reset-password', 'reset_password')->name('password.reset');
});
Route::controller(StudentController::class)->group(function () {
    Route::get('pre-reg', 'get_pre_registration');
    Route::post('pre-reg', 'post_pre_registration');
    // Route::put('pre-reg', 'post_pre_registration');
});
