<?php

namespace App\Http\Controllers;

use App\Enums\VerificationStatusEnum;
use Carbon\Carbon;
use App\Models\Report;
use App\Models\WeeklyReport;
use Illuminate\Http\Request;
use Hekmatinasser\Verta\Verta;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ReportResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\WeeklyReportResource;
<<<<<<< HEAD
use App\Models\Employee;
use App\Models\Form2s;
use App\Models\Student;
use App\Models\Term;
use DateTime;
=======
use Illuminate\Support\Carbon as SupportCarbon;
>>>>>>> d2cbe573be860e821458125e079dd93b1d7eac4a

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
<<<<<<< HEAD
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
=======
        // ! todo: eager loading too inja aslan rayat nashode
        // $student = Auth::user()->student->with('weeklyReport');
        $student = Auth::user()->student;
        // find the first unfinished week and get all the the days of it
        $unfinishedWeeks = $student->weeklyReports->groupBy('week_number');
        $firstUnfinishedWeek = WeeklyReport::getFirstUnfinishedWeeklyReport($unfinishedWeeks);
        // dd($firstUnfinishedWeek, $firstUnfinishedWeek->isEmpty());
        // send the reponse
        return response()->json([
            'weeks_todo' => 1, // chand hafte be joz in hafte moonde ke bayad takmil beshe
            'reports' => !$firstUnfinishedWeek->isEmpty() ? ReportResource::collection($firstUnfinishedWeek?->whereNotNull('content')) : null,
            'is_finished' => $firstUnfinishedWeek->isEmpty(),
            'unfinished_week' => !$firstUnfinishedWeek->isEmpty() ? [
                "week_number" => $firstUnfinishedWeek->first()->week_number,
                "first_day_of_week" => firstDayOfWeek($firstUnfinishedWeek->first()->date)->format('Y-m-d'),
                'days' => WeeklyReportResource::collection($firstUnfinishedWeek->where('stauts', VerificationStatusEnum::NotAvailable)),
            ] : null,
        ]);
>>>>>>> d2cbe573be860e821458125e079dd93b1d7eac4a
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
<<<<<<< HEAD
=======
        $student = Auth::user()->student;
        // parse the dates in the request because date field was cast to date in the weeklyReport model
        $carbonParsedDates = castArrayToCarbon(array_column($req->report, 'date'));
        // find all weeklyReport of the student which the date is in request.date
        $weeklyReports = $student->weeklyReports->whereIn('date', $carbonParsedDates);
        // iterate through each found report
        foreach ($req->report as $report) {
            // find the report using date
            $weeklyReport = $weeklyReports->firstWhere('date', Carbon::parse($report['date']));
            // dd($weeklyReport);
            // dd($carbonParsedDates, $weeklyReports, $weeklyReport);
            if ($weeklyReport) {
                // set the weeklyReport's content to req.report.description and set the status to NotChekced
                $weeklyReport->content = $report['description'];
                if ($weeklyReport->status == VerificationStatusEnum::NotAvailable) {
                    $weeklyReport->status = VerificationStatusEnum::NotChecked;
                }
                $weeklyReport->save();
            } else {
                dd("weekly report not found {$report['date']}");
            }
        }
        return response()->json([
            'message' => 'گزارشات با موفقیت ثبت شد',
        ]);
>>>>>>> d2cbe573be860e821458125e079dd93b1d7eac4a
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
<<<<<<< HEAD
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
=======
        $validator = Validator::make($req->all(), [
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        $student = Auth::user()->student;
        // $carbonParsedDates = castArrayToCarbon(array_column($req->report, 'date'));
        $weeklyReport = $student->weeklyReports->whereId('id', $id);
        $weeklyReport->content = $req->description;
        if ($weeklyReport->status == VerificationStatusEnum::NotAvailable) {
            $weeklyReport->status = VerificationStatusEnum::NotChecked;
        }
        $weeklyReport->save();
        return response()->json([
            'message' => 'گزارش بروز شد',
        ]);
>>>>>>> d2cbe573be860e821458125e079dd93b1d7eac4a
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
<<<<<<< HEAD
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
=======
        $student = Auth::user()->student;
        // get the weeklyReport with desired id which belongs to authenticated student
        $weeklyReport = WeeklyReport::where('student_id', $student->id)
            ->where('id', $id)->firstOrFail();
        // set the content to null and set the status to notAvailable
        $weeklyReport->content = null;
        $weeklyReport->status = VerificationStatusEnum::NotAvailable;
        $weeklyReport->save();
        // send response
        return response()->json([
            'message' => 'گزارشات با موفقیت ثبت شد',
        ]);
>>>>>>> d2cbe573be860e821458125e079dd93b1d7eac4a
    }
    public function verifyWeek($id)
    {
<<<<<<< HEAD
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
=======
        // get the student
        $student = Auth::user()->student;
        // get the first unfinished week
        $unfinishedWeeks = $student->weeklyReports->groupBy('week_number');
        $firstUnfinishedWeek = WeeklyReport::getFirstUnfinishedWeeklyReport($unfinishedWeeks);
        // iterate thourgh each weeklyReport and set the is_week_verified to true 
        foreach ($firstUnfinishedWeek as $weeklyReport) {
            $weeklyReport->is_week_verified = true;
            $weeklyReport->save();
        }
        // search again for first unfinished week
        $unfinishedWeeks = $student->weeklyReports()->get()->groupBy('week_number');
        $firstUnfinishedWeek = WeeklyReport::getFirstUnfinishedWeeklyReport($unfinishedWeeks);
        // if no unfinished week was found, set the student's stage to 3
        if (empty($latestUncompletedReportWeek)) {
            $student->stage = 3;
            $student->save();
        }
        return response()->json([
            'message' => 'هفته تایید شد'
        ], 200);
>>>>>>> d2cbe573be860e821458125e079dd93b1d7eac4a
    }
}
