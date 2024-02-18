<?php

namespace App\Http\Resources\Students;

use App\Models\MasterTerm;
use Illuminate\Http\Resources\Json\JsonResource;

class preRegFacultiesWithMasters extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        $employees = [];
        foreach($this->employees as $employee){
            $count = MasterTerm::where('master_id', $employee->id)->first()->students_count;
            if($count>0){
                array_push($employees , $employee);
            }

        }
        return [
            'id' => $this->id,
            'faculty_name' => $this->faculty_name,
            'masters' => preRegMasters::collection($employees),
            // 'masters' => $this->employees,
        ];
    }
}
