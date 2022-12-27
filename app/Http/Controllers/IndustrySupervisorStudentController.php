<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\IndustrySupervisorStudentsList;
use App\Http\Resources\IndustrySupervisor\CheckStudent;
use App\Http\Resources\pashm;
use App\Http\Resources\StudentResource;
use App\Http\Resources\UserPaginationResource;
use App\Models\form2s;
use App\Models\Options;
use Symfony\Component\HttpKernel\Event\RequestEvent;

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
        $a = auth()->user()->industrySupervisor->industrySupervisorStudents()->filter($req->all())->cpagination($req, pashm::class); //->get();
        return $a;
        return $b;
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
        $validator = Validator::make($req->all(), [
            'student_number' => 'required|exists:students,student_number',
            'national_code' => 'required|exists:users,national_code',
            'introduction_letter_number' => 'required',
            'introduction_letter_date' => 'required|date',
            'internship_department' => 'required',
            'supervisor_position' => 'required',
            'internship_start_date' => 'required|date',
            'internship_website' => 'required',
            'description' => 'nullable',
            // 'schedule_table' => 'required|array|size:6',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
                // 'message' => 'دانشجویی با اطلاعات وارد شده یافت نشد'
            ], 400);
        }
        // return $req;
        $form2 = form2s::where('student_id', Student::where('student_number', $req->student_number)->first()->id)->first();
        if ($form2 != null) {
            return response()->json([
                'message' => 'این دانشجو قبلا توسط یک سرپرست ثبت نام شده است',
            ], 400);
        }
        $form2 = form2s::create([
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
            'schedule_table' => $req->schedule_table,
        ]);
        return response()->json(['message' => 'دانشجو با موفقیت ثبت شد', 'student' => $form2]);
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
        return response()->json(['id' => $id]);
        
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
            'national_code' => 'required|exists:users,national_code',
            'introduction_letter_number' => 'required',
            'introduction_letter_date' => 'required|date',
            'internship_department' => 'required',
            'supervisor_position' => 'required',
            'internship_start_date' => 'required|date',
            'internship_website' => 'required',
            'description' => 'nullable',
            // 'schedule_table' => 'required|array|size:6',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
                // 'message' => 'دانشجویی با اطلاعات وارد شده یافت نشد'
            ], 400);
        }
        // return $req;
        $form2 = form2s::where('student_id', Student::where('student_number', $req->student_number)->first()->id)->first();
        if ($form2 == null) {
            return response()->json([
                'message' => 'این دانشجو توسط سرپرستی ثبت نام نشده است',
            ], 400);
        }
        $form2->industry_supervisor_id = auth()->user()->id;
        $form2->student_id = User::where('national_code', $req->national_code)->firstorfail()->student->where('student_number', $req->student_number)->first()->id ?? abort(404);
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
        return response()->json(['message' => 'اطلاعات دانشجو با موفقیت ویرایش شد', 'student' => $form2]);
        return $form2;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $form2 = form2s::where('student_id', Student::where('student_number', $id)->first()->id)->first();
        if ($form2 == null) {
            return response()->json([
                'message' => 'این دانشجو توسط سرپرستی ثبت نام نشده است',
            ], 400);
        }
        $form2->delete();
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
                // 'message' => 'دانشجویی با اطلاعات وارد شده یافت نشد'
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
        // return response()->json([
        //     // 'data' => User::where('national_code',$req->national_code)->student->where('student_code')->first(),
        //     // 'data' => User::where('national_code',$req->national_code)->first()->student->where('student_number',$req->student_number)->first(),
        //     'data' => $user
        // ]);
        return new CheckStudent($user);
    }
    public function industrySupervisorEvaluateStudentGET(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'student_number' => 'required|exists:students,student_number',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
                // 'message' => 'دانشجویی با اطلاعات وارد شده یافت نشد'
            ], 400);
        }
        // return 1;
        $student = Student::where('student_number', $req->student_number)->where('supervisor_id', auth()->user()->industrySupervisor->id)->first();
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
        // ! FIX: two queries for same purpose!
        // ! FIX: internship_finish_date doesn't have a column on student table
        // ! FIX: array id should be checked, 
        // ! FIX: internship evaluation should be done only ONCE 
        $validator = Validator::make($req->all(), [
            // 'data' => 'required|array|size:8',
            'student_number' => 'required|exists:students,student_number',
            'internship_finish_date' => 'required|date',
            'data' => 'required|array|max:20', // max 20 items
            'data.*.id' => 'required|exists:options,id',
            'data.*.value' => 'required',
        ], [
            // for advanced users only
            // 'data.*.id.exists' => 'مقدار id برای هر آیتم مورد ارزیابی مورد نیاز است',
            'data.*.id.exists' => 'این مورد ارزیابی در دیتابیس موجود نیست. لطفا صفحه را رفرش کنید',
            'data.*.value.required' => 'مقدار value برای هر آیتم مورد ارزیابی مورد نیاز است',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        $student = Student::where('student_number', $req->student_number)->where('supervisor_id', auth()->user()->industrySupervisor->id)->first();
        // dd($student);
        if ($student == null) {
            return response()->json([
                'message' => 'سرپرست در صنعت کارآموزی این دانشجو شما نیستید'
            ], 400);
        } else if ($student->evaluated()) {
            return response()->json([
                'message' => 'این دانشجو قبلا ارزیابی شده است'
            ], 400);
        } else if ($student->readyToEvaluate()) {
            return response()->json([
                'message' => 'ارزشیابی دانشجو هنوز نمی تواند انجام شود'
            ], 400);
        }
        // return $student->internship_status;
        // $student->evaluations = implode(',', $req->data);
        $student->evaluations = $req->data;
        $student->internship_finished_at = $req->internship_finished_at;
        // $student->internship_status = 3;
        // $student->internship_status = 'به اتمام رسیده';
        $student->internship_status = 3;
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
                // 'message' => 'دانشجویی با اطلاعات وارد شده یافت نشد'
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
                'message' => 'این دانشجو سرپرست دارد',
            ], 400);
        }
        // ! DRY with check student function
        $student->supervisor_id = auth()->user()->industrySupervisor->id;
        $student->unevaluate();
        $student->save();
        return response()->json([
            'message' => 'دانشجو با موفقیت اضافه شد',
        ]);
    }
}