<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form3s extends Model
{
    use HasFactory;

    protected $table = 'Form3s';

    protected $fillable = [
        'student_id',
        'term_id' ,
        'grade',
        'verified'
    ];
}
