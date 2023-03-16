<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Traits\CPaginationTrait;
use Spatie\Permission\Models\Role;
use App\Notifications\PasswordReset;
use EloquentFilter\Filterable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasRoles , Filterable;
    use CPaginationTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'birthday',
        'username',
        'national_code',
        'password',
        'verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'roles',
        'email_verified_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    // ###############################################
    // ################## RELATIONSHIPS ###################
    // ###############################################
    public function student()
    {
        return $this->hasOne(Student::class);
    }
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
    public function companyboss()
    {
        return $this->hasOne(Company::class,'company_boss_id');
    }
    public function cars()
    {
        return $this->hasMany(Car::class);
    }
    public function industrySupervisor()
    {
        return $this->hasOne(IndustrySupervisor::class);
    }
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }
    // ###############################################
    // ################## FUNCTIONS ###################
    // ###############################################
    // custom role function
    public function cRole()
    {
        return $this->getRoleNames()->first();
    }
    public function loadRoleInfo()
    {
        switch ($this->role()) {
            case 'student':
                return $this->load('student');
                break;
            case 'employee':
                return $this->load('employee');
                break;
            case 'industry_supervisor':
                return $this->load('industrySupervisor');
            default:
                # code...
                break;
        }
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token, $this->email));
    }
    public function fullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    public function ss($req)
    {
        return $this->offset((($req->page ?? 1) - 1) * ($req->per_page ?? 5))->limit(($req->per_page ?? 5))->get();
    }

}
