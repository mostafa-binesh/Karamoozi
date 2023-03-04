<?php

namespace App\Http\Controllers;

use App\Http\Resources\admin\StudentForm2;
use App\Http\Resources\admin\StudentForm3;
use App\Http\Resources\admin\StudentFormsStatus;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Resources\PreRegStudents;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\InitRegistrationStudents;
use App\Http\Resources\admin\StudentPreRegDescription;
use App\Http\Resources\UniversityFacultyResource;
use App\ModelFilters\Admin\InitRegStudentsFilter;
use App\ModelFilters\Admin\PreRegStudentsFilter;
use App\ModelFilters\Admin\StudentsFilter;
use App\ModelFilters\StudentFilter;
use App\Models\University_faculty;

class AdminStudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function studentsHomePage()
    {
        $students = Student::all();
        // ! i handled the counters in backend not the database, i guess this way is faster
        // initial registration
        $init_unVerified = 0;
        $init_verified = 0;
        // pre reg students
        $preReg_unVerified = 0;
        $preReg_verified = 0;
        foreach ($students as $student) {
            if ($student->verified == 1) {
                $init_verified++;
            } else if ($student->verified == 2) {
                $init_unVerified++;
            }
            if ($student->pre_reg_verified == 1) {
                $preReg_verified++;
            } else if ($student->pre_reg_verified == 2) {
                $preReg_unVerified++;
            }
        }
        return response()->json([
            'data' => [
                'counters' => [
                    'initReg_verified' => $init_verified,
                    'initReg_unverified' => $init_unVerified,
                    'preReg_verified' => $preReg_verified,
                    'preReg_unverified' => $preReg_unVerified,
                ],
            ]
        ]);
    }
    public function initialRegistrationStudents(Request $req)
    {
        // $students = Student::filter($req->all(), InitRegStudentsFilter::class)->where('verified', false)->with('user')->cpagination($req, InitRegistrationStudents::class);
        $students = Student::filter($req->all(), InitRegStudentsFilter::class)->with('user')->cpagination($req, InitRegistrationStudents::class);
        return response()->json([
            'meta' => $students['meta'],
            'data' => [
                'entrance_years' => Student::select('entrance_year')->distinct('entrance_year')->get(),
                'students' => $students['data'],
            ]
        ]);
    }
    public function preRegStudents(Request $req)
    {
        // $students = Student::filter($req->all(), PreRegStudentsFilter::class)->where('pre_reg_done', true)->with(['user', 'universityFaculty'])->cpagination($req, PreRegStudents::class);
        $students = Student::filter($req->all(), PreRegStudentsFilter::class)->with(['user', 'universityFaculty'])->cpagination($req, PreRegStudents::class);
        return response()->json([
            'meta' => $students['meta'],
            'data' => [
                'faculties' => UniversityFacultyResource::collection(University_faculty::all()),
                'entrance_years' => Student::select('entrance_year')->distinct('entrance_year')->get(),
                'students' => $students['data'],
            ]
        ]);
    }
    public function forms(Request $req)
    {
        $students = Student::filter($req->all(), PreRegStudentsFilter::class)->whereHas("form2")->with(['user', 'universityFaculty', 'company'])->cpagination($req, PreRegStudents::class);
        return response()->json([
            'meta' => $students['meta'],
            'data' => [
                'faculties' => UniversityFacultyResource::collection(University_faculty::all()),
                'entrance_years' => Student::select('entrance_year')->distinct('entrance_year')->get(),
                'students' => $students['data'],
            ]
        ]);
        return $students;
    }
    public function initRegVerifyStudent($id)
    {
        $student = Student::findorfail($id);
        $student->verified = 1; // 1: approved
        $student->init_reg_rejection_reason = null;
        $student->save();
        return response()->json([
            'message' => 'دانشجو تایید شد'
        ], 200);
    }
    public function initRegUnVerifyStudent($id, Request $req)
    {
        $validator = Validator::make($req->all(), [
            'rejection_reason' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        $student = Student::findorfail($id);
        $student->verified = 2; // 2: denied
        $student->init_reg_rejection_reason = $req->rejection_reason;
        $student->save();
        return response()->json([
            'message' => 'دانشجو رد شد'
        ], 200);
    }
    public function initRegDesc($id)
    {
        $student = Student::findorfail($id);
        return response()->json([
            'message' => $student->init_reg_rejection_reason,
        ]);
    }
    public function preRegVerifyStudent($id)
    {
        $student = Student::findorfail($id);
        $student->pre_reg_verified = 1;
        $student->pre_reg_rejection_reason = null;
        $student->save();
        return response()->json([
            'message' => 'پیش ثبت نام تایید شد'
        ], 200);
    }
    public function preRegUnVerifyStudent($id, Request $req)
    {
        $validator = Validator::make($req->all(), [
            'rejection_reason' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        $student = Student::findorfail($id);
        $student->pre_reg_verified = 2;
        $student->pre_reg_rejection_reason = $req->rejection_reason;
        $student->save();
        return response()->json([
            'message' => 'پیش ثبت نام رد شد'
        ], 200);
    }
    public function preRegDesc($id)
    {
        $student = Student::findorfail($id);
        return StudentPreRegDescription::make($student);
    }
    public function studentForms($id)
    {
        // ! not completed yet
        // ! need to add other forms, now just form2nd has been added
        $student = Student::where("id", $id)->with(['form2', 'user','studentEvaluations'])->first();
        return StudentFormsStatus::make($student);
        return $student;
    }
    // ###################################### 
    // ############## FORM2 #####################
    // ###################################### 
    public function form2($id)
    {
        $student = Student::where("id", $id)->with(["form2"])->first();
        return StudentForm2::make($student);
        return $student;
    }
    public function form2Verify($id)
    {
        $student = Student::findorfail($id);
        $student->form2->verified = 1;
        $student->form2->save();
        return response()->json([
            'message' => 'فرم تایید شد',
        ]);
    }
    public function form2unVerify($id)
    {
        $student = Student::findorfail($id);
        // $student->form2->verified = Student::VERIFIED[2];
        $student->form2->verified = 2;
        $student->form2->save();
        return response()->json([
            'message' => 'فرم تایید شد',
        ]);
    }
    public function form3($id)
    {
        $student = Student::where("id", $id)->with("studentEvaluations")->first();
        // return $student->studentEvaluations; 
        return StudentForm3::make($student);
        return $student;
    }
    public function form3Verify($id)
    {
        $student = Student::where("id", $id)->first();
        $student->evaluation_verified = 1;
        return response()->json([
            'message' => 'فرم تایید شد',
        ]);
    }
    public function form3UnVerify($id)
    {
        $student = Student::where("id", $id)->first();
        $student->evaluation_verified = 2;
        return response()->json([
            'message' => 'فرم تایید شد',
        ]);
    }

}
