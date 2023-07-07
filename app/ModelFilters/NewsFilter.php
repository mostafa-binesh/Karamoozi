<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class NewsFilter extends ModelFilter
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
        
        $this->where("title", "LIKE", "%{$search}%")->orWhere('body', 'LIKE', "%{$search}%");
        return $this;
    }
}
