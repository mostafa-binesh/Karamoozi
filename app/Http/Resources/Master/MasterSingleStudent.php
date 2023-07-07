<?php

namespace App\Http\Resources\Master;

use App\Http\Resources\Students\StudentSubmittedCompanyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MasterSingleStudent extends JsonResource
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
            // 'id' => $this->id,
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'student_number' => $this->student_number,
            'passed_units' => $this->passed_units,
            'degree' => $this->degree(),
            'entrance_year' => $this->entrance_year,
            'faculty' => $this->facultyName(),
            // 'company' => StudentSubmittedCompanyResource::make($this->company),
            'company' => StudentCompany::make($this->company)
        ];
    }
}
