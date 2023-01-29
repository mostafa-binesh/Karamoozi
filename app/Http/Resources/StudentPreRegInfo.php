<?php

namespace App\Http\Resources;

use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentPreRegInfo extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $student = $this->student;
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'student_number' => $student->student_number,
            'faculty_name' => $student->facultyName(),
            'degree' => Student::DEGREE[$student->grade],
            'passed_units' => $student->passed_units,
            'semester' => Student::SEMESTER[1],
            'academic_year' => 1401,
            'master_name' => $student->professorName(),
            'company_name' => $student->companyName(),
        ];
    }
}
