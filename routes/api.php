<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminMasterController;
use App\Http\Controllers\AdminStudentsController;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\IndustrySupervisor;
use App\Http\Controllers\IndustrySupervisorStudentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\WeeklyReportController;
use App\Http\Resources\admin\StudentEvaluationResource;
use App\Models\Company;
use App\Models\Form2s;
use App\Models\IndustrySupervisor as ModelsIndustrySupervisor;
use App\Models\StudentEvaluation;
use App\Models\WeeklyReport;
use Illuminate\Support\Facades\Artisan;

// NOTE: ALL ROUTES BEGINS WITH {siteAddress}/API/...

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
        Route::resource("weeklyReports", WeeklyReportController::class);
    });
    Route::get('internshipStatus', 'internshipStatus');
    Route::get('testFullyVerifiedMiddleware', function () {
        return null;
    })->middleware(['fullyVerifiedStudent']);
});

// ###############                        #####
// ! ################ INDUSTRY SUPERVISOR ##############
// ###############                       #####
Route::controller(IndustrySupervisor::class)->middleware(['auth:api', 'role:industry_supervisor'])->prefix('industrySupervisor')->group(function () {
    Route::put('profile', 'industrySupervisorProfile');
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
    Route::resource('messages', MessageController::class);
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
        // init reg
        Route::put('{id}/initReg/verify', 'initRegVerifyStudent');
        Route::put('{id}/initReg/unverify', 'initRegUnVerifyStudent');
        Route::get('{id}/initReg/desc', 'initRegDesc');
        // pre reg
        Route::put('{id}/preReg/verify', 'preRegVerifyStudent');
        Route::put('{id}/preReg/unverify', 'preRegUnVerifyStudent');
        Route::get('{id}/preReg/desc', 'preRegDesc');
        // forms
        Route::get('forms', 'forms');
        Route::get('forms/{id}', 'studentForms');
        Route::get('forms/{id}/form2', 'form2');
        Route::put('forms/{id}/form2/verify', 'form2Verify');
        Route::put('forms/{id}/form2/unverify', 'form2unVerify');
        // form3
        Route::get('forms/{id}/form3', 'form3');
        Route::get('forms/{id}/form3/verify', 'form3Verify');
        Route::get('forms/{id}/form3/unverify', 'form3UnVerify');
        // form4
        Route::get('forms/{id}/form4', 'form4');
        Route::put('forms/{id}/form4/verify', 'form4Verify');
        Route::put('forms/{id}/form4/unverify', 'form4UnVerify');
    });
    Route::resource('master', AdminMasterController::class);
});



// ###############                        #####
// ################ DEVELOPER ONLY ##############
// ###############                       #####
// ! DELETE ON PRODUCTION
Route::controller(DeveloperController::class)->prefix('devs')->group(function () {
    Route::get("freshMigrate", function () {
        // Artisan::call("migrate:reset");
        Artisan::call("migrate:fresh --seed");
    });
    Route::get("migrate", function () {
        // Artisan::call("migrate:reset");
        Artisan::call("migrate");
    });
    Route::get("migrateSeed", function () {
        // Artisan::call("migrate:reset");
        Artisan::call("migrate:fresh --seed");
    });
});
// // //
// TEST CONTROLLER
// // //
Route::prefix('test')->controller(TestController::class)->group(function () {
    Route::get('send/{id}','sender');
    Route::get('receive/{id}','receive');
    Route::post('create_chat','create_chat');
    Route::post('create_message','create_message');


    Route::get('pagination', 'usersPagination');
    Route::get('user-function', function (Request $req) {
        return ModelsIndustrySupervisor::find(1)->industrySupervisorStudents->ss($req);
    });
    Route::get('verta', 'verta');
    Route::get('studentTest', function () {
        $student = Student::findorfail(1);
        return $student->weeklyReport->reports;
        // return $student->calculateAllWorkingDaysDate();
        // return $student->howManyDaysMustWork($student->schedule());
    });
    Route::get('howManyDaysMustWork', function (Request $req) {
        // $student = Student::findorfail();
        $student = Student::where('student_number', $req->student_number)->firstorfail();

        return $student->howManyDaysMustWork($student->schedule());
        // return $student->calculateAllWorkingDaysDate();
        // return $student->howManyDaysMustWork($student->schedule());
    });
    Route::get('allStudents', function (Request $req) {
        return Student::all();
    });
    Route::get('student/{id}/studentEvaluation', function ($id) {
        $student = Student::findorfail($id)->with('studentEvaluations')->first();
        return StudentEvaluationResource::collection($student->studentEvaluations);
    });
    Route::get('studentEvaluation/{id}', function ($id) {
        // $student = Student::findorfail($id)->with('studentEvaluations')->first();
        $studentEvalution = StudentEvaluation::findorfail($id)->getRelations();
        return StudentEvaluationResource::collection($studentEvalution);
    });
    Route::get('allUsers', function (Request $req) {
        return User::all();
    });
    Route::get('allForms', function (Request $req) {
        return Form2s::all();
    });
    Route::get('allUsersWithRole', function (Request $req) {
        return User::find(1)->loadRoleInfo();
    });
    Route::get('null', function (Request $req) {
        return null;
    });
    Route::get('allCompanies', function (Request $req) {
        return Company::all();
    });
    Route::get('weeklyReports', function (Request $req) {
        return WeeklyReport::all();
    });
    Route::delete('deleteWeeklyReports', function (Request $req) {
        return WeeklyReport::where('student_id', $req->student_id)->delete();
    });
    Route::get('dupicateQuery', function (Request $req) {
        // ! it seems two where clouses with same names doesn't work as expected
        $students = Student::where('verified', 0)->where('verified', 1)->get();
        // ! return query would be something like this where verified = 0 and where verified = 1
        // return $x;
        return $students;
    });
    Route::get('num2word', 'num2word');
});
