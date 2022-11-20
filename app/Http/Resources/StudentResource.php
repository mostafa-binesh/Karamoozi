<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'national_code' => $this->user->national_code,
            'student_number' => $this->student_number,
        ];
    }
}
