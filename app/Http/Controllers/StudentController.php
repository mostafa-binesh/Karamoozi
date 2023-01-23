<?php

namespace App\Http\Controllers;

use App\Http\Resources\Students\CompanyResource;
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
        $this->middleware(['auth:api', 'role:student']);
    }
    public function get_pre_registration()
    {
        $masters = User::role('master')->get();
        $returnMasters = [];
        foreach ($masters as $master) {
            array_push($returnMasters, ['id' => $master->id, 'name' => $master->first_name . " " . $master->last_name]);
        }
        return response()->json([
            'masters' => $returnMasters,
            'faculties' => University_faculty::all(),
            // TODO: show only verified companies here
            'companies' => CompanyResource::collection(Company::all()),
            'student_company' => [
                'name' => Company::where('student_id',Auth::user()->student->id)->first()->company_name ?? null,
            ]
        ]);
    }
    public function post_pre_registration(Request $req)
    {
        // check pre-reg was not done already
        // $student = Student::where('user_id', auth::id())->first();
        $student = Auth::user()->student;
        if ($student->verified) {
            return response()->json([
                'message' => 'پیش ثبت نام شما از قبل انجام شده است'
            ], 400);
        }
        $validator = Validator::make($req->all(), [
            // 15 fields
            'first_name' => 'required',
            'last_name' => 'required',
            'faculty_id' => 'required|numeric', // FIX later: add exists in faculties
            'degree' => 'required|numeric', // maghta'e tahsili
            'passed_units' => 'required|numeric',
            'intership_master' => 'required|numeric',
            'midterm' => 'required',
            'internship_year' => 'required',
            'internship_type' => 'required',
            'company_id' => 'nullable'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }
        if (!(isset($req->company_id) || isset($student->company->id))) {
            return response()->json([
                'message' => 'شرکتی برای شما معرفی نشده است',
            ], 400);
        } else {
            $company_id = $req->company_id ?? $student->company->id;
        }
        $user = Auth::user();
        $user->first_name = $req->first_name;
        $user->last_name = $req->last_name;
        $user->save();
        // edit assigned student to this user
        $student->student_number = $user->username;
        $student->faculty_id = $req->faculty_id;
        $student->passed_units = $req->passed_units;
        $student->professor_id = $req->professor_id;
        $student->internship_year = $req->internship_year;
        $student->internship_type = $req->internship_type;
        $student->company_id = $company_id;
        $student->grade = $req->degree;
        $student->verified = true;
        $student->save();
        return response()->json([
            'message' => $req->isMethod('post') ?
                'انجام پیش ثبت نام با موفقیت انجام شد' : 'ویرایش پیش ثبت نام با موفقیت انجام شد',
        ]);
    }
    public function submitCompany(Request $req)
    {
        $validator = Validator::make($req->all(), [
            // 15 fields
            'name' => 'required|max:255',
            'type' => 'required|max:255',
            'phone_number' => 'required|max:255|regex:/^(09)+[0-9]{9}$/',
            'postal_code' => 'required|numeric|digits:10',
            'address' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        Company::updateOrCreate([
            'student_id' => Auth::user()->student->id
        ], [
            'company_name' => $req->name,
            'company_type' => $req->type,
            'company_number' => $req->phone_number,
            'company_postal_code' => $req->postal_code,
            'company_address' => $req->address,
            'verified' => false,
        ]);
        return response()->json([
            'message' => 'شرکت با موفقیت ثبت شد',
        ], 200);
    }
}
