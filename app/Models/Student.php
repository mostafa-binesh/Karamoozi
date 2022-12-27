<?php

namespace App\Models;

use App\Traits\CPaginationTrait;
use App\Traits\EnumTrait;
use EloquentFilter\Filterable;
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
        'evaluations' => 'array'
    ];
    /**
     * The attributes that are enum, these are using EnumTrait.
     *
     * @var array
     */
    // it uses EnumTrait
    // INTERNSHIP_STATUS enum has been assigned to internship_status attribute of a student
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
        3 => 'ارزیابی شده',
        4 => 'به اتمام رسیده',
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }
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
    public function company()
    {
        return $this->hasOne(Company::class);
    }
    public function industrySupervisorEvaluated() {
        return isEmpty($this->evaluations);
    }
    public function scopeUnevaluated($query) {
        return $query->whereNull('evaluations');
    }
    public function readyToEvaluate() {
        return $this->internship_status == SELF::INTERNSHIP_STATUS[1]; 
    }
    public function evaluated() {
        return $this->internship_status == SELF::INTERNSHIP_STATUS[3]; 
    }
    public function unevaluate() {
        // i think it will work with only giving the number of internship status, for example $this->is = 2
        return $this->internship_status = SELF::INTERNSHIP_STATUS[2]; 
    }
    public function evaluate() {
        return $this->internship_status = SELF::INTERNSHIP_STATUS[3]; 
    }
    public function editable() { // can be edited by industry supervisor
        return $this->internship_status == SELF::INTERNSHIP_STATUS[1]; 
    }
}