<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use Illuminate\Contracts\Database\Eloquent\Builder;

class MasterFilter extends ModelFilter
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
        // $this->user;
        // $this->wherehas('student_number','LIKE',"%{$search}%");
        $this->where("first_name", "LIKE", "%{$search}%")->orWhere('last_name', 'LIKE', "%{$search}%")
            ->orWhere('national_code', 'LIKE', "%{$search}%")->orWhere("username", "LIKE", "%{$search}%");

        return $this;
        // ->user->orwhere('first_name','LIKE',"%{$search}%")
        // // ->orwhere('student_number','LIKE',"%{$search}%");
        // ->orwhere('last_name','LIKE',"%{$search}%");
    }

    public function faculty($faculty)
    {
        $this->whereHas('employee', function (Builder $query) use ($faculty) {
            $query->where("faculty_id", $faculty);
        });
    }
}
