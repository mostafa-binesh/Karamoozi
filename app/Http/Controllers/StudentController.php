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
use App\Http\Resources\Students\evaluateCompanyOptions;
use App\Http\Resources\Students\preRegFacultiesWithMasters;
use App\Http\Resources\Students\StudentSubmittedCompanyResource;
use App\Http\Resources\Students\SubmittedCompanyEvaluation;
use App\Models\CompanyEvaluation;
use Faker\Extension\CompanyExtension;

class StudentController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['auth:api', 'role:student']);
    }
    // ##########################################
    // ########## PRE REGISTRATIONS ###############
    // ##########################################
    public function get_pre_registration()
    {
        // return null;
        // before submitting the pre reg form,
        // we need to send some data
        // such as masters name, 'sarterm' and ...
        // $masters = User::role('master')->get();
        // $returnMasters = [];
        // TODO: replace this foreach with a api resource collection
        // foreach ($masters as $master) {
        //     array_push($returnMasters, ['id' => $master->employee->id, 'name' => $master->first_name . " " . $master->last_name]);
        // }
        $studentSubmittedCompany = Company::where('student_id', Auth::user()->student->id)->first();
        $x = [
            // 'masters' => $returnMasters,
            // ! one of the most complicated queries of this project
            // https://stackoverflow.com/questions/71462515/nested-relation-wherehas-in-laravel
            // 'faculties' => preRegFacultiesWithMasters::collection(University_faculty::with(['employees' => function($query) {
            //     $query->with(['user' => function($query2) {
            //         $query2->role('master');
            //     }]);
            // }])->get()),
            // previous one with collection
            // 'faculties' => preRegFacultiesWithMasters::collection(University_faculty::with(['employees' => function($query) {
            //     $query->with(['user' => function($query2) {
            //         $query2->role('master');
            //     }]);
            // }])->get()),
            // ? this query's problem was i couldn't get the user and i needed to send a query to the database to get user for every employee
            'faculties' => preRegFacultiesWithMasters::collection(University_faculty::with(['employees' => function ($query) {
                $query->whereHas('user', function ($query2) {
                    $query2->role('master');
                });
            }])->get()),
            // 'faculties' => University_faculty::all(),
            'companies' => CompanyResource::collection(Company::where('verified', true)->get()),
            'student_company' => isset($studentSubmittedCompany) ? StudentSubmittedCompanyResource::make($studentSubmittedCompany) : null,
            'academic_year' => [
                'semester' => 'نیم سال اول',
                'year' => '1401',
            ]
        ];
        return response()->json($x);
    }
    public function post_pre_registration(Request $req)
    {
        // ! REWORK THIS PRE REG CHECK SECTION
        // check pre-reg was not done already
        $student = Auth::user()->student;
        // if ($student->verified) {
        //     return response()->json([
        //         'message' => 'پیش ثبت نام شما از قبل انجام شده است'
        //     ], 400);
        // }
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
        // return $student->company;
        if (!(isset($req->company_id) || isset($student->customCompany->id))) {
            return response()->json([
                'message' => 'شرکتی برای شما معرفی نشده است',
            ], 400);
        } else {
            $company_id = $req->company_id ?? $student->customCompany->id;
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
        $student->pre_reg_done = true; // this field shows pre reg has been done by student or not
        $student->term_id = 1; // ! TODO needs to be dynamic
        $student->save();
        return response()->json([
            'message' => $req->isMethod('post') ?
                'انجام پیش ثبت نام با موفقیت انجام شد' : 'ویرایش پیش ثبت نام با موفقیت انجام شد',
        ]);
    }
    public function put_pre_registration(Request $req)
    {
        // ! REWORK THIS PRE REG CHECK SECTION
        // check pre-reg was not done already
        $student = Auth::user()->student;
        // if ($student->verified) {
        //     return response()->json([
        //         'message' => 'پیش ثبت نام شما از قبل انجام شده است'
        //     ], 400);
        // }
        $validator = Validator::make($req->all(), [
            // 15 fields
            'first_name' => 'required',
            'last_name' => 'required',
            'faculty_id' => 'required|numeric', // FIX later: add exists in faculties
            'degree' => 'required|numeric', // maghta'e tahsili
            'passed_units' => 'required|numeric',
            'internship_master' => 'required|numeric',
            // 'midterm' => 'required', // nim saale avval
            // 'internship_year' => 'required'  , // 1401
            'internship_type' => 'required|numeric',
            'company_id' => 'nullable'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }
        return $student->customCompany;
        if (!(isset($req->company_id) || isset($student->customCompany->id))) {
            return response()->json([
                'message' => 'شرکتی برای شما معرفی نشده است',
            ], 400);
        } else {
            $company_id = $req->company_id ?? $student->customCompany->id;
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
        $student->pre_reg_done = true; // this field shows pre reg has been done by student or not
        $student->save();
        return response()->json([
            'message' => 'ویرایش پیش ثبت نام با موفقیت انجام شد',
        ]);
    }
    public function studentPreRegInfo()
    {
        $user = Auth::user();
        if ($user->student->pre_reg_verified != 1) {
            return response()->json([
                'message' => 'شرکتی برای شما معرفی نشده است',
            ], 400);
        }
        // ! TODO optimize eager loading
        return StudentPreRegInfo::make(Auth::user());
    }
    // first student page api
    public function internshipStatus()
    {
        $student = Auth::user()->student;
        if (
            !$student->IndustrySupervisorVerified()
            || !$student->pre_reg_done
            // || !$student->verified
            // || $student->verified != Student::VERIFIED[1]
            || $student->verified != 2
            // || !$student->form2->university_approval
            // || !$student->faculty_verified
        ) {
            $stage = 1;
            if (isset($student->form2->verified)) {
                $ss = $student->form2->verified == '3' ? true : false;
            } else {
                $ss = false;
            }
            return response()->json([
                'stage' => $stage,
                'data' => [
                    [
                        'name' => 'تاییدیه سرپرست دانشکده',
                        'done' => $student->verified == '0' ? false : true,
                        // ! TODO: tell frontend that need to change it
                        // ! for now, i will change the verified to true and false
                    ],
                    [
                        'name' => 'انجام پیش ثبت نام',
                        'done' => $student->pre_reg_done,
                    ],
                    [
                        'name' => 'تاییدیه سرپرست صنعت',
                        'done' => $student->IndustrySupervisorVerified(),
                    ],
                    [
                        // 'name' => 'تاییدیه مراحل توسط دانشکده',
                        'name' => 'تاییدیه فرم 2 توسط دانشکده',
                        // 'done' => $student->form2->verified ?? false,
                        'done' => $ss,
                    ],
                ]
            ]);
        } elseif (false) {
        }
    }
    // ##########################################
    // ########## COMPANY RELATED FUNCTIONS ###############
    // ##########################################
    // submit or update a custom company in pre reg. page
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
    // evaluation company options
    public function evaluateCompany()
    {
        return response()->json([
            'data' => [
                'options' => evaluateCompanyOptions::collection(Options::where('type', 'student_company_evaluation')->get()),
            ],
        ]);
    }
    public function submitEvaluateCompany(Request $req)
    {
        // ! TODO: add description to the database
        // this id refers to an options row
        $validator = Validator::make($req->all(), [
            'data' => 'required|array|max:20', // max 20 items , data means evaluations, has id (evaluations table) and value (1-4)
            'data.*.id' => 'required|exists:options,id',
            'data.*.value' => 'required',
            'comment' => 'present', // description in db
        ], [
            'data.*.id.exists' => 'این مورد ارزیابی در دیتابیس موجود نیست. لطفا صفحه را رفرش کنید',
            'data.*.value.required' => 'مقدار value برای هر آیتم مورد ارزیابی مورد نیاز است',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        $student = Auth::user()->student;
        // check if student have sent company evaluations already
        $studentEvalution = CompanyEvaluation::where('student_id', $student->id)->first();
        if (isset($studentEvalution)) {
            return response()->json([
                'message' => 'شما ارزیابی را قبلا انجام داده اید',
            ], 400);
        }
        foreach ($req->data as $data) {
            CompanyEvaluation::create([
                'company_id' => $student->company_id,
                'student_id' => $student->id,
                'option_id' => $data['id'],
                'evaluation' => $data['value'],
            ]);
        }
        // create the comment
        CompanyEvaluation::create([
            'company_id' => $student->company_id,
            'student_id' => $student->id,
            'description' => $req->comment,
        ]);
        return response()->json([
            'message' => 'ارزیابی شرکت با موفقیت ثبت شد',
        ]);
    }
    public function studentCompanyEvaluations()
    {
        // ! not optimized (2 queries), i wanna give this structure, any idea?
        // ! -- ideas: create two resources, or create the response data manually without using resources 
        // get all company evaluations of the student + description
        $student = Auth::user()->student;
        $studentEvalution = CompanyEvaluation::where('student_id', $student->id)->first();
        if (!isset($studentEvalution)) {
            return response()->json([
                'message' => 'شما ارزیابی را انجام نداده اید',
            ], 400);
        }
        $comment = CompanyEvaluation::where('student_id', $student->id)->whereNotNull('description')->first();
        return response()->json([
            'data' => [
                'evaluations' => SubmittedCompanyEvaluation::collection(CompanyEvaluation::where('student_id', $student->id)->whereNull('description')->with(['option'])->get()),
                'comment' => $comment->description,
            ]
        ]);
    }
    public function editEvaluateCompany(Request $req)
    {
        // this id refers to an options row
        $validator = Validator::make($req->all(), [
            'data' => 'required|array|max:20', // max 20 items , data means evaluations
            'data.*.id' => 'required|exists:options,id',
            'data.*.value' => 'required', // stored as evaluation in database
            'comment' => 'required', // description in db
        ], [
            'data.*.id.required' => 'این مورد ارزیابی در دیتابیس موجود نیست. لطفا صفحه را رفرش کنید',
            'data.*.value.required' => 'مقدار value برای هر آیتم مورد ارزیابی مورد نیاز است',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        $student = Auth::user()->student;
        $studentEvalution = CompanyEvaluation::where('student_id', $student->id)->first();
        if (!isset($studentEvalution)) {
            return response()->json([
                'message' => 'شما ارزیابی را انجام نداده اید',
            ], 400);
        }
        // update the evaluation without comment
        // ! data['id'] is evaluation table id ! 
        // ! i wish i would handle it in companyEvaluation id way
        foreach ($req->data as $data) {
            CompanyEvaluation::updateOrCreate(['option_id' => $data['id'], 'student_id' => $student->id, 'company_id' => $student->company_id], ['evaluation' => $data['value']]);
        }
        // update the evaluation with comment
        $descriptionEvaluation = CompanyEvaluation::whereNotNull('description')->where('student_id', $student->id)->first();
        $descriptionEvaluation->description = $req->comment;
        return response()->json([
            'message' => 'ویرایش با موفقیت انجام شد',
        ]);
    }
    // ##########################################
    // ########## PROFILE ###############
    // ##########################################
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
