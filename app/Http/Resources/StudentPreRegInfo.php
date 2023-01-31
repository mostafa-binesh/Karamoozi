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
            'faculty' => [
                'id' => $student->faculty_id,
                'faculty_name' => $student->facultyName(),
            ],
            'degree' => [
                'id' => $student->grade,
                'degree' => Student::DEGREE[$student->grade],
            ],
            'passed_units' => $student->passed_units,
            'semester' => Student::SEMESTER[1],
            'academic_year' => 1401,
            'master' => [
                'id' => $student->professor->id,
                'name' => $student->professorName(),
            ],
            'company' => [
                'id' => $student->company_id,
                'company_name' => $student->companyName(),
            ],
            'internship' => [
                'id' => $student->internship_type,
                'internship_type' => Student::INTERNSHIP_TYPE[$student->internship_type],
            ],
        ];
    }
}
