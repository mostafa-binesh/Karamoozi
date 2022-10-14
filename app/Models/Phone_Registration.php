<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone_Registration extends Model
{
    use HasFactory;
    protected $table = 'phone_registrations';
    protected $fillable = ['phone_number', 'verification_code'];
}
