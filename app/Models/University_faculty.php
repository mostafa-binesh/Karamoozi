<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University_faculty extends Model
{
    use HasFactory;
    protected $guarded = [];
    // relations
    public function employees() {
        return $this->hasMany(Employee::class);
    }
    public function masters() {
        return $this->employees()->hasRole('master');
    }
}
