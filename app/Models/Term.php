<?php

namespace App\Models;

use App\Traits\CPaginationTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory, CPaginationTrait, Filterable;
    protected $guarded = [];
    // ###############################################
    // ! ################## RELATIONSHIPS ###################
    // ###############################################
    public function masters()
    {
        return $this->belongsToMany(Employee::class, 'master_term', 'master_id', 'term_id');
    }
    public function students()
    {
        return $this->hasMany(Student::class);
    }
    public function faculties()
    {
        return University_faculty::get()->all();
    }
    // ###############################################
    // ! ################## FUNCTIONS ###################
    // ###############################################
    // current active term 
    // ! usage: $currentTerm = Term::currentTerm()->first();
    // ! usage: $currentTerm = Term::currentTerm();
    public function scopeCurrentTerm($query)
    {
        $now = now()->format('Y-m-d H:i:s');
        return $query->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now); //->first(); // ->first();
    }
    // public static function
    public static function currentTermExists()
    {
        $now = now()->format('Y-m-d H:i:s');
        $term = SELF::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)->first();
        if (!$term) {
            return response()->json([
                'message' => 'ترم فعالی وجود ندارد',
            ], 400);
        } else {
            return null;
        }
    }
    public static function noTermError() {
        return response()->json([
            'message' => 'ترم فعالی وجود ندارد',
        ], 400);
    }
    
}
