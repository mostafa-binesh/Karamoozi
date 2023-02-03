<?php

namespace App\Http\Controllers;

use App\Http\Resources\WeeklyReportResource;
use App\Models\Report;
use App\Models\WeeklyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WeeklyReportController extends Controller
{
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
        $student = Auth::user()->student;
        return WeeklyReportResource::make($student->getLatestUncompletedReportWeek());
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
        // return [1,2,3,4,5];
        // return $req;
        $validator = Validator::make($req->all(), [
            'report.*.date' => 'required|date',
            'report.*.description' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        // student must done his pre reg. to this step
        // make sure every object only has date and description
        $req2 = [];
        $student = Auth::user()->student;
        $errors = [];
        $reports = $student->weeklyReport->reports;
        foreach ($req->report as $re) {
            $found = false;

            for ($i = 0; $i < count($reports); $i++) {
                for ($j = 0; $j < count($reports[$i]['days']); $j++) {
                    // return $re['date'];
                    if ($re['date'] == $reports[$i]['days'][$j]['date']) {
                        $reports[$i]['days'][$j]['is_done'] = true;
                        // return var_dump($reports[$i]['days'][$j]['is_done']);
                        $found = true;
                    }
                }
            }
            if (!$found) {
                array_push($errors, ['message' => 'خطا در دریافت گزارش تاریخ ' . $re['date']]);
            }
            Report::insert($req2);
        }
        // return $reports;
        $weeklyReport = WeeklyReport::where('student_id', $student->id)->first();
        $weeklyReport->reports = $reports;
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
}
