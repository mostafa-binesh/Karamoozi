<?php

namespace App\Http\Resources\admin;

use App\Http\Resources\IndustrySupervisorReportResource;
use App\Http\Resources\ScheduleTableResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentForm2 extends JsonResource
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
            'status' => $this->form2->verified,
            'student' => [
                'id' => $this->id,
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'faculty_name' => $this->facultyName(),
                'student_number' => $this->student_number,
            ],
            'form2' => [
                'created_at' => $this->form2->created_at->format('Y-m-d'),
                'introduction_letter_number' => $this->form2->introduction_letter_number,
                'internship_start_date' => $this->form2->internship_start_date,
                // 'schedule_table' => $this->form2->schedule_table,
                'schedule_table' => ScheduleTableResource::make($this->form2->schedule_table),
                'status' => $this->form2->verified,
                'rejection_reason' => $this->form2->rejection_reason,
            ],
            'company' => [
                // ! i guess there are some problems with CompanyName function
                'name' => $this->companyName(),
                'type' => $this->company->companyType(),
                'phone_number' => $this->company->company_phone,
                'postal_code' => $this->company->company_postal_code,
                'address' => $this->company->company_address,
            ],
            'industry_supervisor' => [
                'full_name' => $this->industrySupervisor->user->fullName(),
                'position' => $this->form2->supervisor_position,
            ],
            'reports' => IndustrySupervisorReportResource::collection($this->indSupervisorReports()),
        ];
    }
}
