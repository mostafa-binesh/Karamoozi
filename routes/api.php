<?php
// use Melipayamak\MelipayamakApi;
use App\Models\User;
use App\Models\student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
// NOTE: ALL ROUTES BEGINS WITH LOCALHOST/API/...

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login')->name('login');
    Route::get('user', 'user');
});
Route::controller(StudentController::class)->group(function () {
    Route::get('pre-reg', 'get_pre_registration');
    Route::post('pre-reg', 'post_pre_registration');
});
Route::get('/relationships', function () {
    // find and return student only 
    // $user = student::find(1);
    // return $user->user;

    // return only student of user 
    // $user = User::where('first_name','Mostafa')->first();
    // return $user->student;

    // return user with student attrs. 
    // $user = User::where('first_name','Mostafa')->with('student')->get();
    // return $user;

    // return student with assigned user
    // $user = Student::with('user')->get();
    // return $user;
});
Route::get('roles', function () {
    // get roles of a user
    // $user = User::find(1);
    // return $user->getRoleNames();

    // get all users with master role
    // User::role('master')->get();
});
Route::get('test', function () {
    return auth()->user()->load(['student']); // -> 2 queries 
    return User::with('student')->find(Auth::id()); // -> 3 queries
})->middleware('auth:api');
