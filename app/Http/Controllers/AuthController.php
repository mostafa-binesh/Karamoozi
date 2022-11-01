<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Melipayamak\MelipayamakApi;
use App\Models\Phone_Registration;
use App\Http\Controllers\Controller;
use App\Models\committee;
use App\Models\employee;
use App\Models\news;
use App\Models\student;
use Exception;
// use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\Authenticatable;

class AuthController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => [
        //     'login', 'register', 'register_getphone', 'register_verifycode',
        //     'register_getinfo', 'login_verify', 'login_getphone', 'all_comms', 'add_comm', 'delete_comm',
        //     'all_news', 'add_news', 'delete_news', 'authentication', 'verification','all_employees',
        //     'login','register'
        // ]]);
        // $this->middleware('auth:api', ['except' => [
        // 'login','register'
        // ]]);
        // $this->middleware('role:student',['except' => ['register','login']]);
        $this->middleware('role:master', ['only' => ['test']]);
        $this->middleware('auth:api', ['only' => ['user', 'get_pre_registration']]);
    }
    public function register(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'student_number' => 'required|unique:users,username|digits:10',
            'national_code' => 'required|unique:users|digits:10',
            'phone_number' => 'required|unique:users|digits:11|regex:/^(09)+[0-9]{9}$/',
            'email' => 'required|email',
            // 'password' => 'required|min:4',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        $user = User::create([
            'first_name' => $req->first_name,
            'last_name' => $req->last_name,
            'username' => $req->student_number,
            'national_code' => $req->national_code,
            'phone_number' => $req->phone_number,
            'email' => $req->email,
            'password' => Hash::make($req->national_code),
        ])->assignRole('student');
        Student::create([
            'student_number' => $req->student_number
        ]);
        $token = Auth::login($user);
        return response()->json([
            'message' => 'عضویت با موفقیت انجام شد.',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 201);
    }
    public function login(Request $req)
    {
        // return 'hello world';
        $validator = Validator::make($req->all(), [
            'username' => 'required',
            // 'national_code' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        $credentials = $req->only('username', 'password');
        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'message' => 'اطلاعات نادرست است.',
            ], 400);
        }
        $user = Auth::user();
        $user->role = $user->getRoleNames()->first();
        if ($user->role == 'student') {
            $user->load('student');
        } else {
            $user->load('employee');
        }
        return response()->json([
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 201);
    }
    public function user()
    {
        $user = Auth::user();
        $user->role = $user->getRoleNames()->first();
        if ($user->role == 'student') {
            $user->load('student');
        } else {
            $user->load('employee');
        }
        return response()->json([
            'user' => $user
        ]);
    }
    public function test(Request $req)
    {
        $users = User::role('master')->get();
        return response()->json([
            // 'mostafa' => 'binesh',
            'users' => $users
        ]);
    }
    public function get_pre_registration(Request $req)
    {
        $masters = User::role('master')->get();
        $returnMasters = [];
        foreach ($masters as $master) {
            array_push($returnMasters, ['id' => $master->id, 'name' => $master->first_name . " " . $master->last_name]);
        }
        return response()->json([
            'masters' => $returnMasters
        ]);
    }
    public function pish(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'student_number' => 'required', // student number
            'username' => 'required|unique:users|digits:10',
            'national_code' => 'required|unique:users|digits:10',
            'phone_number' => 'required|unique:users|digits:11|regex:/^(09)+[0-9]{9}$/',
            'email' => 'required|email',
            // 'password' => 'required|min:4',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
    }







    // FIX: bayad tooye reg-getinfo, biaim o check konim ke aya in shomayreye telephone ghablan verify shode ya na
}
