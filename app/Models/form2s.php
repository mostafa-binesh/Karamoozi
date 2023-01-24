<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form2s extends Model
{
    use HasFactory;
    protected $guarded = [];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'schedule_table' => 'array',
    ];
    public function industrySupervisor() {
        return $this->belongsTo(IndustrySupervisor::class);
    }
    public function student() {
        return $this->belongsTo(Student::class);
    }
}
