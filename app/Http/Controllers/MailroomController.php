<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\ModelFilters\Studenets\StudentFilter;
use App\Models\Student;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\From7s as Form7;

class MailroomController extends Controller
{
    public function get_student(Request $request)
    {
        $val = Validator::make($request->all(), [
            'status' => 'required|integer'
        ]);
        if ($val->fails()) {
            return response()->json([
                'errors' => $val->errors()
            ], 400);
        }
        $term_id = Term::where('start_date', '<=', now())->where('end_date', '>=', now())->first()->id;
        $students = Student::where('stage', 3)
            ->where('term_id', $term_id)
            ->where('internship_finished', $request->status)
            ->filter($request->all(), StudentFilter::class)
            ->cpagination($request, StudentResource::class);
        return response()->json([
            'student' => $students
        ]);
    }

    public function complete_letter(Request $request){
        $val = Validator::make($request->all(),[
            'letter_date'=>'required|data',
            'letter_number'=>'required|string',
            'student_id'=>'required|exists:students,id'
        ]);
        if ($val->fails()) {
            return response()->json([
                'errors' => $val->errors()
            ], 400);
        }
        $term_id = Term::where('start_date', '<=', now())->where('end_date', '>=', now())->first()->id;
        $form7 = Form7::where('term_id',$term_id)->where('student_id',$request->student_id)->first();
        if(!isset($form7->id)){
            return response()->json([
                'error'=>'نامه ای یافت نشد'
            ],404);
        }
        $form7->letter_date = $request->letter_date;
        $form7->letter_number = $request->letter_number;
        $form7->save();
        return response()->json([
            'message'=>'شماره نامه و تاریخ نامه با موفقیت ثبت شد'
        ]);
    }

    public function update_letter(Request $request){
        $val = Validator::make($request->all(),[
            'letter_date'=>'required|data',
            'letter_number'=>'required|string',
            'letter_id'=>'required|exists:Form7s,id'
        ]);
        if ($val->fails()) {
            return response()->json([
                'errors' => $val->errors()
            ], 400);
        }
        $form7 = Form7::where('id',$request->letter_id)->first();
        if(!isset($form7->id)){
            return response()->json([
                'error'=>'نامه ای یافت نشد'
            ],404);
        }
        $form7->letter_date = $request->letter_date;
        $form7->letter_number = $request->letter_number;
        $form7->save();
        return response()->json([
            'message'=>'شماره نامه و تاریخ نامه با موفقیت ویرایش شد'
        ]);
    }

    public function show_letter(Request $request){
        $val = Validator::make($request->all(),[
            'student_id'=>'required|exists:students,id'
        ]);
        if ($val->fails()) {
            return response()->json([
                'errors' => $val->errors()
            ], 400);
        }
        $term_id = Term::where('start_date', '<=', now())->where('end_date', '>=', now())->first()->id;
        $form7 = Form7::where('term_id',$term_id)->where('student_id',$request->student_id)->first();
        if(!isset($form7->id)){
            return response()->json([
                'error'=>'نامه ای یافت نشد'
            ],404);
        }
        return response()->json([
            'letter'=>[
                'id'=>$form7->id,
                'letter_date'=>$form7->letter_date,
                'letter_number'=>$form7->letter_number,
            ]
        ]);

    }
}
