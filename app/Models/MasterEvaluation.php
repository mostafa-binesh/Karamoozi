<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterEvaluation extends Model
{
    use HasFactory;


    protected $table = 'master_evaluations';

    protected $fillable = [
        'student_id' ,
        'term_id' ,
        'option_id',
        'grade' ,
    ];
}
