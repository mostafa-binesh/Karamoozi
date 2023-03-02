<?php

namespace App\ModelFilters\Admin;

use EloquentFilter\ModelFilter;
use Illuminate\Contracts\Database\Eloquent\Builder;

class InitRegStudentsFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];
    public function search($search)
    {
        $this->whereHas('user', function (Builder $query) use ($search) {
            $query->where("first_name", "LIKE", "%{$search}%")->orWhere('last_name', 'LIKE', "%{$search}%")->orWhere('national_code', 'LIKE', "%{$search}%");
        })->orwhere("student_number", "LIKE", "%{$search}%");
    }
    public function entranceYear($year)
    {
        $this->where('entrance_year', $year);
    }
    // init reg verified or verified 
    public function verified($verified)
    {
        $this->where('verified', $verified);
        // $verified == '1' ? $x = true : $x = false;
        // $this->where('verified', $x);
        // $this->where('verified', $verified);
    }
}
