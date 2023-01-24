<?php
// use Melipayamak\MelipayamakApi;
use App\Models\User;
use App\Models\student;
use Illuminate\Http\Request;
use App\Models\Password_reset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Mail\send_code_reset_password;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\IndustrySupervisor;
use App\Http\Controllers\IndustrySupervisorStudentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\StudentController;
use App\Models\IndustrySupervisor as ModelsIndustrySupervisor;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Mime\MessageConverter;

// NOTE: ALL ROUTES BEGINS WITH LOCALHOST/API/...

// ###############                        #####
// ################ AUTHENTICATION ##############
// ###############                       #####

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('industrySupervisorReg', 'industryBossRegistration');
    Route::post('login', 'login')->name('login');
    Route::get('user', 'user');

    Route::post('forget-password', 'send_reset_password');
    Route::get('check-password', 'check_password');
    Route::get('reset-password', 'reset_password')->name('password.reset');
});

// ###############                      #####
// ################### STUDENT ###################
// ###############                     #####

Route::controller(StudentController::class)->group(function () {
    Route::get('student/pre-reg', 'get_pre_registration');
    Route::post('student/pre-reg', 'post_pre_registration');
    Route::post("student/company", 'submitCompany');
    // Route::put('pre-reg', 'post_pre_registration');
});

// ###############                        #####
// ################ INDUSTRY SUPERVISOR ##############
// ###############                       #####
Route::controller(IndustrySupervisor::class)->middleware(['auth:api', 'role:industry_supervisor'])->prefix('industrySupervisor')->group(function () {
    Route::put('profile', 'industrySupervisorProfile');
    Route::get('home', 'industrySupervisorHome');
    // ########### STUDENT RELATED ########
    Route::middleware("verifiedIndustrySupervisor")->group(function () {
        Route::get('students/evaluate', [IndustrySupervisorStudentController::class, 'industrySupervisorEvaluateStudentGET']);
        Route::post('students/evaluate', [IndustrySupervisorStudentController::class, 'industrySupervisorEvaluateStudent']);
        Route::post('students/check', [IndustrySupervisorStudentController::class, 'checkStudent']);
        Route::post('students/check/submit', [IndustrySupervisorStudentController::class, 'submitCheckedStudent']);
        Route::Resource('students', IndustrySupervisorStudentController::class);
    });
    Route::resource('messages', MessageController::class);
});
// ###############                        #####
// ################ DEVELOPER ONLY ##############
// ###############                       #####
// ! DELETE ON PRODUCTION
Route::controller(DeveloperController::class)->prefix('devs')->group(function () {
    Route::get("migrate", function () {
        // Artisan::call("migrate:reset");
        Artisan::call("migrate:fresh --seed");
    });
});
// // // 
// TEST CONTROLLER
// // // 
Route::controller(TestController::class)->group(function () {
    Route::get('pagination', 'usersPagination');
    Route::get('user-function', function (Request $req) {
        return ModelsIndustrySupervisor::find(1)->industrySupervisorStudents->ss($req);
    });
});
