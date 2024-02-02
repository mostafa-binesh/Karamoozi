<?php

namespace App\Http\Resources\admin;

use App\Http\Resources\StudentResource;
use App\Models\WeeklyReport;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\From7s as Form7;

class StudentFormsStatus extends JsonResource
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
        // statuses:
        // 0: not available
        // 1: not checked
        // 2: approved
        // 3: refused
        // #### FORM2
        // $form2_status = optional($this->form2)->verified ?? 0;
        $form2_status = $this->form2->verified;
        // if (isset($this->form2)) {
        //     // available
        //     if (!isset($this->form2->verified)) {
        //         // not checked
        //         $form2_status = 1;
        //     } elseif ($this->form2->verified == 3) {
        //         // approved
        //         $form2_status = 2;
        //     } else {
        //         // refused
        //         $form2_status = 3;
        //     }
        // } else {
        //     // form not available
        //     $form2_status = 0;
        // }
        // #### FORM3
        // $form3_status = $this->studentEvaluations->verified ?? 0;
        $form3_status = $this->evaluations_verified;
        // if (isset($this->studentEvaluations)) {
        //     // available
        //     if (!isset($this->studentEvaluations->verified)) {
        //         // not checked
        //         $form3_status = 1;
        //     } elseif ($this->studentEvaluations->verified) {
        //         // approved
        //         $form3_status = 2;
        //     } else {
        //         // refused
        //         $form3_status = 3;
        //     }
        // } else {
        //     // form not available
        //     $form3_status = 0;
        // }
        $weekly_status = WeeklyReport::where('student_id',$this->id)->first();
        return [
            'student' => StudentFormsResource::make($this),
            // 'forms' => [
            'form2' => [ // internship start, the form that ind. supervisor sends
                'status' => $form2_status,
            ],
            'form3' => [ // student evaluations
                'status' => $form3_status,
                // 'status' => $this->studentEvaluations,
            ],
            'form4' => [
                'status' => $this->form4_verified,
            ],
            'weekly_reports' => [
                'status' => isset($weekly_status->id) ? 1 : 0,
            ],
            'final_reports' => [
                'status' => $this->final_report_path == null ? 0 : 1,
            ],
            'finish_internship' => [
                'status' => Form7::where('student_id',$this->id)?->first()?->verify_industry_collage ?
                Form7::where('student_id',$this->id)->first()->verify_industry_collage : 0,
            ],
            // ],
        ];
    }
}
