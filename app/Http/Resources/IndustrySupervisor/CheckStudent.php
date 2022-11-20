<?php

namespace App\Http\Resources\IndustrySupervisor;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckStudent extends JsonResource
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
        
        return [
            'national_code' => $this->national_code,
            'student_number' => $this->student->student_number,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
        ];
    }
}
