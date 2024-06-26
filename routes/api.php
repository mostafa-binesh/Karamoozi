<?php

use App\Http\Controllers\AdminEducationalController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\StudentFinalReportController;
use App\Models\User;
use App\Models\Form2s;
use App\Models\Company;
use App\Models\Student;
use App\Models\WeeklyReport;
use App\Providers\GenerateRandomId;
use Illuminate\Http\Request;
use App\Models\StudentEvaluation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\IndustrySupervisor;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\AdminMasterController;
use App\Http\Controllers\AdminCompanyController;
use App\Http\Controllers\AdminNewsController;
use App\Http\Controllers\WeeklyReportController;
use App\Http\Controllers\AdminStudentsController;
use App\Http\Controllers\HomeController;
use App\Http\Resources\admin\StudentEvaluationResource;
use App\Http\Controllers\IndustrySupervisorStudentController;
use App\Http\Controllers\MailroomController;
use App\Http\Controllers\masters\MasterController;
use App\Http\Controllers\masters\StudentsController;
use App\Http\Controllers\MessagesResourceController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

// ! install 'better comments' plugin on vs code to see the code more clear
// ! NOTE: ALL ROUTES BEGINS WITH {siteAddress}/API/...
// ###############                        #####
// ! ################ AUTHENTICATION ##############
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
// ! ################### STUDENT ###################
// ###############                     #####

Route::controller(StudentController::class)->middleware(['auth:api', 'role:student'])->prefix('student')->group(function () {
    // ######### PRE REGISTRATION #########
    Route::get('info', 'studentInfo');
    Route::middleware(['verifiedStudent'])->group(function () {
        Route::get('pre-reg', 'get_pre_registration');
        Route::post('pre-reg', 'post_pre_registration');
        Route::put('pre-reg', 'put_pre_registration');
        // after student submitted pre reg, can see it's data with:
        Route::get('preRegInfo', 'studentPreRegInfo');
        // ######### PROFILE #########
        Route::get('profile', 'getStudentProfile');
        Route::put('profile/edit', 'editStudentProfile');
        // ######### COMPANY #########
        // submit or update custom student's company
        Route::post("company", 'submitCompany');
    });
    Route::middleware(['fullyVerifiedStudent'])->group(function () {
        // ######### EVALUATE COMPANY #########
        // get the options
        Route::get('evaluateCompany', 'evaluateCompany');
        Route::post('evaluateCompany', 'submitEvaluateCompany');
        Route::put('evaluateCompany', 'editEvaluateCompany');
        // get the student company evaluations
        Route::get('companyEvaluations', 'studentCompanyEvaluations');
        // ######### WEEKLY REPORT #########
        Route::put('weeklyReports/verifyWeek', [WeeklyReportController::class, 'verifyWeek']);
        Route::resource("weeklyReports", WeeklyReportController::class);
        Route::resource("finalReport", StudentFinalReportController::class)->except('destory');
        Route::delete("finalReport", [StudentFinalReportController::class, 'destroy']);
    });
    Route::get('internshipStatus', 'internshipStatus');
    Route::get('testFullyVerifiedMiddleware', function () {
        return null;
    })->middleware(['fullyVerifiedStudent']);

    Route::resource('final_report', StudentFinalReportController::class);
});

// ###############                       ######
// ! ################# HOME #####################
// ###############                       ######

Route::prefix('home')->group(function () {

    // news
    Route::resource('news', NewsController::class);

    // companies
    Route::get('companies', [CompaniesController::class, 'index']);

    //statics
    Route::get('statics', [HomeController::class, 'statics']);
});


// ###############                        #####
// ! ################ INDUSTRY SUPERVISOR ##############
// ###############                        #####

