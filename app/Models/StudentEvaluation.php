<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpOption\Option;

class StudentEvaluation extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function option() {
        return $this->belongsTo(Option::class);
    }
}
