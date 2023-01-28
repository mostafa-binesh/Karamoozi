<?php

namespace App\Models;

use App\Traits\EnumTrait;
use EloquentFilter\Filterable;
use App\Traits\CPaginationTrait;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
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
        'verified' => 'boolean',
        'pre_reg_verified' => 'boolean',
    ];
    /**
     * The attributes that are enum, these are using EnumTrait.
     *
     * @var array
     */
    // it uses EnumTrait
    protected static $enums = [
        'ROLES' => 'role',
        'INTERNSHIP_TYPE' => 'internship_type',
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
    // public const STATUS = [
    //     ''
    // ];
    /**
     * Internship Types
     * 
     * @var array
     */
    public const INTERNSHIP_TYPE = [
        1 => 'x',
        2 => 'y'
        // 'x' => 1,
        // 'y' => 2,

    ];
    public const INTERNSHIP_STATUS = [
        1 => 'شروع نشده',
        2 => 'در حال اجرا',
        3 => 'به اتمام رسیده',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'student_number',
        'national_code',
    ];
    // ###############################################
    // ################## RELATIONSHIPS ###################
    // ###############################################
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function form2() {
        return $this->hasOne(Form2s::class);
    }
    public function universityFaculty() {
        return $this->belongsTo(University_faculty::class);
    }
    public function industrySupervisor() {
        return $this->belongsTo(IndustrySupervisor::class,'id','supervisor_id');
    }
    public function professor() {
        return $this->belongsTo(Employee::class);
    }
    public function company()
    {
        return $this->hasOne(Company::class);
    }
    // ###############################################
    // ################## FUNCTIONS ###################
    // ###############################################
    
    public function status()
    {   //  !!  NOT COMPLETED !!
        // ! bayad baraxe in code bezanam. yani avval az marhaleye akhar shooroo konam biam paiin
        // ! besoorate inverse, if(!$condition) ... 
        // return $this->internship_type;
        if (
            $this->verified
            && $this->pre_reg_verified
            && $this->expert_verification
            && $this->supervisor_in_faculty_verification
            && $this->internship_master_verification
            && $this->educational_assistant_verification
        ) {
            // check internship status
            if ($this->internship_finished) {
                $status = "finishing";
            } else {
                $status = "internship";
                if (
                    !$this->supervisor_submitted
                    ||  !$this->supervisor_verification
                ) {
                    $step = 1;
                    if($this->supervisor_id != null) {
                        
                    }
                } 
                // elseif()
            }
            // add search for verified student's verified form2
        } else {
            $status = 'pre-reg';
        }
        return $status;
    }
    public function statusArray()
    {
        return null;
    }
    
    
    public function industrySupervisorEvaluated() {
        return isEmpty($this->evaluations);
    }
    public function scopeUnevaluated($query) {
        return $query->whereNull('evaluations');
    }
    public function evaluate() {
        $this->internship_status = 'به اتمام رسیده';
        $this->internship_finished_at = now();
        $this->save();
    }
    public function working() {
        $this->internship_status = 'در حال اجرا';
        $this->internship_finished_at = null;
        $this->save();
    }
    public function unevaluate() {
        $this->internship_status = 'شروع نشده';
        $this->internship_finished_at = null;
        $this->save();
    }
    // * i guess it would be better to the name of editable be isEditable
    public function editable() { // can be edited by industry supervisor or not
        return $this->internship_status == SELF::INTERNSHIP_STATUS[1]; 
    }
    public function IndustrySupervisorVerified() {
        $form2 = Form2s::where('student_id',Auth::user()->student->id)->first();
        return isset($form2);
    }
}
