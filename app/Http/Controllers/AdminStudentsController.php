<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Resources\PreRegStudents;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\InitRegistrationStudents;
use App\Http\Resources\admin\StudentPreRegDescription;

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
        $students = Student::where('verified', false)->with('user')->cpagination($req, InitRegistrationStudents::class);
        return $students;
    }
    public function preRegStudents(Request $req)
    {
        $students = Student::where('pre_reg_done', true)->with(['user', 'universityFaculty'])->cpagination($req, PreRegStudents::class);
        return $students;
    }
    public function initRegVerifyStudent($id)
    {
        $student = Student::findorfail($id);
        $student->verified = true;
        $student->init_reg_rejection_reason = null;
        $student->save();
        return response()->json([
            'message' => 'دانشجو با موفقیت تایید شد'
        ], 200);
    }
    public function initRegUnVerifyStudent($id,Request $req)
    {
        $validator = Validator::make($req->all(), [
            'reason' => 'required|max:255',
        ]);
        // return Auth::id();
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
                // 'message' => 'دانشجویی با اطلاعات وارد شده یافت نشد'
            ], 400);
        }
        $student = Student::findorfail($id);
        $student->verified = false;
        $student->init_reg_rejection_reason = $req->reason;
        $student->save();
        return response()->json([
            'message' => 'دانشجو با موفقیت رد شد'
        ], 200);
    }
    public function preRegVerifyStudent($id)
    {
        $student = Student::findorfail($id);
        $student->pre_reg_verified = true;
        $student->pre_reg_rejection_reason = null;
        $student->save();
        return response()->json([
            'message' => 'پیش ثبت نام با موفقیت تایید شد'
        ], 200);
    }
    public function preRegUnVerifyStudent($id,Request $req)
    {
        $validator = Validator::make($req->all(), [
            'reason' => 'required|max:255',
        ]);
        // return Auth::id();
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
                // 'message' => 'دانشجویی با اطلاعات وارد شده یافت نشد'
            ], 400);
        }
        $student = Student::findorfail($id);
        $student->pre_reg_verified = false;
        $student->pre_reg_rejection_reason = $req->reason;
        $student->save();
        return response()->json([
            'message' => 'پیش ثبت نام با موفقیت رد شد'
        ], 200);
    }
    public function preRegDesc($id)
    {
        $student = Student::findorfail($id);
        return StudentPreRegDescription::make($student);
    }
}
