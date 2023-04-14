<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustrySupervisor extends Model
{
    use HasFactory;
    protected $guarded = [];
    // ###############################################
    // ################## RELATIONSHIPS ###################
    // ###############################################
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function students()
    {
        return $this->hasMany(Student::class, 'supervisor_id');
    }
    // TODO:  could rename them to a shorter name
    public function industrySupervisorStudents()
    {
        return $this->hasMany(Student::class, 'supervisor_id');
    }
    public function industrySupervisorUnevaluatedStudents()
    {
        // return $this->hasMany(Student::class)->where('supervisor_id', $this->id)->whereNull('evaluations');
        return $this->hasMany(Student::class, 'supervisor_id')->where('supervisor_id', $this->id)->unevaluated();
    }
    public function company()
    {
        return $this->hasOne(Company::class, "company_boss_id");
    }
    // ###############################################
    // ################## FUNCTIONS ###################
    // ###############################################
}
