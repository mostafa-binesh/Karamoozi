<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CPaginationTrait;
use EloquentFilter\Filterable;
use Illuminate\Notifications\Notifiable;

class Company extends Model
{
    // logic: we don't store industry boss in employees table, actually we just store it
    // in the user table, and connect it to a company in companies table
    // use HasFactory;
    use HasFactory, Notifiable, Filterable;

    use CPaginationTrait;
    // Protected $fillable = [
    //     'company_name',
    //     'company_phone',
    //     'company_type',
    //     'company_postal',
    //     'company_address',
    // ];
    public const COMPANY_TYPE = [
        1 => 'دولتی',
        2 => 'خصوصی',
        3 => 'دانشگاه'
    ];
    protected $guarded = [];
    protected $casts = [
        'verified' => 'boolean',
        // 'submitted_by_student' => 'boolean',
    ];
    // ###############################################
    // ################## RELATIONSHIPS ###################
    // ###############################################
    public function user()
    {
        return $this->belongsTo(User::class,'company_boss_id');
    }
    public function companyType() {
        return SELF::COMPANY_TYPE[$this->company_type];
    }
    // ###############################################
    // ################## FUNCTIONS ###################
    // ###############################################
}
