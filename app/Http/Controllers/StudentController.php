<?php

namespace App\Http\Controllers;

use App\Http\Resources\Students\CompanyResource;
use App\Http\Resources\Students\StudentSubmittedCompanyResource;
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
        // TODO: replace this foreach with a api resource collection
        foreach ($masters as $master) {
            array_push($returnMasters, ['id' => $master->employee->id, 'name' => $master->first_name . " " . $master->last_name]);
        }
        $studentSubmittedCompany = Company::where('student_id', Auth::user()->student->id)->first();
        return response()->json([
            'masters' => $returnMasters,
            'faculties' => University_faculty::all(),
            'companies' => CompanyResource::collection(Company::where('verified', true)->get()),
            // 'student_company' => [
            //     'name' => Company::where('student_id', Auth::user()->student->id)->first()->company_name ?? null,
            // ],
            'student_company' => isset($studentSubmittedCompany) ? StudentSubmittedCompanyResource::make(Company::where('student_id', Auth::user()->student->id)->first()) : null,
            'academic_year' => [
                'semester' => 'نیم سال اول',
                'year' => '1401',
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
            'internship_master' => 'required|numeric',
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
        $student->professor_id = $req->internship_master;
        $student->internship_year = $req->internship_year;
        $student->internship_type = $req->internship_type;
        $student->company_id = $company_id;
        $student->grade = $req->degree;
        $student->pre_reg_verified = true;
        $student->save();
        return response()->json([
            'message' => $req->isMethod('post') ?
                'انجام پیش ثبت نام با موفقیت انجام شد' : 'ویرایش پیش ثبت نام با موفقیت انجام شد',
        ]);
    }
    public function internshipStatus()
    {
        $student = Auth::user()->student;
        $stage = 1;
        if (!$student->IndustrySupervisorVerified() || !$student->pre_reg_verified || !$student->verified) {
            $stage = 1;
            return response()->json([
                'stage' => $stage,  
                'data' => [
                    [
                        'name' => 'تاییدیه سرپرست دانشکده',
                        'done' => $student->verified,
                    ],
                    [
                        'name' => 'انجام پیش ثبت نام',
                        'done' => $student->pre_reg_verified,
                    ],
                    [
                        'name' => 'تاییدیه سرپرست صنعت',
                        'done' => $student->IndustrySupervisorVerified(),
                    ],
                    [
                        'name' => 'تاییدیه سرپرست',
                        'done' => $student->form2->university_approval,
                    ],
                ]
            ]);
        } elseif (false) {
        }
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
