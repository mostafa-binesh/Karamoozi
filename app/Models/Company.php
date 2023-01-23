<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    // logic: we don't store industry boss in employees table, actually we just store it
        // in the user table, and connect it to a company in companies table
    use HasFactory;
    // Protected $fillable = [
    //     'company_name',
    //     'company_phone',
    //     'company_type',
    //     'company_postal',
    //     'company_address',
    // ];
    protected $guarded = [];
    protected $casts = [
        'verified' => 'boolean',
        // 'submitted_by_student' => 'boolean',
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }
}
