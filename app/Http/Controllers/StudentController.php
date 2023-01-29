<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Options;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\University_faculty;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\StudentProfile;
use App\Http\Resources\StudentPreRegInfo;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Students\CompanyResource;
use App\Http\Resources\Students\StudentSubmittedCompanyResource;

class StudentController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['auth:api', 'role:student']);
    }
    public function get_pre_registration()
    {
        // before submitting the pre reg form,
        // we need to send some data
        // such as masters name, 'sarterm' and ...
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
            'student_company' => isset($studentSubmittedCompany) ? StudentSubmittedCompanyResource::make($studentSubmittedCompany) : null,
            'academic_year' => [
                'semester' => 'نیم سال اول',
                'year' => '1401',
            ]

        ]);
    }
    public function post_pre_registration(Request $req)
    {
        // check pre-reg was not done already
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
            // 'midterm' => 'required', // nim saale avval
            // 'internship_year' => 'required', // 1401
            'internship_type' => 'required|numeric',
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
        // TODO: check: submitted company must be verified
        $user = Auth::user();
        $user->first_name = $req->first_name;
        $user->last_name = $req->last_name;
        $user->save();
        // edit assigned student to this user
        $student->student_number = $user->username;
        $student->faculty_id = $req->faculty_id;
        $student->passed_units = $req->passed_units;
        $student->professor_id = $req->internship_master;
        // ! fix semester and internship year later
        $student->semester = 1;
        $student->internship_year = 1401;
        $student->internship_type = $req->internship_type;
        $student->company_id = $company_id;
        $student->grade = $req->degree;
        // TODO: pre_reg_verified needs to be renamed to pre_reg_done
        $student->pre_reg_verified = true; // this field shows pre reg has been done by student or not
        $student->save();
        return response()->json([
            'message' => $req->isMethod('post') ?
                'انجام پیش ثبت نام با موفقیت انجام شد' : 'ویرایش پیش ثبت نام با موفقیت انجام شد',
        ]);
    }
    public function studentPreRegInfo()
    {
        return StudentPreRegInfo::make(Auth::user());
    }
    public function internshipStatus()
    {
        $student = Auth::user()->student;
        if (
            !$student->IndustrySupervisorVerified()
            || !$student->pre_reg_verified
            || !$student->verified
            || !$student->form2->university_approval
        ) {
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
                        // 'name' => 'تاییدیه سرپرست',
                        'name' => 'تاییدیه مراحل توسط دانشکده',
                        'done' => $student->form2->university_approval ?? false,
                    ],
                ]
            ]);
        } elseif (false) {
        }
    }
    public function submitCompany(Request $req)
    {
        $validator = Validator::make($req->all(), [
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
    public function getStudentProfile()
    {
        return StudentProfile::make(Auth::user()->student);
    }
    public function editStudentProfile(Request $req)
    {
        // ! parameters? 
        // password
        // email
        // phone number
        // #
        $validator = Validator::make($req->all(), [
            'email' => 'required|email|max:255',
            'phone_number' => 'required|max:255|regex:/^(09)+[0-9]{9}$/',
            'current_password' => 'required|max:255',
            'new_password' => 'nullable|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        // check if currentPassword param. is equal to user's database password
        if ($req->current_password) {
            if (!Hash::check($req->current_password, Auth::user()->password)) {
                return response()->json([
                    'message' => 'رمز عبور فعلی وارد شده با رمز حساب مطابقت ندارد',
                ], 400);
            }
        }
        // change user info
        $user = Auth::user();
        $user->email = $req->email;
        $user->phone_number = $req->phone_number;
        if (isset($req->new_password)) {
            $user->password = Hash::make($req->new_password);
        }
        $user->save();
        // show success message
        return response()->json([
            'message' => 'پروفایل با موفقیت ویرایش شد',
        ], 200);
    }
}
