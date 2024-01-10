<?php

namespace App\Http\Controllers\masters;

use App\Enums\PreRegVerificationStatusEnum;
use App\Models\Term;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Master\MasterSingleStudent;
use App\Http\Resources\Master\MasterStudents;
use App\Http\Resources\TermMinResource;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;

class StudentsController extends Controller
{
    public function count()
    {
        // if no term exists, return null
        $minStudents = 10;
        $term = Term::currentTerm()->first();
        if ($term) {
            return [
                'term' => TermMinResource::make($term),
                'min' => 10,
            ];
        } else {
            return [
                'term' => null,
                'min' => $minStudents,
            ];
        }
    }
    public function updateCount(Request $req)
    {
        $minStudents = 10;
        $validator = Validator::make($req->all(), [
            // ! todo: make students_count validation dynamic
            'students_count' => 'required|integer|min:' . $minStudents,
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }
        // check if current term exists
        $term = Term::currentTerm()->first();
        if (!$term) {
            return Term::noTermError();
        }
        $master = auth()->user()->employee;
        // create new record in master-term, if exists, update it
        $master->terms()->sync([$term->id => ['students_count' => $req->students_count]]);
        // return response
        return [
            'message' => 'عملیات با موفقیت انجام شد',
        ];
    }
    public function verifiedStudents(Request $req)
    {
        $master = auth()->user()->master;
    return $master->MasterStudents()->with('user')->where('pre_reg_verified', PreRegVerificationStatusEnum::MasterApproved)->cpagination($req, MasterStudents::class);
    }
    public function pendingStudents(Request $req)
    {
        $master = auth()->user()->master;
        return $master->MasterStudents()->with('user')->where('pre_reg_verified', PreRegVerificationStatusEnum::MasterPending)->cpagination($req, MasterStudents::class);
    }
    public function singleStudent(Request $req, $id)
    {
        $student = Student::with('user')->find($id);
        $master = auth()->user()->master;
        if ($student->professor_id != $master->id) {
            return response()->json([
                'message' => 'این دانشجو به شما تعلق ندارد',
            ], 400);
        }
        return MasterSingleStudent::make($student);
    }
    public function verifyStudent(Request $req, $id)
    {
        $student = Student::with('user')->find($id);
        $master = auth()->user()->master;
        if ($student->professor_id != $master->id) {
            return response()->json([
                'message' => 'این دانشجو به شما تعلق ندارد',
            ], 400);
        }
        if ($student->pre_reg_verified == PreRegVerificationStatusEnum::MasterPending
                || $student->pre_reg_verified == PreRegVerificationStatusEnum::MasterRefused
        ) {
            return response()->json([
                'message' => 'نمی توانید این دانشجو را تایید کنید',
            ], 400);
        }
        $student->pre_reg_verified = PreRegVerificationStatusEnum::MasterApproved;
        $student->save();
        return response()->json([
            'message' => 'دانشجو تایید شد',
        ]);
    }
    public function unverifyStudent(Request $req,$id)
    {
        $student = Student::with('user')->find($id);
        $master = auth()->user()->master;
        if ($student->professor_id != $master->id) {
            return response()->json([
                'message' => 'این دانشجو به شما تعلق ندارد',
            ], 400);
        }
        if ($student->pre_reg_verified == PreRegVerificationStatusEnum::MasterPending
                || $student->pre_reg_verified == PreRegVerificationStatusEnum::MasterApproved
        ) {
            return response()->json([
                'message' => 'نمی توانید این دانشجو را رد کنید',
            ], 400);
        }
        $student->pre_reg_verified = PreRegVerificationStatusEnum::MasterRefused;
        $student->save();
        return response()->json([
            'message' => 'دانشجو رد شد',
        ]);
    }
}
