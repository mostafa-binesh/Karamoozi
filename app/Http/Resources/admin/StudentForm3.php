<?php

namespace App\Http\Resources\admin;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentForm3 extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'student' => [
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'faculty_name' => $this->facultyName(),
            ],
            'company' => [
                // ! i guess there are some problems with CompanyName function
                'name' => $this->companyName(),
                'type' => $this->company->company_type,
                'phone_number' => $this->company->company_phone,
                'postal_code' => $this->company->company_postal_code,
                'address' => $this->company->company_address,
            ],
            'industry_supervisor' => [
                'full_name' => $this->industrySupervisor->user->fullName(),
                'position' => $this->form2->supervisor_position,
            ],
            'option'=>[
                'type'=>$this->option->type,
                'name'=>$this->option->name,
            ],
            'studentEvaluations'=>[
                'option_id'=>$this->studentEvaluations->option_id,
                'value'=>$this->studentEvaluations->value,
            ]
        ];

    }
}
