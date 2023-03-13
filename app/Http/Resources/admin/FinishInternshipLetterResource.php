<?php

namespace App\Http\Resources\admin;

use Illuminate\Http\Resources\Json\JsonResource;

class FinishInternshipLetterResource extends JsonResource
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
            'letter_name' => $this->form2->introduction_letter_number,
            'letter_date' => $this->form2->introduction_letter_date,
            'full_name' => $this->user->fullName(),
            'duration' => 240, // ! harcoded
            'student_number' => $this->student_number,
            'internship_start_date' => $this->form2->internship_start_date,
            'internship_finish_date' => $this->form2->internship_finished_at,
            'company' => $this->companyName(),
            'internship_supervisor' => $this->industrySupervisor->user->fullName(),
        ];
    }
}
