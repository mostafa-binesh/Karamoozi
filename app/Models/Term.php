<?php

namespace App\Models;

use App\Traits\CPaginationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory, CPaginationTrait;
    protected $guarded = [];
}
