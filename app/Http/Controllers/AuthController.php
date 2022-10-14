<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Melipayamak\MelipayamakApi;
use App\Models\Phone_Registration;
use App\Http\Controllers\Controller;
// use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\Authenticatable;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'register_getphone', 'register_verifycode', 'register_getinfo', 'login_verify', 'login_getphone']]);
    }
    public function sendSMS($text, $phone_number)
    {
        $username = '9390565606';
        $password = 'C#25S';
        $api = new MelipayamakApi($username, $password);
        $sms = $api->sms();
        // $to = '09390565606';
        $to = $phone_number;
        $from = '50004001565606'; //correct one
        // $from = '50004001565605';
        // $text = 'تست وب سرویس ملی پیامک';
        // $text = 'کد تاییدیه: '. $verification_code;
        $response = $sms->send($to, $from, $text);
        return json_decode($response);
    }
    public function login(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        // $validator->fails()
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'غیر مجاز',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }
    public function login_getphone(Request $req)
    { // verify phone number, send login code
        $validator = Validator::make($req->all(), [
            'phone_number' => 'required|max:255|min:3'
        ]);
        if ($validator->fails()) {
            # code...
            return [
                'status' => 'error',
                'message' => $validator->errors()
            ];
        } else {
            $user = User::where('phone_number', $req->phone_number)->first();
            if (isset($user)) {
                // send sms
                $login_temp_code = rand(1000, 9999);
                $user->phone_code = $login_temp_code;
                $user->save();
                // $user->save('phone_code',$login_temp_code);
                if (isset($req->real)) {
                    $sms = self::sendSMS('کد تاییدیه: ' . $login_temp_code, $req->phone_number);
                } else {
                    $sms = $login_temp_code;
                }
                return response()->json(['status' => 'ok', 'message' => 'sms been sent', 'time' => 120, 'sms' => $sms]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'user not found', 'time' => 120]);
            }
        }
    }
    public function login_verify(Request $req)
    { // verify phone number, send login code
        $validator = Validator::make($req->all(), [
            'phone_number' => 'required',
            'login_code' => 'required|max:255|min:3'
        ]);
        // $validator = Validator::make($req->all(), [
        //     'phone_number' => 'required|max:255|min:3|unique:users'
        // ]);
        if ($validator->fails()) {
            # code...
            return [
                'status' => 'error',
                'message' => $validator->errors()
            ];
        } else {
            $user = User::where('phone_number', $req->phone_number)->first();
            // dd($user);
            if (isset($user) && $user->phone_code == $req->login_code) {
                // login user
                $token = Auth::login($user);
                // $token = Auth::loginUsingId($user->id);
                // $token = auth('auth')->loginUsingId($user->id);
                if (!$token) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'غیرمجاز',
                    ], 401);
                }

                $user = Auth::user();
                return response()->json([
                    'status' => 'ok',
                    'message' => 'user logged in',
                    'user' => $user,
                    'authorisation' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'کاربر یافت نشد']);
            }
        }
    }
    public function register_getphone(Request $req)
    { // phone
        // validate phone number : 11 char, string
        $validator = Validator::make($req->all(), [
            'phone_number' => 'required|max:255|min:3|unique:users'
        ]);
        if ($validator->fails()) {
            # code...
            return [
                'status' => 'error',
                'message' => $validator->errors()
            ];
        } else {
            // TODO: first need to check if sms not been sent in last 2 minutes
            $verification_code = rand(1000, 9999);
            $phone_registration = Phone_Registration::create(['phone_number' => $req->phone_number, 'verification_code' => $verification_code]);
            if (isset($req->real)) {
                $text = 'کد تاییدیه: ' . $verification_code;
                $response = self::sendSMS($text, $req->phone_number);
                return response()->json(['status' => 'ok', 'sms' => $response->StrRetStatus, 'message' => 'پیامک ارسال شد.']);
            } else {
                return response()->json(['status' => 'ok', 'sms' => $verification_code, 'message' => 'خطا در ارسال پیامک']);
            }
            // return ['status' => 'ok', 'message' => "sms has been sent"];
        }
        // check if not exist : send a 5 digit verification code and return status ok, sms sent
    }
    public function register_verifycode(Request $req)
    { //phone2
        // validate verification code
        $validator = Validator::make($req->all(), [
            'phone_number' => 'required',
            'verification_code' => 'required|max:255|min:3'
        ]);
        if ($validator->fails()) {
            # code...
            return [
                'status' => 'error',
                'message' => $validator->errors()
            ];
        } else {
            $verification_code = Phone_Registration::where('phone_number', $req->phone_number)->where('verification_code', $req->verification_code)->first();
            if (isset($verification_code)) {
                return response()->json(['status' => 'ok', 'message' => 'کد تایید مطابقت داشت.']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'کد تایید مطابقت نداشت.']);
            }
        }
    }
    public function register_getinfo(Request $req)
    { //phone3
        $validator = Validator::make($req->all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|max:255|email|unique:users',
            'phone_number' => 'required|max:255|min:5|unique:users',
            'birthday' => 'required|date_format:Y-m-d'
        ]);
        if ($validator->fails()) {
            # code...
            return [
                'status' => 'error',
                'message' => $validator->errors()
            ];
        } else {
            // create user
            $created_user = User::create([
                'first_name' => $req->first_name,
                'last_name' => $req->last_name,
                'email' => $req->email,
                'phone_number' => $req->phone_number,
                'birthday' => $req->birthday,
            ]);
            // if (isset($created_user)) {
            // user has been created
            $token = Auth::login($created_user);
            return response()->json([
                'status' => 'ok',
                'message' => 'عضویت با موفقیت انجام شد.',
                'user' => $created_user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
            // return response()->json(['status' => 'ok', 'message' => 'user has been created']);
            // } else {
            // return response()->json(['status' => 'error', 'message' => 'user has not been created']);
            // }
        }
        // validate info
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'عضویت با موفقیت انجام شد.',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'با موفقیت خارج شدید.',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
