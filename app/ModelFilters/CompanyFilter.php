<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class CompanyFilter extends ModelFilter
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

        $this->where("company_name", "LIKE", "%{$search}%")->orWhere('company_number', 'LIKE', "%{$search}%")
            ->orWhere('company_registry_code', 'LIKE', "%{$search}%")->orWhere("company_phone", "LIKE", "%{$search}%");

        return $this;

    }
}
