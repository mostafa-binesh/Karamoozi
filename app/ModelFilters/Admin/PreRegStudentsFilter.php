<?php

namespace App\ModelFilters\Admin;

use EloquentFilter\ModelFilter;
use Illuminate\Contracts\Database\Eloquent\Builder;


class PreRegStudentsFilter extends ModelFilter
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
    // pre reg verified
    public function verified($verified)
    {
        $this->where('pre_reg_verified', $verified);
    }
    public function faculty($faculty)
    {
        $this->where('faculty_id', $faculty);
    }
}
