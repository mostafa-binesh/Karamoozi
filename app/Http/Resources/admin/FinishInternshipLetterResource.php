<?php

namespace App\Http\Resources\admin;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\From7s as Form7;
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
        $status = Form7::where('student_id',$this->id)->first();
        // $this is student
        return [
            'letter_number_start' => $this->form2->introduction_letter_number,
            'letter_date_start' => $this->form2->introduction_letter_date,
            'full_name' => $this->user->fullName,
            'duration' => 240, // ! harcoded
            'student_number' => $this->student_number,
            'internship_start_date' => $this->form2->internship_started_at,
            'internship_finish_date' => $this->form2->internship_finished_at,
            'company' => $this->companyName(),
            'internship_supervisor' => $this->industrySupervisor->user->fullName,
            'status'=>isset($status) ? ($status->verify_industry_collage ? 1 : 0)  : 0,
            'letter_number_end' => isset($status->letter_number) ? $status->letter_number : null ,
            'letter_date_end'=> isset($status->letter_date) ? $status->letter_date : null
        ];
    }
}
