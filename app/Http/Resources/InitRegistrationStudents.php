<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InitRegistrationStudents extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'student_number' => $this->student_number,
            'faculty' => "تو پیش ثبت نام پر میشه",
            'term' => 'اصلا چنین دیتایی نگرفتیم',
            'national_code' => $this->user->national_code,
            'national_code' => $this->user->phone_number,
        ];
    }
}
