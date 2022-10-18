<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class committee extends Model
{
    use HasFactory;
    protected $fillable = [
        'committee_name',
        'caption',
        'image',
        // 'phone_number',
        // 'birthday',
    ];
}
