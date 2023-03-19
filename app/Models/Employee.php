<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
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
    public function faculty() {
        return $this->belongsTo(University_faculty::class);
    }
    // ###############################################
    // ################## FUNCTIONS ###################
    // ###############################################
    public function fullName() {
        return $this->user->first_name . " " . $this->user->last_name;
    }
    public function studentMaster(){
        return $this->hasMany(Student::class);
    }
    public function get_faculty_id(){
        return $this->faculty_id;
    }
}
