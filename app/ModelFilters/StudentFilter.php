<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use Illuminate\Contracts\Database\Eloquent\Builder;

class StudentFilter extends ModelFilter
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
    public function status($status)
    {
        $this->where('internship_status', $status);
    }
}
