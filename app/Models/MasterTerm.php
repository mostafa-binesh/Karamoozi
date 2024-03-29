<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTerm extends Model
{
    use HasFactory;
    protected $table = 'master_term';

    protected $fillable = [

        'master_id' ,
        'term_id' ,
        'students_count'
    ];
}
