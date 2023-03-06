<?php

namespace App\Http\Resources\admin;

use App\Models\StudentEvaluation;
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
        // $this is student
        // ! needs user, company, form2, studentEvaluation relationships
        $total_grade = 0;
        foreach ($this->studentEvaluations as $evaluation) {
            $total_grade += StudentEvaluation::StudentEvaluation[$evaluation->value];
        }
        // $dictionary = new \MojtabaaHN\PersianNumberToWords\Dictionary();
        // $converter = new \MojtabaaHN\PersianNumberToWords\PersianNumberToWords($dictionary);
        return [
            'student' => [
                'id' => $this->id,
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'faculty_name' => $this->facultyName(),
                'student_number' => $this->student_number,
                'internship_start_date' => $this->form2->internship_start_date,
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
                'full_name' => $this->industrySupervisor->user->fullName(),
                'position' => $this->form2->supervisor_position,
            ],
            'student_evaluations' => isset($this->studentEvaluations) ? StudentEvaluationResource::collection($this->studentEvaluations) : null,
            'total_grade' => $total_grade, // ! fix later
            // 'total_grade_word' => $converter->convert($total_grade),
            'status' => $this->evaluation_verified,
            'final_evaluation' => [
                [
                    'title' => 'میانگین ارزشیابی بازدید های استاد کارآموزی',
                    'grade' => 1,
                ],
                [
                    'title' => 'میانگین ارزشیابی سرپرست کارآموزی در صنعت',
                    'grade' => 1,
                ],
                [
                    'title' => 'ارزشیابی گزارش های کارآموزی دانشجو توسط استاد',
                    'grade' => 1,
                ],
                [
                    'title' => 'امتحان و دفاع دانشجو - در جلسه حضوری',
                    'grade' => 1,
                ],
                [
                    'title' => 'نمره میانگین',
                    'grade' => 1,
                ],
                [
                    'title' => 'ارزیابی نهایی',
                    'grade' => 1,
                ],
            ]
        ];
    }
}
