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
        $reports =  $student->weeklyReport->reports;
        $unfinishedWeekDays = [];
        foreach ($reports as $week) {
            if ($week['is_done']) {
                continue;
            } else {
                // iterating in days of the week
                // if is_done is true, save the date to the array
                foreach ($week['days'] as $day) {
                    if ($day['is_done']) {
                        array_push($unfinishedWeekDays, $day['date']);
                    }
                }
            }
        }
        // then go to the Reports database and return all of reports in the array
        return response()->json([
            'weeks_todo' => 1, // chand hafte be joz in hafte moonde ke bayad takmil beshe
            'reports' => ReportResource::collection(Report::where('student_id', $student->id)->whereIn('date', $unfinishedWeekDays)->get()),
            'unfinished_week' => WeeklyReportResource::make($student->getLatestUncompletedReportWeek()),
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
        $errors = [];
        $reports = $student->weeklyReport->reports;
        // loop of request report
        $dbReports = null;
        foreach ($req->report as $re) {
            $found = false;
            // loop of database WeeklyReports table > reports array
            for ($i = 0; $i < count($reports); $i++) {
                // loop of days of weeks
                for ($j = 0; $j < count($reports[$i]['days']); $j++) {
                    if ($re['date'] == $reports[$i]['days'][$j]['date'] && $reports[$i]['days'][$j]['is_done'] == false) {
                        $reports[$i]['days'][$j]['is_done'] = true;
                        $found = true;
                        // array_push($dbReports, ['student_id' => $student->id, 'date' => Verta::parse($re['date'])->datetime(), 'description' => $re['description']]);
                        // array_push($dbReports, ['student_id' => $student->id, 'date' => $re['date'], 'description' => $re['description']]);
                        $dbReports = ['student_id' => $student->id, 'date' => $re['date'], 'description' => $re['description']];
                        break;
                    }
                }
                if ($found) break;
            }
            if (!$found) {
                array_push($errors, ['message' => 'خطا در دریافت گزارش تاریخ ' . $re['date']]);
            } else {
                Report::create($dbReports);
            }
        }
        // set week done to true    
        $weeklyReport = WeeklyReport::where('student_id', $student->id)->first();
        $weeklyReport->reports = $reports; // save the modified report
        $weeklyReport->save();
        return response()->json([
            'message' => 'گزارشات با موفقیت ثبت شد',
            'possibleErrors' => $errors,
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
        $errors = [];
        $reports = $student->weeklyReport->reports;
        $dbReport = Report::find($id);
        if (!$dbReport) {
            return response()->json([
                'message' => 'گزارش پیدا نشد',
            ], 400);
        }
        $dbDate = Carbon::parse($dbReport->date)->format('Y-m-d');
        $found = false;
        for ($i = 0; $i < count($reports); $i++) {
            for ($j = 0; $j < count($reports[$i]['days']); $j++) {
                if ($dbDate == $reports[$i]['days'][$j]['date'] && $reports[$i]['days'][$j]['is_done'] == true) {
                    $found = true;
                    $dbReport->description = $req->description;
                    $dbReport->save();
                    break;
                }
            }
            if ($found) break;
        }
        if (!$found) {
            array_push($errors, ['message' => 'خطا در دریافت گزارش تاریخ ' . $dbReport->date]);
        }
        $weeklyReport = WeeklyReport::where('student_id', $student->id)->first();
        $weeklyReport->reports = $reports; // save the modified report
        $weeklyReport->save();
        return response()->json([
            'message' => 'گزارش بروز شد',
            'possibleErrors' => $errors,
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
        $errors = [];
        $reports = $student->weeklyReport->reports;
        $dbReport = Report::find($id);
        if (!$dbReport) {
            return response()->json([
                'message' => 'گزارش پیدا نشد',
            ], 400);
        }
        $dbDate = Carbon::parse($dbReport->date)->format('Y-m-d');
        // foreach ($req->report as $re) {
        $found = false;
        for ($i = 0; $i < count($reports); $i++) {
            for ($j = 0; $j < count($reports[$i]['days']); $j++) {
                if ($dbDate == $reports[$i]['days'][$j]['date'] && $reports[$i]['days'][$j]['is_done'] == true) {
                    $reports[$i]['days'][$j]['is_done'] = false;
                    $found = true;
                    // array_push($dbReports, ['student_id' => $student->id, 'date' => Verta::parse($re['date'])->datetime(), 'description' => $re['description']]);
                    $dbReport->delete();
                    // $dbReport->save();
                    break;
                }
            }
            if ($found) break;
        }
        if (!$found) {
            array_push($errors, ['message' => 'خطا در دریافت گزارش تاریخ ' . $dbReport->date]);
        }
        $weeklyReport = WeeklyReport::where('student_id', $student->id)->first();
        $weeklyReport->reports = $reports; // save the modified report
        $weeklyReport->save();
        return response()->json([
            'message' => 'گزارشات با موفقیت ثبت شد',
            'possibleErrors' => $errors,
        ]);
    }
    public function verifyWeek()
    {
        $student = Auth::user()->student;
        // find the first unfinished week and get all the the days of it 
        $reports =  $student->weeklyReport->reports;
        for ($i = 0; $i < count($reports); $i++) {
            if ($reports[$i]['is_done']) {
                continue;
            } else {
                // check if all reports' days of the week are fullfied
                foreach ($reports[$i]['days'] as $days) {
                    if ($days['is_done'] == false) {
                        return response()->json([
                            'message' => 'باید همه ی روز های این هفته گزارش داشته باشند',
                        ], 400);
                    }
                }
                $reports[$i]['is_done'] = true;
                $student->weeklyReport->reports = $reports;
                $student->weeklyReport->save();
                return response()->json([
                    'message' => 'هفته تایید شد',
                ]);
            }
        }
        return response()->json([
            'message' => 'هفته ای برای تایید پیدا نشد'
        ], 400);
    }
}