Route::controller(IndustrySupervisor::class)->middleware(['auth:api', 'role:industry_supervisor'])->prefix('industrySupervisor')->group(function () {
    Route::get('profile', 'industrySupervisorProfile');
    Route::put('profile', 'editIndustrySupervisorProfile');
    Route::put('profile/password', 'editIndustrySupervisorPassword');
    Route::get('home', 'industrySupervisorHome');
    // ########### STUDENT RELATED ########
    Route::middleware("verifiedIndustrySupervisor")->group(function () {
        Route::get('students/evaluate', [IndustrySupervisorStudentController::class, 'industrySupervisorEvaluateStudentGET']);
        Route::post('students/evaluate', [IndustrySupervisorStudentController::class, 'industrySupervisorEvaluateStudent']);
        // check if student exists
        Route::post('students/check', [IndustrySupervisorStudentController::class, 'checkStudent']);
        Route::post('students/check/submit', [IndustrySupervisorStudentController::class, 'submitCheckedStudent']);
        Route::Resource('students', IndustrySupervisorStudentController::class);
    });
    Route::get("form2/student", [HomeController::class, 'get_student_data']);
});

// ###############                        #####
// ! ##################### ADMIN  #####################
// ###############                       #####

Route::controller(AdminController::class)->middleware(['auth:api', 'role:admin'])->prefix('admin')->group(function () {
    Route::controller(AdminStudentsController::class)->prefix('students')->group(function () {
        Route::get('home', 'studentsHomePage');
        // list of students waiting for initial registration approval
        Route::get('initReg', 'initialRegistrationStudents');
        // list of students waiting for pre registration approval
        Route::get('preReg', 'preRegStudents');
        //get faculty
        Route::get('faculty', 'faculty');
        // init reg
        Route::put('{id}/initReg/verify', 'initRegVerifyStudent');
        Route::put('{id}/initReg/unverify', 'initRegUnVerifyStudent');
        Route::get('{id}/initReg/desc', 'initRegDesc');
        // pre reg
        Route::get('entrance', 'entrance_years');
        Route::put('{id}/preReg/verify', 'preRegVerifyStudent');
        Route::put('{id}/preReg/unverify', 'preRegUnVerifyStudent');
        Route::get('{id}/preReg/desc', 'preRegDesc');
        Route::get('{id}/preReg/rejectionDescription', 'rejectionDescription');
        // forms
        Route::get('forms', 'forms');
        Route::get('forms/{id}', 'studentForms');
        Route::get('forms/{id}/form2', 'form2');
        Route::put('forms/{id}/form2/verify', 'form2Verify');
        Route::put('forms/{StudentID}/form2/unverify', 'form2unVerify');
        // form3
        Route::get('forms/{id}/form3', 'form3');
        Route::put('forms/{id}/form3/verify', 'form3Verify');
        Route::put('forms/{id}/form3/unverify', 'form3UnVerify');
        // form4
        Route::get('forms/{id}/form4', 'form4');
        Route::put('forms/{id}/form4/verify', 'form4Verify');
        Route::put('forms/{id}/form4/unverify', 'form4UnVerify');
        // weekly report
        Route::get('weekly_reports', [WeeklyReportController::class, 'index']);
        Route::get('weekly_reports/verify/{id}', [WeeklyReportController::class, 'verifyWeek']);
        // final_reports
        Route::get('forms/{id}/final_report', [StudentFinalReportController::class, 'show']);
        // finish internship
        Route::get('forms/{id}/finish_internship', 'finishInternship');
        Route::put('forms/{id}/finish_internship/verify', 'verify_finishInternship');
        Route::put('forms/{id}/finish_internship/unverify', 'unverify_finishInternship');
    });
    Route::controller(AdminEducationalController::class)->prefix('educational')->group(function () {
        // ! faculties
        // TODO move all the faculties function to a separated controller
        Route::get('faculties', [AdminMasterController::class, 'faculties']);
        Route::get('faculties/{id}', 'singleFaculty');
        Route::post('faculties', 'addFaculty');
        Route::put('faculties/{id}', 'editFaculty');
        Route::delete('faculties/{id}', 'deleteFaculty');
        // ! terms
        // TODO move all the terms function to a separated controller
        Route::get('terms', 'allTerms');
        Route::get('terms/{id}', 'singleTerm');
        Route::get('terms/{id}/students', 'termStudents');
        Route::get('terms/{id}/masters', 'termMasters');
        Route::post('terms', 'addTerm');
        Route::put('terms/{id}', 'editTerm');
        Route::delete('terms/{id}', 'deleteTerm');
    });
    Route::get('faculties', [AdminMasterController::class, 'faculties']); // TODO move this function to adminController
    Route::resource('master', AdminMasterController::class);
    // Route::get('searchMaster',[AdminMasterController::class,'initialRegistrationMaster']);
    Route::resource('companies', AdminCompanyController::class);
    Route::post('companies/update/{id}', [AdminCompanyController::class, 'update_company']);

    //news
    Route::resource('news', AdminNewsController::class);
    Route::post('news/update/{id}', [AdminNewsController::class, 'updata_news']);
});
// ###############                        #####
// ! ##################### MASTER  #####################
// ###############                       #####
Route::controller(MasterController::class)->middleware(['auth:api', 'role:master'])->prefix('masters')->group(function () {
    // students
    Route::prefix('students')->controller(StudentsController::class)->group(function () {
        Route::get('count', 'count');
        Route::put('count', 'updateCount');
        // Route::get('/', 'verifiedStudents');
        Route::get('/verified', 'verifiedStudents');
        Route::get('/pending', 'pendingStudents');
        Route::get('/{id}', 'singleStudent');
        Route::put('/{id}/verify', 'verifyStudent');
        Route::put('/{id}/unverify', 'unverifyStudent');

        Route::get('forms/{id}/form3', [AdminStudentsController::class, 'form3']);
        Route::get('forms/{id}/final_report', [StudentFinalReportController::class, 'show']);
        Route::get('forms/weekly_reports', [WeeklyReportController::class, 'index']);
        Route::get('weekly_reports/verify/{id}', [WeeklyReportController::class, 'verifyWeek']);

        Route::post('/evaluate', [MasterController::class, 'store']);
        Route::get('/evaluate/{id}', [MasterController::class, 'show']);
        Route::put('/evaluate/{id}', [MasterController::class, 'update']);
    });
    // master routes
    //
    // ! سناریو اینطوری هست که وقتی که استاد کسی را رد میکنه نباید فرم پیش ثبت نامش برای ادمین ارسال بشه.
    // ! اگر تایید شد فرم برای ادمین ارسال میشه و  در صورت تایید ادمین به سراغ بقیه مراحل میره

});


