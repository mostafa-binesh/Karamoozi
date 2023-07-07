<?php

namespace App\Http\Resources\IndustrySupervisor;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentEvaluationResource extends JsonResource
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
        // ! internship status is stage ! 
        return [
            'student_number' => $this->student_number,
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'internship_started_at' => $this->internship_started_at,
            'internship_status' => $this->stage,
            'editable' => $this->editableAsIndSup(),
        ];
    }
}
