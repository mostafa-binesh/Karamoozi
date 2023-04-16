<?php

namespace App\Models;

use App\Traits\CPaginationTrait;
use EloquentFilter\Filterable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class University_faculty extends Model
{
    use HasFactory, HasRoles;
    use CPaginationTrait;
    use Filterable;
    protected $guarded = [];
    // ######
    // ############### relations ##############
    // ######
    public function employees()
    {
        return $this->hasMany(Employee::class, 'faculty_id');
    }
    public function masters()
    {
        // return $this->hasMany(Employee::class,'faculty_id')->user()->role('master')->get();
        // return $this->employees()->user()->role('master')->get();
        // return $this->employees()->hasRole('master');
        // return $this->employees;
    }
}
