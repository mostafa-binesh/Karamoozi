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
        $users = Student::all();
        // ! i handled the counters in backend not the database, i guess this way is faster
        // initial registration
        $init_unVerified = 0;
        $init_verified = 0;
        // pre reg students
        $preReg_unVerified = 0;
        $preReg_verified = 0;
        foreach ($users as $user) {
            if ($user->verified) {
                $init_verified++;
            } else {
                $init_unVerified++;
            }
            if ($user->pre_reg_verified) {
                $preReg_verified++;
            } else {
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
        $students = Student::filter($req->all(), InitRegStudentsFilter::class)->where('verified', false)->with('user')->cpagination($req, InitRegistrationStudents::class);
        return response()->json([
            'data' => [
                'faculties' => University_faculty::all(),
                'students' => $students,
            ],
        ]);
    }
    public function preRegStudents(Request $req)
    {
        $students = Student::filter($req->all(), PreRegStudentsFilter::class)->where('pre_reg_done', true)->with(['user', 'universityFaculty'])->cpagination($req, PreRegStudents::class);
        return response()->json([
            'data' => [
                'faculties' => UniversityFacultyResource::collection(University_faculty::all()),
                'students' => $students,
            ],
        ]);
    }
    public function initRegVerifyStudent($id)
    {
        $student = Student::findorfail($id);
        $student->verified = true;
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
        $student->verified = false;
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
        $student->pre_reg_verified = true;
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
        $student->pre_reg_verified = false;
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
    public function forms(Request $req)
    {
        $students = Student::whereHas("form2")->with(['user', 'universityFaculty'])->cpagination($req, PreRegStudents::class);
        return $students;
    }
    public function studentForms($id)
    {
        // ! not completed yet
        // ! need to add other forms, now just form2nd has been added
        $student = Student::where("id", $id)->with(['form2'])->first();
        return StudentFormsStatus::make($student);
        return $student;
    }
    public function form2($id)
    {
        $student = Student::where("id", $id)->with(["form2"])->first();
        return StudentForm2::make($student);
        return $student;
    }
    public function form3($id){
        $student = Student::where("id", $id)->with(["option"])->with(["students_evaluations"])->first();
        return StudentForm3::make($student);
        return $student;
    }
}
