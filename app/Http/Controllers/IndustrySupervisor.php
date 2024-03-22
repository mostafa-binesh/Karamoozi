<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\IndustrySupervisor as IND;

class IndustrySupervisor extends Controller
{
    public function industrySupervisorHome()
    {
        return response()->json([
            'data' => [
                'total_students_count' => Student::where('supervisor_id', IND::where('user_id', Auth()->user()->id)->first()->id)->count(),
                // 'unevaluated_students_count' => Auth()->user()->industrySupervisor->industrySupervisorUnevaluatedStudents->count(),
                // 'unread_messages' => Auth()->user()->receivedMessages()->count(),
            ]
        ], 200);
    }
    public function industrySupervisorProfile()
    {
        return response()->json([
            'data' => [
                'first_name' => auth()->user()->first_name,
                'last_name' => auth()->user()->last_name,
                'national_code' => auth()->user()->national_code,
                'phone_number' => auth()->user()->phone_number,
                'email' => auth()->user()->email,
                'company_name' => auth()->user()->industrySupervisor->company->company_name,
                'company_type' => auth()->user()->industrySupervisor->company->company_type,
            ]
        ]);
    }
    public function editIndustrySupervisorProfile(Request $req)
    {
        $user = auth()->user();
        $validator = Validator::make($req->all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'phone_number' => 'required|unique:users,phone_number,' . $user->id . '|regex:/^(09)+[0-9]{9}$/',
            'email' => 'required|email|unique:users,email,' . $user->id . '|max:255',
            'company_name' => 'required|max:255',
            'company_type' => 'required|max:255',
            'old_password' => 'required',
            // 'new_password' => 'nullable|min:4'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        $user->first_name = $req->first_name;
        $user->last_name = $req->last_name;
        $user->phone_number = $req->phone_number;
        $user->email = $req->email;
        $user->industrySupervisor->company->company_name = $req->company_name;
        $user->industrySupervisor->company->company_type = $req->company_type;
        if (!Hash::check($req->old_password, $user->password)) {
            return response()->json([
                'message' => "رمز قدیم وارد شده با رمز حساب مطابقت ندارد",
            ], 400);
        }
        $user->industrySupervisor->company->save();
        $user->save();
        return response()->json([
            'message' => 'اطلاعات ویرایش شد',
        ]);
    }
    public function editIndustrySupervisorPassword(Request $req)
    {
        $user = auth()->user();
        $validator = Validator::make($req->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:4'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        if (!Hash::check($req->old_password, $user->password)) {
            return response()->json([
                'message' => "رمز قدیم وارد شده با رمز حساب مطابقت ندارد",
            ], 400);
        }
        $user->password = Hash::make($req->new_password);
        $user->save();
        return response()->json([
            'message' => 'رمز عبور ویرایش شد',
        ]);
    }
}
