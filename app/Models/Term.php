<?php

namespace App\Models;

use App\Traits\CPaginationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory, CPaginationTrait;
    protected $guarded = [];
    // ###############################################
    // ################## RELATIONSHIPS ###################
    // ###############################################
    public function masters()
    {
        return $this->belongsToMany(Employee::class, 'master_term', 'master_id','term_id');
    }
    public function students()
    {
        return $this->hasMany(Student::class);
    }
    // ###############################################
    // ################## FUNCTIONS ###################
    // ###############################################
}
