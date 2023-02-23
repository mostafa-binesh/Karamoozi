<?php

namespace App\Http\Resources\admin;

use App\Models\Student;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentPreRegDescription extends JsonResource
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
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'student_number' => $this->student_number,
            'faculty' => [
                'id' => $this->faculty_id,
                'faculty_name' => $this->facultyName(),
            ],
            'degree' => [
                'id' => $this->grade,
                'degree' => Student::DEGREE[$this->grade],
            ],
            'passed_units' => $this->passed_units,
            'semester' => Student::SEMESTER[1],
            'academic_year' => 1401,
            'master' => [
                'id' => $this->professor->id,
                'name' => $this->professorName(),
            ],
            'company' => [
                'id' => $this->company_id,
                'company_name' => $this->companyName(),
            ],
            'internship' => [
                'id' => $this->internship_type,
                'internship_type' => Student::INTERNSHIP_TYPE[$this->internship_type],
            ],
        ];
    }
}
