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
use App\Http\Controllers\IndustrySupervisor;
use App\Http\Controllers\IndustrySupervisorStudentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\StudentController;
use App\Models\IndustrySupervisor as ModelsIndustrySupervisor;
use Symfony\Component\Mime\MessageConverter;

// NOTE: ALL ROUTES BEGINS WITH LOCALHOST/API/...

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('industrySupervisorReg', 'industryBossRegistration');
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
// // // 
// TEST CONTROLLER
// // // 
Route::controller(TestController::class)->group(function () {
    Route::get('pagination', 'usersPagination');
    Route::get('user-function',function(Request $req){
        return ModelsIndustrySupervisor::find(1)->industrySupervisorStudents->ss($req);
    });
});

// ###############                        #####
// ################ INDUSTRY SUPERVISOR ##############
// ###############                       #####
// ! add role authentication
Route::controller(IndustrySupervisor::class)->middleware(['auth:api'])->prefix('industrySupervisor')->middleware('role:industry_supervisor')->group(function () {
    Route::get('home', 'industrySupervisorHome');

    Route::post('students/evaluate', [IndustrySupervisorStudentController::class, 'industrySupervisorEvaluateStudent']);
    Route::post('students/check', [IndustrySupervisorStudentController::class, 'checkStudent']);
    Route::apiResource('students', IndustrySupervisorStudentController::class);

    // Route::post('add-student', 'industrySupervisor');
    // Route::get('get-student', 'industrySupervisorGetSpecificStudent');
    // Route::put('update-student', 'industrySupervisorGetSpecificStudent');
    // Route::get('remove-student', 'industrySupervisorDeleteStudent');
    // Route::get('get-students', 'industrySupervisorGetStudents');

    // Route::get('messages', 'industrySupervisorGetMessages');
    // Route::post('send-message', 'industrySupervisorSendMessage');

    Route::resource('messages', MessageController::class);
    //  get all /messages
    //  get specific /messages/{id}
    //  post new /messages
    //  update put /messages/{id}
    Route::put('profile', 'industrySupervisorProfile');

});
