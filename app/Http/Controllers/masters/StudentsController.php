<?php

namespace App\Http\Controllers\masters;

use App\Enums\PreRegVerificationStatusEnum;
use App\Models\Term;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Master\MasterSingleStudent;
use App\Http\Resources\Master\MasterStudents;
use App\Http\Resources\TermMinResource;
use App\Models\Employee;
use App\Models\MasterTerm;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StudentsController extends Controller
{
    public function count()
    {
        $master_id = Employee::where('user_id', Auth::user()->id)->first()->id;
        // if no term exists, return null
        $term = Term::where('start_date', '<=', now())->where('end_date', '>=', now())->first();
        $students_count = MasterTerm::where('master_id', $master_id)->where('term_id', $term->id)->first()?->students_count;
        if ($term) {
            return [
                'term' => TermMinResource::make($term),
                'min' => $students_count ? $students_count : 0
            ];
        } else {
            return [
                'term' => null,
                'min' => $students_count ? $students_count : 0,
            ];
        }
    }
    public function updateCount(Request $req)
    {
        $term = Term::where('start_date', '<=', now())->where('end_date', '>=', now())->first();
        $master_id = Employee::where('user_id', Auth::user()->id)->first()->id;
        $minStudents = Student::where('professor_id',$master_id)->where('term_id',$term->id)->count();
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
        if (!$term) {
            return Term::noTermError();
        }
        $master_term = MasterTerm::where('master_id', $master_id)->where('term_id', $term->id)->first();
        if (isset($master_term->id)) {
            $master_term->students_count = $req->students_count;
            $master_term->save();
        } else {
            MasterTerm::create([
                'master_id' => $master_id,
                'term_id' => $term->id,
                'students_count' => $req->students_count
            ]);
        }
        return [
            'message' => 'عملیات با موفقیت انجام شد',
        ];
    }
    public function verifiedStudents(Request $req)
    {
        // $master = auth()->user()->master;
        // // return $master;
        // return $master->MasterStudents()->with('user')->where('pre_reg_verified', PreRegVerificationStatusEnum::MasterApproved)->cpagination($req, MasterStudents::class);
        $master = Employee::where('user_id', Auth::user()->id)->first();
        $term_id = Term::where('start_date', '<=', now())->where('end_date', '>=', now())->first()->id;
        return Student::where('professor_id', $master->id)->where('term_id', $term_id)
            ->cpagination($req, MasterStudents::class);
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
        if (!($student->pre_reg_verified == PreRegVerificationStatusEnum::MasterPending
            || $student->pre_reg_verified == PreRegVerificationStatusEnum::MasterRefused)) {
            return response()->json([
                'message' => 'نمی توانید این دانشجو را تایید کنید',
            ], 400);
        }
        $student->pre_reg_verified = PreRegVerificationStatusEnum::AdminPending;
        $student->save();
        return response()->json([
            'message' => 'دانشجو تایید شد',
        ]);
    }
    public function unverifyStudent(Request $req, $id)
    {
        $student = Student::with('user')->find($id);
        $master = auth()->user()->master;
        if ($student->professor_id != $master->id) {
            return response()->json([
                'message' => 'این دانشجو به شما تعلق ندارد',
            ], 400);
        }
        if (
            $student->pre_reg_verified == PreRegVerificationStatusEnum::MasterPending
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
