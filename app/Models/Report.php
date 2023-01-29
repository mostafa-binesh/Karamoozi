<?php

namespace App\Models;

use App\Traits\EnumTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;
    // use EnumTrait;
    protected $guarded = [];
    // public const REPORT_TYPES = [
    //     "WeeklyReport" => 1,
    //     "Form2" => 2,
    // ];
    // protected static $enums = [
    //     'REPORT_TYPES' => 'report_type',
    // ];
}
