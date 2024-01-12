<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StudentFinalReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $final_report_path = 'storage/final_reports/';
    private function resource($fileName){
        return $this->final_report_path.$fileName;
    }
    public function index()
    {
        $filePath = auth()->user()->student->final_report_path;
        return response()->json([
            'data' => [
                'finalReportPath' => asset($filePath),
                'name' => $filePath,
                'size' => Storage::disk('public')->size($filePath)
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'final_report' => 'required|max:10240|mimes:pdf,docx,doc',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        $fileName = time() . $request->file('final_report')->getClientOriginalName();
        $request->file('image')->storeAs('public/final_reports/', $fileName);
        $student = auth()->user()->student;
        $student->final_report_path = $fileName;
        $student->save();
        return response()->json([
            'message' => 'فایل با موفقیت آپلود شد',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Student::where('id',$id)->first();
        if(!isset($student->id)){
            return response()->json([
                'error'=>'دانشجو یافت نشد'
            ],404);
        }
        if($student->final_report_path == null){
            return response()->json([
                'error'=>'گزارش پایانی برای این دانشجو ثبت نشده است'
            ],404);
        }
        return response()->json([
            'final_report'=>asset($this->resource($student->final_report_path)),
        ]);
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

    public function destroy()
    {
        $student = auth()->user()->student;
        if ($student->final_report_path == null ) {
            return response()->json([
                'message' => 'گزارش پایانی برای شما یافت نشد',
            ], 404);
        }
        Storage::delete($this->final_report_path . $student->final_report_path);
        try {
            unlink(public_path($this->final_report_path . $student->final_report_path));
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'در حذف فایل مشکلی به وجود آمده است'
            ], 400);
        }
        $student->final_report_path = null;
        $student->save();
        return response()->json([
            'message' => 'گزارش با موفقیت حذف شد',
        ]);
    }
}
