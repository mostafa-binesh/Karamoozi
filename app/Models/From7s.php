<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class From7s extends Model
{
    use HasFactory;

    protected $table = 'Form7s';

    protected $fillable = [
        'student_id',
        'term_id',
        'letter_date',
        'letter_number',
        'supervisor_approval',
        'verify_industry_collage'
    ];
}
