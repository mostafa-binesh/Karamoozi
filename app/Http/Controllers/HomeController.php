<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Master;
use App\Models\Student;
use App\Models\Term;
use App\Models\University_faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function statics()
    {
        $term_id = Term::where('start_date', '<=', now())->where('end_date', '>=', now())->first()->id;
        return response()->json([
            'faculties' => University_faculty::get()->count(),
            'companies' => Company::get()->count(),
            'students' => Student::where('term_id', $term_id)->count(),
            'field_of_study' => University_faculty::get()->count(),
            'masters' => Employee::get()->count(),
            'all_of_students' => Student::get()->count(),
        ]);
    }
    public function get_student_data(Request $req)
    {
        // return "ddddd";
        $val = Validator::make($req->all(), [
            'student_number' => 'required|digits:10'
        ]);
        if ($val->fails()) {
            return response()->json([
                'error' => $val->errors()
            ], 400);
        }
        $student = Student::where('student_number', $req->student_number)->first();
        if (!isset($student->id)) {
            return response()->json([
                'error' => 'دانشجو یافت نشد'
            ], 400);
        }
        return response()->json([
            'fist_name' => $student->user->first_name,
            'last_name' => $student->user->last_name,
            'national_code' => $student->user->national_code
        ]);
    }
}
