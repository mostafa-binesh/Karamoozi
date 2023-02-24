<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PreRegStudents extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $this is student 
        return [
            'id' => $this->id,
            'name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'student_number' => $this->student_number,
            'national_code' => $this->user->national_code,
            'company' => $this->companyName(),
            'faculty' => $this->facultyName(),
            'entrance_year' => $this->entrance_year(),
            'passed_units' => $this->passed_units,
        ];
    }
}