//! ###############                        #####
//! ################ MAILROOM ROUTE  ##############
//! ###############                       #####
// middleware(['auth:api', 'role:mailroom'])->
Route::prefix('mailroom')->group(function () {
    Route::get('student', [MailroomController::class, 'get_student']);
    Route::get('letter', [MailroomController::class, 'show_letter']);
    Route::put('update/{id}', [MailroomController::class, 'update_letter']);
    Route::post('letter', [MailroomController::class, 'complete_letter']);
});

// // //
//! Message Routing
// // //
Route::middleware('auth:api')->group(function () {
    Route::resource('messages', MessagesResourceController::class);
});
// ###############                        #####
// ################ DEVELOPER ONLY ##############
// ###############                       #####
// ! DELETE ON PRODUCTION
Route::controller(DeveloperController::class)->prefix('devs')->group(function () {
    // Route::get("freshMigrate", function () {
    //     Artisan::call("migrate:fresh --seed");
    //     return "Migration completed successfully";
    // });
    // Route::get("migrate", function () {
    //     Artisan::call("migrate");
    //     return "Migration completed successfully";
    // });
    // Route::get("migrateSeed", function () {
    //     Artisan::call("migrate:fresh --seed");
    //     return "Migration completed successfully";
    // });
    // Route::get("role", function () {
    //     return Artisan::call("vendor:publish --provider='Spatie\Permission\PermissionServiceProvider'");
    // });
    // Route::get('', function () {
    //     Artisan::call('storage:link');
    // });

    // Route::get('pass' , function(){
    //     $user = User::where('username', '3981231052')->first();
    //     $user->password = Hash::make('4380413799');
    //     $user->save();
    // });

    // Route::get('develop',function(){
    //     $master = User::create([
    //         'rand_id' => GenerateRandomId::generateRandomId(),
    //         'first_name' => 'احمد',
    //         'last_name' => 'محمدی',
    //         'username' => '435622',
    //         'national_code' => '1234567910',
    //         'phone_number' => '09121112229',
    //         'email' => 'ahmad@gmail.com',
    //         'password' => Hash::make('1234567910')
    //     ]);
    //     $master->assignRole('mailroom');

    // });

});
