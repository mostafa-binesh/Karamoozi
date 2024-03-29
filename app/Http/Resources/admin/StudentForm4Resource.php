<?php

namespace App\Http\Resources\admin;

use App\Models\CompanyEvaluation;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class StudentForm4Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $desc = '';
        $companyEvaluations = CompanyEvaluation::where('student_id', $this->id)->with('option')->get();
        foreach($companyEvaluations as $companyEvaluation){
            if($companyEvaluation->description){
                $desc = $companyEvaluation->description;
            }
        }
        return [
            'evaluations' => CompanyEvaluationResource::collection($companyEvaluations),
            'comment' => $desc,
            'status' => $this->form4_verified,
            'student' => [
                'id' => $this->id,
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'faculty_name' => $this->facultyName(),
                'student_number' => $this->student_number,
                'internship_start_date' => $this->form2->internship_started_at,
                'internship_finish_date' => $this->form2->internship_finished_at,
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
                'full_name' => $this->industrySupervisor->user->fullName,
                'position' => $this->form2->supervisor_position,
            ],

        ];
    }
}
