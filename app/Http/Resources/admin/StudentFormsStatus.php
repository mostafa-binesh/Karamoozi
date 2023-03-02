<?php

namespace App\Http\Resources\admin;

use App\Http\Resources\StudentResource;
use Illuminate\Http\Resources\Json\JsonResource;

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
        // 2: refused
        // 3: approved
        $form2_status = 0;
        if (isset($this->form2)) {
            // available
            if (!isset($this->form2->university_approval)) {
                // not checked
                $form2_status = 1;
            } elseif ($this->form2->university_approval) {
                // approved
                $form2_status = 3;
            } else {
                // refused
                $form2_status = 2;
            }
        } else {
            // form not available
            $form2_status = 0;
        }
        return [
            'student' => StudentFormsResource::make($this),
            // 'forms' => [
            'form2' => [ // internship start, the form that ind. supervisor sends
                'status' => $form2_status,
            ],
            'form3' => [ // student evaluations
                'status' => 0,
            ],
            'form4' => [
                'status' => 0,
            ],
            'weekly_reports' => [
                'status' => 0,
            ],
            'finish_internship' => [
                'status' => 0,
            ],
            // ],
        ];
    }
}
