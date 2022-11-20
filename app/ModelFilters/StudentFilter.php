<?php 

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class StudentFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];
    public function status($status) {
        // $this->where('')
    }
    public function search($search) {
        // $this->user;
        $this->where('student_number','LIKE',"%{$search}%");
        // ->user->orwhere('first_name','LIKE',"%{$search}%")
        // // ->orwhere('student_number','LIKE',"%{$search}%");
        // ->orwhere('last_name','LIKE',"%{$search}%");
    }
}
