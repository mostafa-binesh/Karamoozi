<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Form2s;
use App\Models\Report;
use App\Models\Options;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Resources\pashm;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\StudentResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserPaginationResource;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use App\Http\Resources\IndustrySupervisorStudentsList;
use App\Http\Resources\IndustrySupervisor\CheckStudent;
use App\Http\Resources\IndustrySupervisor\IndustrySupervisorsStudent;
use App\Models\StudentEvaluation;
use App\Models\WeeklyReport;

class IndustrySupervisorStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        // return Student::find(1)->user;
        // return Student::all()->filter($req->all())->get();
        return auth()->user()->industrySupervisor->industrySupervisorStudents()->filter($req->all())->cpagination($req, pashm::class); //->get();
        $h = auth()->user()->industrySupervisor->industrySupervisorStudents()->paginate(5);
        // return $hgf;
        return new IndustrySupervisorStudentsList(auth()->user()->industrySupervisor->industrySupervisorStudents()->paginate(5));
        // $x = auth()->user()->industrySupervisor->industrySupervisorStudents()->cpagination($req);
        // $x = auth()->user()->industrySupervisor->industrySupervisorStudents()->cpagination($req, pashm::class);
        // // return $asdas;
        // return $x;
        // $x = auth()->user()->industrySupervisor->industrySupervisorStudents;
        // return new UserPaginationResource($x);
        return new pashm($x);
        return IndustrySupervisorStudentsList::collection(auth()->user()->industrySupervisor->industrySupervisorStudents->paginate(5));


        // return new  IndustrySupervisorStudentsList(auth()->user()->industrySupervisor->industrySupervisorStudents()->paginate(2));
        // return new  IndustrySupervisorStudentsList(Student::paginate(1));
        // return 1;
        // return Student::jsonPaginate();

        // return new IndustrySupervisorStudentsList(Student::find(1),$req);
        return Student::cpagination($req);
        return Student::cpagination($req)[0];
        return Student::cpagination($req)[1];

        $student = Student::paginate(2);
        return response()->json([
            'current_page' => $student->currentPage(),
            'total_page' => $student->total(),
            'data' => $student->collection(),
        ]);
        return $student->currentPage();
        // return IndustrySupervisorStudentsList::collection();
        return response()->json(Student::paginate(5));

        // return IndustrySupervisorStudentsList::collection(User::paginate(1));

        return Student::paginate(3);
        // $data->current_page = 100;
        return response()->json($data, 200);
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
            'internship_website' => 'present',
            'description' => 'nullable',
            'schedule_table' => 'sometimes|array|size:6',
            'reports' => 'required|array',
            'reports.*.date' => 'required|date',
            'reports.*.desc' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        // return $req;
        $form2 = Form2s::where('student_id', Student::where('student_number', $req->student_number)->first()->id)->first();
        if ($form2 != null) {
            return response()->json([
                'message' => 'اطلاعات این دانشجو قبلا ثبت شده است',
            ], 404);
        }
        $form2 = Form2s::create([
            'industry_supervisor_id' => auth()->user()->id,
            'student_id' => User::where('national_code', $req->national_code)->firstorfail()->student->where('student_number', $req->student_number)->first()->id ?? abort(404),
            // ! fix later, dry
            'schedule_table' => $req->schedule_table,
            'introduction_letter_number' => $req->introduction_letter_number,
            'introduction_letter_date' => $req->introduction_letter_date,
            'internship_department' => $req->internship_department,
            'supervisor_position' => $req->supervisor_position,
            'internship_start_date' => $req->internship_start_date,
            'internship_website' => $req->internship_website,
            'description' => $req->description,
            'schedule_table' => $req->schedule_table ?? null,
        ]);
        $student = Student::where("student_number", $req->student_number)->first();
        $student->supervisor_id = Auth::id();
        $student->unevaluate();
        $student->save();
        // submit reports
        foreach ($req->reports as $report) {
            $result = Report::create([
                'form2_id' => $form2->id,
                'date' => $report['date'],
                'description' => $report['desc'],
            ]);
        }
        // set the reports attr. of the weeklyReports table for this student
        $studentWeeklyReport = $student->weeklyReport;
        // !
        // WeeklyReport::create([
        //     'student_id' => $student->id,
        //     'reports' => $student->calculateAllWorkingDaysDate()
        // ]);
        WeeklyReport::updateOrCreate(
            ['student_id' => $student->id],
            [
                'reports' => $student->calculateAllWorkingDaysDate()
            ]
        );
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
            // 'national_code' => 'required|exists:users,national_code',
            'introduction_letter_number' => 'required',
            'introduction_letter_date' => 'required|date',
            'internship_department' => 'required',
            'supervisor_position' => 'required',
            'internship_start_date' => 'required|date',
            'internship_website' => 'present',
            'description' => 'nullable',
            // 'schedule_table' => 'required|array|size:6',
            'reports' => 'required|array',
            'reports.*.id' => 'present',
            'reports.*.date' => 'required|date',
            'reports.*.desc' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        // return $req;
        $student = Student::where('student_number', $req->student_number)->first();
        $form2 = Form2s::where('student_id', $student->id)->first();
        if ($form2 == null) {
            return response()->json([
                'message' => 'این دانشجو توسط سرپرستی ثبت نام نشده است',
            ], 400);
        }
        $form2->industry_supervisor_id = auth()->user()->id;
        // $form2->student_id = User::where('national_code', $req->national_code)->firstorfail()->student->where('student_number', $req->student_number)->first()->id ?? abort(404);
        $form2->student_id = Student::where('student_number', $req->student_number)->first()->id;
        // !! fix later, dry | theres two search in this page, one in form2 where student, and second is user where
        $form2->schedule_table = $req->schedule_table;
        $form2->introduction_letter_number = $req->introduction_letter_number;
        $form2->introduction_letter_date = $req->introduction_letter_date;
        $form2->internship_department = $req->internship_department;
        $form2->supervisor_position = $req->supervisor_position;
        $form2->internship_start_date = $req->internship_start_date;
        $form2->internship_website = $req->internship_website;
        $form2->description = $req->description;
        $form2->schedule_table = $req->schedule_table;
        $form2->save();
        // # update reports
        // array to store all of reports id to delete ones who ind. supervisor deleted
        $industrySupervisorReports = [];
        foreach ($req->reports as $report) {
            $id =  Report::updateOrCreate(
                ['id' => $report['id']],
                ['form2_id' => $form2->id, 'date' => $report['date'], 'description' => $report['desc']],
            );
            array_push($industrySupervisorReports, $id->id);
        }
        // delete the reports where ind. supervisor deleted
        Report::where('form2_id', $form2->id)->whereNotIn('id', $industrySupervisorReports)->delete();
        // set the reports attr. of the weeklyReports table for this student
        $studentWeeklyReport = $student->weeklyReport;
        $studentWeeklyReport->reports = $student->calculateAllWorkingDaysDate();
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
        $student->weeklyReport->delete();
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
        // $user = User::where('national_code',$req->national_code)->firstorfail();
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
        $student = Student::where('student_number', $req->student_number)->where('supervisor_id', auth()->id())->first();
        if ($student == null) {
            return response()->json([
                'message' => 'سرپرست در صنعت کارآموزی این دانشجو شما نیستید'
            ], 400);
        }
        return response()->json([
            'data' => [
                'options' => Options::where('type', 'industry_supervisor_evaluation')->get(),
                'student' => new StudentResource(Student::where('student_number', $req->student_number)->with('user')->first()),
                // 'student' => Student::where('student_number', $req->student_number)->first(),
            ]
        ], 200);
    }
    public function industrySupervisorEvaluateStudent(Request $req)
    {
        // ! note: industry supervisor submits evaluations about a student
        // ! FIX: two queries for same purpose!
        // ! FIX: internship_finish_date doesn't have a column on student table
        $validator = Validator::make($req->all(), [
            // 'data' => 'required|array|size:8',
            'student_number' => 'required|exists:students,student_number',
            'internship_finish_date' => 'required|date',
            'data' => 'required|array|max:20', // max 20 items
            'data.*.id' => 'required|exists:options,id',
            'data.*.value' => 'required',
        ], [
            // 'data.*.id.exists' => 'مقدار id برای هر آیتم مورد ارزیابی مورد نیاز است',
            'data.*.id.exists' => 'این مورد ارزیابی در دیتابیس موجود نیست. لطفا صفحه را رفرش کنید',
            'data.*.value.required' => 'مقدار value برای هر آیتم مورد ارزیابی مورد نیاز است',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        $student = Student::where('student_number', $req->student_number)->where('supervisor_id', auth()->id())->first();
        if ($student == null) {
            return response()->json([
                'message' => 'سرپرست در صنعت کارآموزی این دانشجو شما نیستید'
            ], 400);
        }
        // ! previous implementation: save all evaluation in a text column and retrieve it as a json file
        // $student->evaluations = $req->data;
        // ! fix: delete evaluation column in students table
        // ! new implementation: save it on another table
        foreach ($req->data as $evaluation) {
            StudentEvaluation::create([
                'student_id' => $student->id,
                'option_id' => $evaluation['id'],
                'value' => $evaluation['value'],
            ]);
        }
        $student->internship_finished_at = $req->internship_finished_at;
        $student->internship_status = 3;
        $student->evaluation_verified = 1;
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
        // $user = User::where('national_code',$req->national_code)->firstorfail();
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
