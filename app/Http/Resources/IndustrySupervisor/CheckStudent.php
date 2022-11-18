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
            'national_Code' => $this->user->national_code,
            'student_number' => $this->student_number,
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
        ];
    }
}
