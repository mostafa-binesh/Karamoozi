<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Student;
// use App\Models\Phone_Registration;
use Illuminate\Http\Request;
// use App\Models\committee;
// use App\Models\employee;
// use App\Models\news;
use App\Models\Password_reset;
use Melipayamak\MelipayamakApi;
// use Illuminate\Contracts\Validation\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Mail\send_code_reset_password;
use App\Models\Company;
use App\Models\IndustrySupervisor;

class AuthController extends Controller
{

    public function __construct()
    {
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
            'email' => 'required|unique:users|email',
            // 'password' => 'required|min:4',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        // ADD: error handling
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
            'user_id' => $user->id,
            'student_number' => $req->student_number,
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
        reqConvert($req,'username','password');
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
        // $x =  User::where('username','۳۹۸۱۲۳۱۰۲۰')->first();
        // return Hash::check($x->password,'۵۳۰۰۰۵۳۲۶۰');

        // $credentials = $req->only('username', 'password');
        $credentials = ['username' => $req->username, 'password' => $req->password];
        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'message' => 'اطلاعات نادرست است.',
            ], 400);
        }
        $user = Auth::user();
        $user->role = $user->getRoleNames()->first();
        switch ($user->role) {
            case 'student':
                $user->load('student');
                break;

            case 'employee':
                $user->load('employee');
                break;
            case 'industry_supervisor':
                $user->load('industrySupervisor');
            default:
                # code...
                break;
        }
        // if ($user->role == 'student') {
        // } else {
        // }
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
        // if ($user->role == 'student') {
        //     $user->load('student');
        // } elseif($user->role == 'employee') {
        //     $user->load('employee');
        // } elseif ($user->role == 'industry_boss') {
        //     $user->load('industryBoss');
        // }
        switch ($user->role) {
            case 'student':
                $user->load('student');
                break;

            case 'employee':
                $user->load('employee');
                break;
            case 'industry_supervisor':
                $user->load('industrySupervisor');
            default:
                # code...
                break;
        }
        return response()->json([
            'user' => $user
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
    public function send_reset_password(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'email' => 'required|email|exists:users',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        return $status = Password::sendResetLink(
            $req->only('email')
        );

        // Delete all old code that user send before.
        Password_reset::where('email', $req->email)->delete();

        // Generate random code
        // $data['token'] = mt_rand(100000, 999999);

        // Create a new code
        // $user = User::where('email',$req->email);
        return Password::createToken($req->email);
        // Password::createToken()

        $token = mt_rand(100000, 999999);
        $codeData = Password_reset::create([
            'token' => $token,
            'email' => $req->email
        ]);

        // Send email to user
        Mail::to($req->email)->send(new send_code_reset_password($token));

        return response(['message' => trans('passwords.sent')], 200);
    }
    public function check_password(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'email' => 'required|email|exists:users',
            'token' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        $user =  Password_reset::where('email', $req->email)->first() ?? 'not found';
        return Hash::check($req->token, $user->token);
        // ! add: if result was true, we have to redirect the user to reset password page 
        // ! otherwise to login page with some error message

    }
    public function reset_password(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'email' => 'required|email|exists:users',
            'token' => 'required|string',
            'new_password' => 'required|min:6|string',
            'repeat_password' => 'required|same:new_password',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        $user =  Password_reset::where('email', $req->email)->first() ?? 'not found';
        if (Hash::check($req->token, $user->token)) {
            $user->password = Hash::make($req->new_password);
            $user->save();
        } else {
            return response()->json([
                'message' => 'توکن نامعتبر است'
            ], 400);
        }
        return response()->json([
            'data' => [
                'mesage' => 'عملیات با موفقیت انجام شد'
            ]
        ], 200);
    }
    public function industryBossRegistration(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'national_code' => 'required|string|unique:users,username',
            'phone_number' => 'required|unique:users|digits:11|regex:/^(09)+[0-9]{9}$/',
            'email' => 'required|email|unique:users',
            'company_name' => 'required|string',
            'password' => 'required|string|min:5',
            'repeat_password' => 'required|string|same:password'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        $industrySupervisor = User::create([
            'first_name' => $req->first_name,
            'last_name' => $req->last_name,
            'username' => $req->national_code,
            'national_code' => $req->national_code,
            'phone_number' => $req->phone_number,
            'email' => $req->email,
            'password' => Hash::make($req->password),
        ])->assignRole('industry_supervisor');
        Company::create([
            'company_name' => $req->company_name,
            'company_boss_id' => $industrySupervisor->id,
            'company_type' => 1,
            'verified' => false,
        ]); // ! FIX: company type is a dummy data
        IndustrySupervisor::create([
            'user_id' => $industrySupervisor->id,
            'verified' => false,
        ]);
        $token = Auth::login($industrySupervisor);
        $industrySupervisor->load('industrySupervisor');
        return response()->json([
            'message' => 'عضویت با موفقیت انجام شد.',
            'user' => $industrySupervisor,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 201);
    }
    // FIX: bayad tooye reg-getinfo, biaim o check konim ke aya in shomayreye telephone ghablan verify shode ya na
}
