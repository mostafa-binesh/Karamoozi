<?php

namespace App\Http\Resources;

use App\Models\Student;
use Illuminate\Http\Resources\Json\JsonResource;

class InitRegistrationStudents extends JsonResource
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
            'faculty' => "تو پیش ثبت نام پر میشه",
            'entrance_year' => $this->entrance_year(),
            'national_code' => $this->user->national_code,
            'phone_number' => $this->user->phone_number,
            'verified' => Student::VERIFIED[$this->verified],
        ];
    }
}
