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
    protected $fillable = [
        'company_name',
        'caption',
        'company_grade',
        'company_number',
        'company_registry_code',
        'company_phone',
        'company_address',
        'company_category',
        'company_postal_code',
        'company_type',
        'company_boss_id',
        'verified',
        'image_logo'
    ];
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
        return $this->belongsTo(User::class, 'company_boss_id');
    }
    public function companyType()
    {
        return self::COMPANY_TYPE[$this->company_type];
    }
    // ###############################################
    // ################## FUNCTIONS ###################
    // ###############################################
}
