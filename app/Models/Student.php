<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use App\Traits\EnumTrait;

class Student extends Model
{
    use HasFactory, Notifiable, EnumTrait;

    /**
     * The attributes that are enum, these are using EnumTrait.
     *
     * @var array
     */
    // it uses EnumTrait
    protected static $enums = [
        'ROLES' => 'role',
        'INTERSHIP_TYPE' => 'intership_type',

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
    {
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
}
