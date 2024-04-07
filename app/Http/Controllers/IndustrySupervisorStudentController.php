<?php

namespace App\Http\Controllers;

use App\Enums\VerificationStatusEnum;
use App\Models\User;
use App\Models\Form2s;
use App\Models\Report;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\StudentResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserPaginationResource;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use App\Http\Resources\IndustrySupervisorStudentsList;
use App\Http\Resources\IndustrySupervisor\CheckStudent;
use App\Http\Resources\IndustrySupervisor\IndustrySupervisorsStudent;
use App\Http\Resources\IndustrySupervisor\StudentEvaluationResource;
use App\Models\Form3s;
use App\Models\Option;
use App\Models\StudentEvaluation;
use App\Models\Term;
use App\Models\WeeklyReport;
use App\Models\From7s as Form7;
use App\Models\IndustrySupervisor;

class IndustrySupervisorStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        return auth()->user()->industrySupervisor->industrySupervisorStudents()->filter($req->all())->cpagination($req, StudentEvaluationResource::class);
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
        // ! OPTIMIZATION queries in this request are not OPTIMIZED
        // in this request, we need both studentNumber and nationalCode for security reasons,
        // -- because ind. supervisor
        // TODO: add check that schedule table should be written correctly
        // -- for example, first hours cannot be 11:00 and second one 8:00
        $validator = Validator::make($req->all(), [
            'student_number' => 'required|exists:students,student_number',
            'national_code' => 'required|exists:users,national_code',
            'introduction_letter_number' => 'required',
            'introduction_letter_date' => 'required|date',
            'internship_department' => 'required',
            'supervisor_position' => 'required',
            'internship_start_date' => 'required|date',
            // 'internship_website' => 'present',
            'description' => 'nullable',
            'schedule_table' => 'sometimes|array|size:6',
            // 'reports' => 'required|array',
            // 'reports.*.date' => 'required|date',
            // 'reports.*.desc' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        $ind_id = IndustrySupervisor::where('user_id', auth()->user()->id)->first()->id;
        if (!$ind_id) {
            return response()->json([
                'error' => 'این سرپرست یافت نشد. '
            ], 400);
        }
        $form2 = Form2s::where('student_id', Student::where('student_number', $req->student_number)->first()->id)
            // ->first();
            ->exists();
        if ($form2) {
            return response()->json([
                'message' => 'اطلاعات این دانشجو قبلا ثبت شده است',
            ], 404);
        }
        $form2 = Form2s::create([
            'industry_supervisor_id' => $ind_id,
            'student_id' => User::where('national_code', $req->national_code)->firstorfail()->student->where('student_number', $req->student_number)->first()->id ?? abort(404),
            // ! fix later, dry
            'schedule_table' => $req->schedule_table,
            'introduction_letter_number' => $req->introduction_letter_number,
            'introduction_letter_date' => $req->introduction_letter_date,
            'internship_department' => $req->internship_department,
            'supervisor_position' => $req->supervisor_position,
            'internship_started_at' => $req->internship_start_date,
            'internship_website' => $req->internship_website ? $req->internship_website : '1',
            'description' => $req->description,
            // 'schedule_table' => $req->schedule_table ?? null,
            'verified' => 1,
            // waiting
        ]);
        $student = Student::where("student_number", $req->student_number)->first();
        $student->supervisor_id = $ind_id;
        $student->unevaluate();
        $student->save();
        // submit reports
        // foreach ($req->reports as $report) {
        //     $result = Report::create([
        //         'form2_id' => $form2->id,
        //         'date' => $report['date'],
        //         'description' => $report['desc'],
        //     ]);
        // }
        // set the reports attr. of the weeklyReports table for this student
        // $allWorkingDaysDate = $student->calculateAllWorkingDaysDate();
        // if ($allWorkingDaysDate == 0) {
        //     return response()->json([
        //         'message' => 'لطفا برنامه ی معتبری را وارد کنید',
        //     ], 400);
        // }
        // // create weekly reports
        // // return($allWorkingDaysDate);
        // foreach ($allWorkingDaysDate as $report) {
        //     $days = $report['days'];
        //     foreach ($days as $index => $day) {
        //         WeeklyReport::new($student->id, $day['date'],$report['week_number'], VerificationStatusEnum::NotChecked,null);
        //     }
        // }
        return response()->json(['message' => 'دانشجو با موفقیت ثبت شد']);
        // return $form2;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(['id' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = Student::where('student_number', $id)->first();
        if (!$student) {
            return response()->json([
                'message' => 'چنین دانشجویی یافت نشد',
            ], 400);
        }
        $form2 = Form2s::where('student_id', $student->id)->first();
        if (!$form2) {
            return response()->json([
                'message' => 'شما این دانشجو را ثبت نکرده اید',
            ], 400);
        }
        return IndustrySupervisorsStudent::make($form2);
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
        // ! OPTIMIZATION queries in this request are not OPTIMIZED
        $validator = Validator::make($req->all(), [
            'student_number' => 'required|exists:students,student_number',
            'introduction_letter_number' => 'required',
            'introduction_letter_date' => 'required|date',
            'internship_department' => 'required',
            'supervisor_position' => 'required',
            'internship_start_date' => 'required|date',
            'internship_website' => 'present',
            'description' => 'nullable',
            // 'reports' => 'required|array',
            // 'reports.*.id' => 'present',
            // 'reports.*.date' => 'required|date',
            // 'reports.*.desc' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        //->with(['WeeklyReport'])
        $student = Student::where('student_number', $req->student_number)->first();
        $form2 = Form2s::where('student_id', $student->id)->first();
        if ($form2 == null) {
            return response()->json([
                'message' => 'این دانشجو توسط سرپرستی ثبت نام نشده است',
            ], 400);
        }
        $ind_id = IndustrySupervisor::where('user_id', auth()->user()->id)->first()->id;
        if (!$ind_id) {
            return response()->json([
                'error' => 'این سرپرست یافت نشد. '
            ], 400);
        }
        $form2->industry_supervisor_id = $ind_id;
        $form2->student_id = Student::where('student_number', $req->student_number)->first()->id;
        // !! fix later, dry | theres two search in this page, one in form2 where student, and second is user where
        $form2->schedule_table = $req->schedule_table;
        $form2->introduction_letter_number = $req->introduction_letter_number;
        $form2->introduction_letter_date = $req->introduction_letter_date;
        $form2->internship_department = $req->internship_department;
        $form2->supervisor_position = $req->supervisor_position;
        $form2->internship_started_at = $req->internship_start_date;
        $form2->internship_website = $req->internship_website;
        $form2->description = $req->description;
        $form2->schedule_table = $req->schedule_table;
        $form2->save();
        // # update reports
        // array to store all of reports id to delete ones who ind. supervisor deleted
        // $industrySupervisorReports = [];
        // foreach ($req->reports as $report) {
        //     $newReport = Report::updateOrCreate(
        //         ['id' => $report['id']],
        //         ['form2_id' => $form2->id, 'date' => $report['date'], 'description' => $report['desc']],
        //     );
        //     array_push($industrySupervisorReports, $newReport->id);
        // }
        // // delete the reports where ind. supervisor deleted
        // Report::where('form2_id', $form2->id)->whereNotIn('id', $industrySupervisorReports)->delete();
        // // set the reports attr. of the weeklyReports table for this student
        // // check if schedule duration is more than 0
        // $allWorkingDaysDate = $student->calculateAllWorkingDaysDate();
        // if ($allWorkingDaysDate == 0) {
        //     return response()->json([
        //         'message' => 'لطفا برنامه ی معتبری را وارد کنید',
        //     ], 400);
        // }
        // WeeklyReport::updateOrCreate(
        //     ['student_id' => $student->id],
        //     [
        //         'reports' => $allWorkingDaysDate
        //     ]
        // );
        return response()->json(['message' => 'اطلاعات دانشجو با موفقیت ویرایش شد']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student = Student::where("student_number", $id)->first();
        if ($student == null) {
            return response()->json([
                'message' => 'این دانشجو وجود ندارد',
            ], 400);
        }
        $form2 = Form2s::where('student_id', $student->id)->first();
        if ($form2 == null) {
            return response()->json([
                'message' => 'این دانشجو توسط سرپرستی ثبت نام نشده است',
            ], 400);
        }
        // TODO: need to remove all saved info when was submitting by industry supervisor
        $form2->delete();
        $student->supervisor_id = null;
        $student->save();
        // $student->weeklyReport->delete();
        return response()->json([
            'message' => 'دانشجو با موفقیت حذف شد',
        ]);
    }
    public function checkStudent(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'student_number' => 'required|numeric',
            'national_code' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        $user = User::where('national_code', $req->national_code)->first();
        if ($user == null) {
            return response()->json([
                'message' => 'دانشجویی با اطلاعات وارد شده یافت نشد'
            ], 404);
        }
        $user->student->where('student_number', $req->student_number)->first();
        if ($user == null) {
            return response()->json([
                'message' => 'دانشجویی با اطلاعات وارد شده یافت نشد'
            ], 404);
        }
        return new CheckStudent($user);
    }
    public function industrySupervisorEvaluateStudentGET(Request $req)
    {
        // ! not optimized queries
        $validator = Validator::make($req->all(), [
            'student_number' => 'required|exists:students,student_number',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        $ind_id = IndustrySupervisor::where('user_id', auth()->user()->id)->first()->id;
        if (!$ind_id) {
            return response()->json([
                'error' => 'این سرپرست یافت نشد. '
            ], 400);
        }
        $student = Student::where('student_number', $req->student_number)->where('supervisor_id', $ind_id)->first();
        if ($student == null) {
            return response()->json([
                'message' => 'سرپرست در صنعت کارآموزی این دانشجو شما نیستید'
            ], 400);
        }
        $term_id = Term::where('start_date', '<=', now())->where('end_date', '>=', now())->first()->id;
        //->where('term_id',$term_id)
        $setudent_evaluations = StudentEvaluation::where('student_id', $student->id)->get();
        $evaluate = [];
        foreach ($setudent_evaluations as $evl) {
            array_push($evaluate, [
                'option_id' => $evl->option_id,
                'value' => $evl->value
            ]);
        }
        return response()->json([
            'data' => [
                'options' => Option::where('type', 'industry_supervisor_evaluation')->get(),
                'student' => new StudentResource(Student::where('student_number', $req->student_number)->with('user')->first()),
                'student_evaluations' => isset($student->studentEvaluations) ? $evaluate : [],
                'internship_finish_date' => $student->internship_finished_at ? $student->internship_finished_at : null,
            ]
        ], 200);
    }
    public function industrySupervisorEvaluateStudent(Request $req)
    {

        // return $req;
        // ! note: industry supervisor submits evaluations about a student
        // ! FIX: two queries for same purpose!
        // ! FIX: internship_finish_date doesn't have a column on student table
        $validator = Validator::make($req->all(), [
            'student_number' => 'required|exists:students,student_number',
            'internship_finish_date' => 'required|date',
            'data' => 'required|array|max:20',
            // max 20 items
            'data.*.id' => 'required|exists:options,id',
            'data.*.value' => 'required',
        ], [
            'data.*.id.exists' => 'این مورد ارزیابی در دیتابیس موجود نیست. لطفا صفحه را رفرش کنید',
            'data.*.value.required' => 'مقدار value برای هر آیتم مورد ارزیابی مورد نیاز است',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        $term_id = Term::where('start_date', '<=', now())->where('end_date', '>=', now())->first()->id;
        $ind_id = IndustrySupervisor::where('user_id', auth()->user()->id)->first()->id;
        if (!$ind_id) {
            return response()->json([
                'error' => 'این سرپرست یافت نشد. '
            ], 400);
        }
        $student = Student::where('student_number', $req->student_number)->where('supervisor_id', $ind_id)->first();
        if ($student == null) {
            return response()->json([
                'message' => 'سرپرست در صنعت کارآموزی این دانشجو شما نیستید'
            ], 400);
        }
        $student->internship_finished_at = $req->internship_finish_date;
        $evaluations = [0.5, 1.5, 2, 2.5];
        $grade = 0;
        // ! previous implementation: save all evaluation in a text column and retrieve it as a json file
        // ! fix: delete evaluation column in students table
        // ! new implementation: save it on another table
        foreach ($req->data as $evaluation) {
            $grade += $evaluations[$evaluation['value'] - 1];
            StudentEvaluation::create([
                'student_id' => $student->id,
                'option_id' => $evaluation['id'],
                'value' => $evaluation['value'],
            ]);
        }
        Form3s::create([
            'student_id' => $student->id,
            'grade' => $grade,
            'verified' => 1,
            'term_id' => $term_id
        ]);
        // $student->internship_finished_at = $req->internship_finished_at;
        // $student->internship_status = 3;
        // $student->evaluations_verified = 1;
        $term_id = Term::where('start_date', '<=', now())->where('end_date', '>=', now())->first()->id;
        Form7::create([
            'student_id' => $student->id,
            'term_id' => $term_id,
            // 'letter_date' => now(),
            // 'letter_number' => "1/$student->id 3 $student->id ب",
            // 'supervisor_approval' => 1,
            'verify_industry_collage' => 1
        ]);
        $student->stage = 3;
        $student->save();
        return [
            'message' => 'عملیات با موفقیت انجام شد',
        ];
    }
    public function submitCheckedStudent(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'student_number' => 'required|exists:students,student_number',
            'national_code' => 'required|exists:users,national_code',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        $user = User::where('national_code', $req->national_code)->first();
        if ($user == null) {
            return response()->json([
                'message' => 'دانشجویی با چنین شماره دانشجویی یافت نشد'
            ], 404);
        }
        $student = $user->student->where('student_number', $req->student_number)->first();
        if ($student == null) {
            return response()->json([
                'message' => 'دانشجویی با اطلاعات وارد شده یافت نشد'
            ], 404);
        }
        if ($student->supervisor_id !== null) {
            return response()->json([
                'message' => 'این دانشجو از قبل سرپرست دارد',
            ], 400);
        }
        // ! DRY with check student function
        $student->supervisor_id = auth()->id();
        $student->save();
        return response()->json([
            'message' => 'دانشجو با موفقیت اضافه شد',
        ]);
    }
}
