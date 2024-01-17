<?php

namespace App\Models;

use App\Enums\PreRegVerificationStatusEnum;
use App\Enums\VerificationStatusEnum;
use App\Traits\EnumTrait;
use EloquentFilter\Filterable;
use App\Traits\CPaginationTrait;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Http\Resources\WeeklyReportResource;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Model
{
    use HasFactory, Notifiable, EnumTrait, Filterable;
    use CPaginationTrait;
    protected $casts = [
        'evaluations' => 'array',
        'pre_reg_done' => 'boolean',
        // 'passed_units' => 'int',
        'student_number' => 'int',
        'stage' => 'int',
        'pre_reg_verified' => PreRegVerificationStatusEnum::class,
        // 'verified' => 'integer',
    ];
    /**
     * The attributes that are enum, these are using EnumTrait.
     *
     * @var array
     */
    // it uses EnumTrait
    protected static $enums = [
        'ROLES' => 'role',
        'INTERNSHIP_STATUS' => 'internship_status',
    ];
    /**
     * Users' roles
     *
     * @var array
     */
    public const ROLES = [
        1 => 'admin',
        2 => 'author'
    ];
    public const SEMESTER = [
        1 => 'نیم سال اول',
        2 => 'نیم سال دوم'
    ];
    // maghate' tahsili
    public const DEGREE = [
        1 => 'کارشناسی',
        2 => 'کارشناسی ارشد'
    ];
    public const INTERNSHIP_TYPE = [
        0 => 'کار آموزی در صنعت و شرکت خصوصی',
        1 => 'کارآموزی در صنعت و شرکت دولتی',
        2 => 'کارآموزی در بخش it',
        3 => 'کارآموزی در دانشکده',
        4 => 'کارآموزی در آزمایشگاه پژوهشی و امور پژوهشی ( ارسال پروپوزال )',
    ];
    public const INTERNSHIP_STATUS = [
        1 => 'شروع نشده',
        2 => 'در حال اجرا',
        3 => 'به اتمام رسیده',
    ];
    public const DAYSOFWEEK = [
        0 => 'شنبه',
        1 => 'یکشنبه',
        2 => 'دوشنبه',
        3 => 'سه شنبه',
        4 => 'چهار شنبه',
        5 => 'پنج شنبه',
        6 => 'جمعه',
    ];
    public const VERIFIED = [
        0 => 'موجود نیست',
        1 => 'بررسی نشده',
        2 => 'تایید شده',
        3 => 'رد شده',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];
    // ###############################################
    // ################## RELATIONSHIPS ###################
    // ###############################################
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function form2()
    {
        return $this->hasOne(Form2s::class);
    }
    public function universityFaculty()
    {
        return $this->belongsTo(University_faculty::class, 'faculty_id', 'id');
    }
    public function industrySupervisor()
    {
        return $this->belongsTo(IndustrySupervisor::class, 'supervisor_id', 'id');
    }
    public function professor()
    {
        return $this->belongsTo(Employee::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function weeklyReport()
    {
        return $this->hasOne(WeeklyReport::class);
    }
    public function customCompany()
    {
        return $this->hasOne(Company::class);
    }
    // form3
    public function studentEvaluations()
    {
        return $this->hasMany(StudentEvaluation::class);
    }
    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    // ###############################################
    // ############# RELATION RELATED FUNCTIONS ###############
    // ###############################################
    public function facultyName()
    {
        // ! added optional because some of values been hardcoded in the database and may have some problems
        return optional($this->universityFaculty)->faculty_name;
    }
    public function professorName()
    {
        return $this->professor?->fullName;
    }
    public function companyName()
    {
        return optional($this->company)->company_name;
    }
    public function schedule()
    {
        return $this->form2->schedule_table;
    }
    public function indSupervisorReports()
    {
        // ! i guess there would be a good relation pattern for this one
        return Report::where('form2_id', $this->form2->id)->get();
    }
    // ###############################################
    // ##################  FUNCTIONS ###################
    // ###############################################
    public function statusArray()
    {
        return null;
    }
    public function industrySupervisorEvaluated()
    {
        // ! previous impl. :
        // return isEmpty($this->evaluations);
        // ! require new implementation
        return false;
    }
    public function scopeUnevaluated($query)
    {
        return $query->whereNull('evaluations');
    }
    public function evaluate()
    {
        $this->internship_status = 'به اتمام رسیده';
        $this->internship_finished_at = now();
        $this->save();
    }
    public function working()
    {
        $this->internship_status = 'در حال اجرا';
        $this->internship_finished_at = null;
        $this->save();
    }
    public function unevaluate()
    {
        $this->internship_status = 'شروع نشده';
        $this->internship_finished_at = null;
        $this->save();
    }
    // * i guess it would be better to the name of editable be isEditable
    public function editableAsIndSup()
    { // can be edited by industry supervisor or not
        return $this->form2?->verified != 2;
    }
    public function IndustrySupervisorVerified()
    {
        $form2 = Form2s::where('student_id', Auth::user()->student->id)->first();
        return $form2?->verified ?? VerificationStatusEnum::NotAvailable;
    }
    // calculate how many working hours per week student works based on schedule
    public function howManyDaysMustWork($schedule_table)
    {
        $total_duration = 0; // in seconds for a week
        foreach ($schedule_table as $row) {
            // return $row;
            $shifts = explode(',', $row);
            for ($i = 0; $i < count($shifts); $i += 2) {
                $start_time = strtotime($shifts[$i]);
                $end_time = strtotime($shifts[$i + 1]);
                $duration = $end_time - $start_time;
                $total_duration += $duration;
            }
        }
        if ($total_duration == 0) {
            return 0;
        }
        $total_hours = $total_duration / 3600;
        $totalInternshipDuration = 3600 * 240;
        $totalWorkingDaysCount = 0;
        while (true) {
            $thisWeekDuration = 0;
            foreach ($schedule_table as $row) {
                if ($row == '00:00,00:00,00:00,00:00')
                    continue;
                $shifts = explode(',', $row);
                // calculating hour of every day
                for ($i = 0; $i < count($shifts); $i += 2) {
                    $start_time = strtotime($shifts[$i]);
                    $end_time = strtotime($shifts[$i + 1]);
                    $duration = $end_time - $start_time;
                    $thisWeekDuration += $duration;
                    $totalInternshipDuration -= $duration;
                }
                $totalWorkingDaysCount++;
                if ($totalInternshipDuration < 0) {
                    break;
                }
            }
            if ($totalInternshipDuration < 0) {
                break;
            }
        }
        return $totalWorkingDaysCount;
    }
    // returned value of this function will be used as reports attr.
    // in weeklyReport table
    public function calculateAllWorkingDaysDate()
    {
        // calculate all working days based on schedule
        // assume that first working day is:
        // TODO: need to get this data from database, haven't created the attr. for it in the db
        $firstWorkingDayDate = verta('2023/01/07');
        // ! clone and ->copy() for verta do the same thing
        $firstWorkingDayDateBackUp = clone $firstWorkingDayDate;
        // get the schedule
        $schedule = $this->schedule();
        // calculate how many days student have to work based on schedule, eg: 30
        $howManyDaysMustWork = self::howManyDaysMustWork($schedule);
        return $howManyDaysMustWork;
        // calculate all working days date, an array of dates
        $allWorkingDaysDate = [];
        $allowedDays = [];
        $weekCounter = 1;
        while ($howManyDaysMustWork > 0) {
            $i = 0;
            $lasti = 0;
            $thisWeek = [];
            // handling one week
            foreach ($schedule as $sch) {
                if ($sch != '00:00,00:00,00:00,00:00') {
                    array_push(
                        $thisWeek,
                        [
                            'title' => self::DAYSOFWEEK[$i],
                            'date' => $firstWorkingDayDate->addDays($i - $lasti)->DateTime()->format('Y-m-d'),
                            'is_done' => false,
                        ]
                    );
                    $lasti = $i;
                    $howManyDaysMustWork--;
                }
                if ($howManyDaysMustWork == 0) {
                    break;
                }
                $i++;
            }
            array_push($allowedDays, [
                'week_number' => $weekCounter,
                'first_day_of_week' => $firstWorkingDayDateBackUp->DateTime()->format('Y-m-d'),
                'is_done' => false,
                'days' => $thisWeek,
            ]);
            // ! note that if we wanna set a verta, we need to clone it ! otherwise every change
            // ! -- to right one, will affect on the left one as well
            $firstWorkingDayDate = clone $firstWorkingDayDateBackUp->addWeek();
            $weekCounter++;
        }
        return $allowedDays;
    }
    public function getLatestUncompletedReportWeek()
    {
        // ?! TODO what i'm taking the student again in here. i need to get the student with this model not the auth and ... (self i mean)
        $student = Auth::user()->student;
        $reports =  $student->weeklyReport->reports;
        foreach ($reports as $week) {
            if ($week['is_done']) {
                continue;
            } else {
                return $week;
            }
        }
        return null;
    }
    public function entrance_year()
    {
        return "1" . substr($this->student_number, 0, 3);
    }
    public static function university_entrance_year_static($student_number)
    {
        return "1" . substr($student_number, 0, 3);
    }
    public function preRegDone()
    {
        return $this->pre_reg_verified == PreRegVerificationStatusEnum::Verified;
    }
    public function degree()
    {
        return SELF::DEGREE[$this->grade];
    }
}
