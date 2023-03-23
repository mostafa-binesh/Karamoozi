<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentProfile extends JsonResource
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
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'student_number' => $this->student_number,
            'national_code' => $this->user->national_code,
            'passed_units' => $this->user->passed_units,
            'email' => $this->user->email,
            'phone_number' => $this->user->phone_number,
            'faculty_name' => optional($this->universityFaculty)->faculty_name,
            'professor' => optional($this->professor)->fullName(),
        ];
    }
}
