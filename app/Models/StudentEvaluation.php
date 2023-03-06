<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentEvaluation extends Model
{
    use HasFactory;
    // 1: zaiif 0.5
    // 2: medium 1.5
    // 3: khoob 2
    // 4: aali 2.5
    public const StudentEvaluation = [
        1 => 0.5,
        2 => 1.5,
        3 => 2,
        4 => 2.5,
    ];
    protected $guarded = [];
    public function option() {
        return $this->belongsTo(Option::class);
    }
}
