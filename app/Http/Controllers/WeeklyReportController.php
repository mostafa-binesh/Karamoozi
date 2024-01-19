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
use Illuminate\Support\Carbon as SupportCarbon;

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
    public function index()
    {
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
        $validator = Validator::make($req->all(), [
            'report' => 'required|array',
            'report.*.date' => 'required|date',
            'report.*.description' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
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
    }
    public function verifyWeek()
    {
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
    }
}
