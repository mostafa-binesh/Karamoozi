<?php

namespace App\Models;

use App\Traits\CPaginationTrait;
use EloquentFilter\Filterable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class University_faculty extends Model
{
    use HasFactory, HasRoles;
    use CPaginationTrait;
    use Filterable;
    // use SoftDeletes; // enable it on next migration
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
    }
}
