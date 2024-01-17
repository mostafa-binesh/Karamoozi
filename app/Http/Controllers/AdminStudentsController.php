<?php

namespace App\Http\Controllers;

use App\Enums\PreRegVerificationStatusEnum;
use App\Enums\VerificationStatusEnum;
use App\Models\User;
use App\Models\Report;
use App\Models\Student;
use Illuminate\Http\Request;
use Hekmatinasser\Verta\Verta;
use App\Models\CompanyEvaluation;
use App\Models\University_faculty;
use App\ModelFilters\StudentFilter;
use App\Http\Resources\PreRegStudents;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\admin\StudentForm2;
use App\Http\Resources\admin\StudentForm3;
use App\ModelFilters\Admin\StudentsFilter;
use App\Http\Resources\WeeklyReportResource;
use App\Http\Resources\admin\StudentFormsStatus;
use App\Http\Resources\InitRegistrationStudents;
use App\ModelFilters\Admin\PreRegStudentsFilter;
use App\Http\Resources\UniversityFacultyResource;
use App\ModelFilters\Admin\InitRegStudentsFilter;
use App\Http\Resources\admin\StudentForm4Resource;
use App\Http\Resources\admin\StudentPreRegDescription;
use App\Http\Resources\admin\CompanyEvaluationResource;
use App\Http\Resources\admin\FinishInternshipLetterResource;
use Illuminate\Support\Facades\Auth;

class AdminStudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
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
    public function studentsHomePage()
    {
        $students = Student::all();
        // ! i handled the counters in backend not the database, i guess     way is faster
        // initial registration
        $init_unVerified = 0;
        $init_verified = 0;
        $init_waiting = 0;
        // pre reg students
        $preReg_unVerified = 0;
        $preReg_verified = 0;
        $preReg_waiting = 0;
        foreach ($students as $student) {
            if ($student->verified == 2) {
                $init_verified++;
            } else if ($student->verified == 3) {
                $init_unVerified++;
            } else if ($student->verified == 1) {
                $init_waiting++;
            }
            if ($student->pre_reg_verified == PreRegVerificationStatusEnum::AdminApproved) {
                $preReg_verified++;
            } else if ($student->pre_reg_verified == PreRegVerificationStatusEnum::AdminRefused) {
                $preReg_unVerified++;
            }
            //else if ($student->pre_reg_verified == PreRegVerificationStatusEnum::AdminNotChecked) {
            //  $preReg_waiting++;
            //}
        }
        return response()->json([
            'data' => [
                'counters' => [
                    'initReg_verified' => $init_verified,
                    'initReg_unverified' => $init_unVerified,
                    'preReg_verified' => $preReg_verified,
                    'preReg_unverified' => $preReg_unVerified,
                ],
            ]
        ]);
    }
    public function initialRegistrationStudents(Request $req)
    {
        // $students = Student::filter($req->all(), InitRegStudentsFilter::class)->where('verified', false)->with('user')->cpagination($req, InitRegistrationStudents::class);
        $students = Student::filter($req->all(), InitRegStudentsFilter::class)->with('user')->cpagination($req, InitRegistrationStudents::class);
        return response()->json([
            'meta' => $students['meta'],
            'data' => [
                'students' => $students['data'],
            ]
        ]);
    }
    public function entrance_years()
    {
        return Student::select('entrance_year')->distinct('entrance_year')->get();
    }
    public function preRegStudents(Request $req)
    {
        $students = Student::filter($req->all(), PreRegStudentsFilter::class)
            // only search for students where approved by their masters
            // ->where('pre_reg_verified', PreRegVerificationStatusEnum::MasterApproved)
            ->with(['user', 'universityFaculty'])->cpagination($req, PreRegStudents::class);
        return response()->json([
            'meta' => $students['meta'],
            'data' => [
                'students' => $students['data'],
            ]
        ]);
    }
    public function faculty()
    {
        return UniversityFacultyResource::collection(University_faculty::all());
    }
    public function forms(Request $req)
    {
        // ! inja preRegStudentsFilter nabayad pre_reg_verified filter dashte bashe
        $students = Student::whereHas("form2")->with(['user', 'universityFaculty', 'company'])->cpagination($req, PreRegStudents::class);
        return response()->json([
            'meta' => $students['meta'],
            'data' => [
                'faculties' => UniversityFacultyResource::collection(University_faculty::all()),
                'students' => $students['data'],
            ]
        ]);
        return $students;
    }
    public function initRegVerifyStudent($id)
    {
        $student = Student::findorfail($id);
        $student->verified = 2; // 1: approved
        $student->init_reg_rejection_reason = null;
        $student->save();
        return response()->json([
            'message' => 'دانشجو تایید شد'
        ], 200);
    }
    public function initRegUnVerifyStudent($id, Request $req)
    {
        $validator = Validator::make($req->all(), [
            'rejection_reason' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        $student = Student::findorfail($id);
        $student->verified = 3; // 3: denied
        $student->init_reg_rejection_reason = $req->rejection_reason;
        $student->stage = 1;
        $student->save();
        return response()->json([
            'message' => 'دانشجو رد شد'
        ], 200);
    }
    public function initRegDesc($id)
    {
        $student = Student::findorfail($id);
        return response()->json([
            'message' => $student->init_reg_rejection_reason,
        ]);
    }
    public function preRegVerifyStudent($id)
    {
        $student = Student::findorfail($id);
        $student->pre_reg_verified = PreRegVerificationStatusEnum::Verified;
        $student->pre_reg_rejection_reason = null;
        $student->save();
        return response()->json([
            'message' => 'پیش ثبت نام تایید شد'
        ], 200);
    }
    public function preRegUnVerifyStudent($id, Request $req)
    {
        $validator = Validator::make($req->all(), [
            'rejection_reason' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        $student = Student::findorfail($id);
        $student->pre_reg_verified = PreRegVerificationStatusEnum::AdminRefused;
        $student->pre_reg_rejection_reason = $req->rejection_reason;
        $student->stage = 1;
        $student->save();
        return response()->json([
            'message' => 'پیش ثبت نام رد شد'
        ], 200);
    }
    public function preRegDesc($id)
    {
        $student = Student::findorfail($id);
        return StudentPreRegDescription::make($student);
    }
    public function rejectionDescription($id)
    {
        $student = Student::findorfail($id);
        return response()->json([
            'message' => $student->pre_reg_rejection_reason,
        ]);
    }
    public function studentForms($id)
    {
        // ! not completed yet
        // ! need to add other forms, now just form2nd has been added
        $student = Student::where("id", $id)->with(['form2', 'user', 'studentEvaluations'])->first();
        return StudentFormsStatus::make($student);
        // return $student;
    }
    // ######################################
    // ############## FORM2 #####################
    // ######################################
    public function form2($id)
    {
        $student = Student::where("id", $id)->with(["form2"])->first();
        return StudentForm2::make($student);
        // return $student;
    }
    public function form2Verify($id)
    {
        // ! دقت کنید
        // ! در صورتی که فرم 2 یک دانشجو تایید شود، دانشجو می تواند به مرحله ی بعدی رفته و شروع به پر کردن گزارش های هفتگی خود کند
        $student = Student::findorfail($id);
        $student->stage = 2;
        $student->save();
        $student->form2->verified = 2; // verified
        $student->form2->rejection_reason = null;
        $student->form2->save();
        return response()->json([
            'message' => 'فرم تایید شد| وضعیت 2',
        ]);
    }
    public function form2unVerify($StudentID, Request $req)
    {
        $validator = Validator::make($req->all(), [
            'rejection_reason' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }
        $student = Student::findorfail($StudentID);
        $student->form2->verified = 3;
        $student->form2->rejection_reason = $req->rejection_reason;
        $student->form2->save();
        $student->stage = 1;
        $student->save();
        return response()->json([
            'message' => 'فرم رد شد | وضعیت 3',
        ]);
    }
    public function form3($id)
    {
        $user = Auth::user();
        if ($user->hasAnyRole(['master'])) {
            $student = Student::where("id", $id)->with("studentEvaluations")->first();
            if ($student->professor_id != $user->id) {
                return response()->json([
                    'error' => 'این دانشجو با شما این درس را اخذ نکرده است( در این ترم)'
                ], 400);
            }
            return StudentForm3::make($student);
        }
        $student = Student::where("id", $id)->with("studentEvaluations")->first();
        // return $student->studentEvaluations;
        return StudentForm3::make($student);
    }
    public function form3Verify($id)
    {
        $student = Student::where("id", $id)->first();
        $student->evaluations_verified = 2;
        $student->save();
        return response()->json([
            'message' => 'فرم تایید شد',
        ]);
    }
    public function form3UnVerify($id)
    {
        $student = Student::where("id", $id)->first();
        $student->evaluations_verified = 3;
        $student->save();
        return response()->json([
            'message' => 'فرم رد شد',
        ]);
    }
    public function form4($id)
    {
        // $companyEvaluations = CompanyEvaluation::where('student_id', $id)->with('option')->get();
        $student = Student::findorfail($id)->with('user')->first();
        return StudentForm4Resource::make($student);
    }
    public function form4Verify($id)
    {
        $student = Student::where("id", $id)->first();
        $student->form4_verified = 2;
        $student->save();
        return response()->json([
            'message' => 'فرم تایید شد',
        ]);
    }
    public function form4UnVerify($id)
    {
        $student = Student::where("id", $id)->first();
        $student->form4_verified = 3;
        $student->save();
        return response()->json([
            'message' => 'فرم رد شد',
        ]);
    }
    public function weeklyReports($id)
    {
        $student = Student::where("id", $id)->first();
        // counters
        $weeks = [];
        $status0 = $status1 = $status2 = $status3 = 0;
        $groupedByWeeklyReports = $student->weeklyReports->groupBy('week_number');
        // ! put this foreach in Weeklyreportresource
        foreach ($groupedByWeeklyReports as $weekNumber => $weeklyReports) {
            // $weekIsFinished = ($weeklyReport->status == VerificationStatusEnum::Approved);
            $weekIsFinished = true;
            foreach ($weeklyReports as $weeklyReport) {
                $firstDayOfTheWeek = $weeklyReport->date->startOfWeek()->startOfDay();
                $weekStatus = $weeklyReport->status;
                if ($weekStatus != VerificationStatusEnum::Approved) {
                    $weekIsFinished = false;
                }
                switch ($weekStatus->value) {
                    case 0:
                        $status0++;
                        break;
                    case 1:
                        $status1++;
                        break;
                    case 2:
                        $status2++;
                        break;
                    case 3:
                        $status3++;
                        break;
                    default:
                        # code...
                        break;
                }
            }
        }
        array_push($weeks, [
            'id' => $weekNumber + 1,
            'first_day_of_week' => $firstDayOfTheWeek,
            'status' => $weekIsFinished,
            'not_available' => $status0,
            'not_checked' => $status1,
            'rejected' => $status2,
            'accepted' => $status3,
        ]);
        return [
            'data' => [
                'weeks' => $weeks,
                'student' => [
                    'id' => $student->id,
                    'first_name' => $student->user->first_name,
                    'last_name' => $student->user->last_name,
                    'faculty_name' => $student->facultyName(),
                    'student_number' => $student->student_number,
                    'internship_start_date' => $student->form2->internship_started_at,
                    'internship_finish_date' => $student->form2->internship_finished_at,
                ],
                'company' => [
                    // ! i guess there are some problems with CompanyName function
                    'name' => $student->companyName(),
                    'type' => $student->company->companyType(),
                    'phone_number' => $student->company->company_phone,
                    'postal_code' => $student->company->company_postal_code,
                    'address' => $student->company->company_address,
                ],
                'industry_supervisor' => [
                    'full_name' => $student->industrySupervisor->user->fullName,
                    'position' => $student->form2->supervisor_position,
                ],
            ]
        ];
    }
    public function showWeeklyReport($id, $weekID)
    { // arguments: studentID, weekID
        $student = Student::findorfail($id)->first();
        // dd($student);
        $dates = [];
        foreach ($student->weeklyReport['reports'][$weekID - 1]['days'] as $day) {
            array_push($dates, $day['date']);
        }
        // return($student->weeklyReport['reports'][0]['days']);
        // return(Report::where('student_id', $id)->whereIn('date', $dates)->get(['date', 'description']));
        // return($student->weeklyReport['reports'][$weekID - 1]);
        return [
            'data' =>
            [
                'reports' => Report::where('student_id', $id)->whereIn('date', $dates)->get(['date', 'description']),
                'dates_debugOnly' => $dates,
                'week_id_debugOnly' => $student->weeklyReport['reports'][$weekID - 1],
                'student_id_debugOnly' => $id,
            ]
        ];
    }
    // return $days;
    public function finishInternship($id)
    {
        $student = Student::where("id", $id)->with(["form2", 'user'])->first();
        return FinishInternshipLetterResource::make($student);
    }
}
