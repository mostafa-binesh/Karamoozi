<?php

namespace App\Http\Resources\IndustrySupervisor;

use App\Http\Resources\ReportResource;
use App\Models\Report;
use App\Models\Student;
use Illuminate\Http\Resources\Json\JsonResource;

class IndustrySupervisorsStudent extends JsonResource
// Industry Supervisor's Student
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // this is student
        $student = Student::where('id',$this->student_id)->first();
        return [
            'first_name'=>$student->user->first_name,
            'last_name'=>$student->user->last_name,
            'national_code'=>$student->user->national_code,
            'student_number'=>$student->student_number,
            'introduction_letter_number' => $this->introduction_letter_number,
            'introduction_letter_date' => $this->introduction_letter_date,
            'internship_department' => $this->internship_department,
            'supervisor_position' => $this->supervisor_position,
            'internship_start_date' => $this->internship_started_at,
            'internship_website' => $this->internship_website,
            'description' => $this->description,
            'schedule_table' => $this->schedule_table,
            'reports' => ReportResource::collection(Report::where('form2_id',$this->id)->get()),
        ];
    }
}
