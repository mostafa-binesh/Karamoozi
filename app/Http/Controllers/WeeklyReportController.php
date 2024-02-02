<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Report;
use App\Models\WeeklyReport;
use Illuminate\Http\Request;
use Hekmatinasser\Verta\Verta;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ReportResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\WeeklyReportResource;
use App\Models\Employee;
use App\Models\Form2s;
use App\Models\Student;
use App\Models\Term;
use DateTime;

// ! status Coding : 0->not verify, 1->verify master, 2->verify supervisor
class WeeklyReportController extends Controller
{
    // ! weekly reports which been submitted by students
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // ! maybe add this to trait, because it's been used in here and in student model as well
    public const DAYSOFWEEK = [
        0 => 'شنبه',
        1 => 'یکشنبه',
        2 => 'دوشنبه',
        3 => 'سه شنبه',
        4 => 'چهار شنبه',
        5 => 'پنج شنبه',
        6 => 'جمعه',
    ];
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('student')) {
            $validate = Validator::make($request->all(), [
                'start' => 'date',
                'end' => 'date',
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'errors' => $validate->errors()
                ], 400);
            }
            $term_id = Term::where('start_date', '<=', now())->where('end_date', '>=', now())->first()->id;
            $student_id = Student::where('user_id', $user->id)->first()->id;
            $reports = WeeklyReport::where('student_id', $student_id)->where('term_id', $term_id)->cpagination($request, WeeklyReportResource::class);
            if (isset($request->start) and !isset($request->end)) {
                $reports = WeeklyReport::where('student_id', $student_id)->where('term_id', $term_id)->where('report_date', '>=', $request->start)->cpagination($request, WeeklyReportResource::class);
            }
            if (isset($request->end) and !isset($request->start)) {
                $reports = WeeklyReport::where('student_id', $student_id)->where('term_id', $term_id)->where('report_date', '<=', $request->end)->cpagination($request, WeeklyReportResource::class);
            }
            if (isset($request->end) and isset($request->start)) {
                $reports = WeeklyReport::where('student_id', $student_id)->where('term_id', $term_id)->where('report_date', '<=', $request->end)->where('report_date', '>=', $request->start)->cpagination($request, WeeklyReportResource::class);
            }
            return $reports;
        } elseif ($user->hasRole('master') || $user->hasRole('admin')) {
            if ($user->hasRole('master')) {
                $professor_id  = Student::where('id', $request->student_id)->first()->professor_id;
                $employee_id = Employee::where('user_id', $user->id)->first()->id;
                if ($professor_id  != $employee_id) {
                    return response()->json([
                        'error' => 'این دانش آموز برای شما نیست'
                    ], 400);
                }
            }
            $validate = Validator::make($request->all(), [
                'start' => 'date',
                'end' => 'date',
                'student_id' => 'required'
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'errors' => $validate->errors()
                ], 400);
            }
            $term_id = Term::where('start_date', '<=', now())->where('end_date', '>=', now())->first()->id;
            $student_id = $request->student_id;
            $reports = WeeklyReport::where('student_id', $student_id)->where('term_id', $term_id)->cpagination($request, WeeklyReportResource::class);
            if (isset($request->start) and !isset($request->end)) {
                $reports = WeeklyReport::where('student_id', $student_id)->where('term_id', $term_id)->where('report_date', '>=', $request->start)->cpagination($request, WeeklyReportResource::class);
            }
            if (isset($request->end) and !isset($request->start)) {
                $reports = WeeklyReport::where('student_id', $student_id)->where('term_id', $term_id)->where('report_date', '<=', $request->end)->cpagination($request, WeeklyReportResource::class);
            }
            if (isset($request->end) and isset($request->start)) {
                $reports = WeeklyReport::where('student_id', $student_id)->where('term_id', $term_id)->where('report_date', '<=', $request->end)->where('report_date', '>=', $request->start)->cpagination($request, WeeklyReportResource::class);
            }
            return $reports;
        }
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
    public function store(Request $req)
    {
        $user = Auth::user();
        if ($user->hasRole('student')) {
            $validator = Validator::make($req->all(), [
                'reports' => 'required|array'
            ]);
            if ($validator->fails()) {
                return $validator->errors();
            }
            $term_id = Term::where('start_date', '<=', now())->where('end_date', '>=', now())->first()->id;
            $student_id = Student::where('user_id', $user->id)->first()->id;
            $internship_started_at = Form2s::where('student_id', $student_id)->first()->internship_started_at;
            if (!$internship_started_at) {
                return response()->json([
                    'error' => 'فرم شماره دو شما پر نشده '
                ], 400);
            }
            $givenDate = Carbon::createFromFormat('Y-m-d', $internship_started_at);
            $currentDate = Carbon::now();
            $weeksPassed = $givenDate->diffInWeeks($currentDate);
            $reports = $req->reports;
            foreach ($reports as $report) {
                $weekly = WeeklyReport::where('student_id', $student_id)->where('term_id', $term_id)->where('report_date', $report["date"])->first();
                if (isset($weekly->id)) {
                    return response()->json([
                        'error' => 'تاریخ این گزارش تکراری هست',
                        'report' => $report
                    ], 400);
                }
                WeeklyReport::create([
                    'student_id' => $student_id,
                    'term_id' => $term_id,
                    'report' => $report["description"],
                    'report_date' => $report["date"],
                    'week_number' => $weeksPassed,
                    'status' => 0,
                    'is_week_verified' => 0
                ]);
            }
            return response()->json([
                'message' => 'گزارش با موفقیت اضافه شد'
            ]);
        } else {
            return response()->json([
                'error' => 'شما دانش اموز نیستید'
            ], 400);
        }
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
    public function update(Request $req, $id)
    {
        $user = Auth::user();
        if ($user->hasRole('student')) {
            $validator = Validator::make($req->all(), [
                'reports' => 'required|array'
            ]);
            if ($validator->fails()) {
                return $validator->errors();
            }
            $report_edit = WeeklyReport::where('id', $id)->first();
            if (!isset($report_edit->id)) {
                return response()->json([
                    'errors' => 'گزارش یافت نشد'
                ], 400);
            }
            $student_id = Student::where('user_id', $user->id)->first()->id;
            $reports = $req->reports;
            $weekly = WeeklyReport::where('student_id', $student_id)
                ->where('report_date', $reports[0]['date'])
                ->where('id', '!=', $id)
                ->first();
            if (isset($weekly->id)) {
                return response()->json([
                    'error' => 'تاریخ این گزارش تکراری هست'
                ], 400);
            }
            $report_edit->report = $reports[0]['description'];
            $report_edit->report_date = $reports[0]['date'];
            $report_edit->save();
            return response()->json([
                'message' => 'گزارش با موفقیت ادیت شد'
            ]);
        } else {
            return response()->json([
                'error' => 'شما دانش اموز نیستید'
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if ($user->hasRole('student')) {
            $report_edit = WeeklyReport::where('id', $id)->first();
            if (!isset($report_edit->id)) {
                return response()->json([
                    'errors' => 'گزارش یافت نشد'
                ], 400);
            }
            if ($report_edit->status == 1 or $report_edit->status == 2) {
                return response()->json([
                    'error' => 'شما اجازه حذف این گزارش را ندارید'
                ], 400);
            }
            WeeklyReport::destroy($report_edit->id);
            return response()->json([
                'message' => 'گزارش با موفقیت حذف شد'
            ]);
        } else {
            return response()->json([
                'error' => 'شما دانش اموز نیستید'
            ], 400);
        }
    }
    public function verifyWeek($id)
    {
        $user = Auth::user();
        if ($user->hasRole('master')) {
            $professor_id  = Student::where('id', $id)->first()->professor_id;
            $employee_id = Employee::where('user_id', $user->id)->first()->id;
            if ($professor_id  != $employee_id->id) {
                return response()->json([
                    'error' => 'این دانش آموز برای شما نیست'
                ], 400);
            }
            $reports = WeeklyReport::where('student_id', $id)->update(['status' => 1]);
            return response()->json([
                'message' => 'تایید توسط استاد انجام شد'
            ]);
        } elseif ($user->hasRole('admin')) {
            $reports = WeeklyReport::where('student_id', $id)->update(['status' => 2]);
            return response()->json([
                'message' => 'تایید توسط سرپرست انجام شد'
            ]);
        }
    }
}
