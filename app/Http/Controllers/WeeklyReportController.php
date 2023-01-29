<?php

namespace App\Http\Controllers;

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
        $studentSchedule = Auth::user()->student->schedule();
        // return $studentSchedule;
        $datetime = verta('2023-01-7');
        $datetime2 = verta('2023-01-7')->copy();
        // return $datetime->addDays(3);
        $i = 0;
        $lasti = 0;
        $allowedDays = [];
        foreach ($studentSchedule as $schedule) {
            // return $schedule;
            if ($schedule != '00:00,00:00,00:00,00:00') {
                array_push($allowedDays,[
                    'title' => self::DAYSOFWEEK[$i],
                    // 'property' => $i,
                    'date' => $datetime->addDays($i - $lasti)->format('Y/n/j'), 
                ]);
                $lasti = $i;
            } 
            $i++;
        }
        return [
            'data' => [
                'start_date' => $datetime2->format('Y/n/j'),
                'allowed_days' => $allowedDays,
            ]
        ];
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
        // make sure every object only has date and description
        $req2 = [];
        foreach ($req->report as $re) {
            // dd($re);
            // return $re['description'];
            array_push($req2, ['date' => $re['date'], 'description' => $re['description'], 'student_id' => Auth::user()->student->id]);
        }
        $req = $req2;
        try {
            WeeklyReport::insert($req);
            return response()->json([
                'message' => 'گزارشات با موفقیت ثبت شد',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'خطا در ثبت گزارشات'
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
