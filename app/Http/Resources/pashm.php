<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class pashm extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // dd($this);
        // return $this->data;
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'student_number' => $this->student_number,
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'internship_started_at' => $this->internship_started_at,
            'internship_status' => $this->internship_status,
            'editable' => $this->editable(),
        ];
    }
}
