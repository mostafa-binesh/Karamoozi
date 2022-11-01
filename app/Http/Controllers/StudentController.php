<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\University_faculty;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api','role:student']);
    }
    public function get_pre_registration() {
        $masters = User::role('master')->get();
        $returnMasters = [];
        foreach ($masters as $master) {
            array_push($returnMasters,['id' => $master->id, 'name' => $master->first_name . " " . $master->last_name]);
        }
        return response()->json([
            'masters' => $returnMasters,
            'faculties' => University_faculty::all(),
        ]);
    }
    public function post_pre_registration(Request $req) {
        // check pre-reg was not done already
        $student = Student::where('user_id',auth::id())->first(); 
        if($student->verified) {
            return response()->json([
                'message' => 'پیش ثبت نام شما از قبل انجام شده است'
            ],400);
        }
        $validator = Validator::make($req->all(), [
            // 15 fields
            'first_name' => 'required',
            'last_name' => 'required',
            'faculty_id' => 'required',
            'grade' => 'required', // maghta'e tahsili
            'passed_units' => 'required',
            'intership_master' => 'required',
            'midterm' => 'required',
            'intership_year' => 'required',
            'intership_type' => 'required',
            'company_is_registered' => 'required',
            'company_name' => 'required',
            'company_type' => 'required',
            'company_phone' => 'required',
            'company_postal' => 'required',
            'company_address' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        } 
        // create company 
        Company::create([
            'company_name' => $req->company_name,
            'company_type' => $req->company_type,
            'company_phone' => $req->company_phone,
            'company_postal_code' => $req->company_postal,
            'company_address' => $req->company_address,
            'company_is_registered' => $req->company_is_registered,
        ]);
        $user = User::find(Auth::id());
        $user->first_name = $req->first_name; 
        $user->last_name = $req->last_name; 
        $user->save();
        // edit assigned student to this user
        $student->student_number = $user->username;
        $student->faculty_id = $req->faculty_id;
        $student->passed_units = $req->passed_units;
        $student->professor_id = $req->professor_id;
        $student->intership_year = $req->intership_year;
        $student->intership_type = $req->intership_type;
        $student->company_id = $req->company_id;
        $student->grade = $req->grade;
        $student->verified = true;
        $student->save();
        return response()->json([
            'message' => 'درخواست شما با موفقیت ارسال شد'
        ]);
    }
}
