<?php 

namespace App\ModelFilters\Studenets;

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
    
}
