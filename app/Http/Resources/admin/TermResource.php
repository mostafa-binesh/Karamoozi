<?php

namespace App\Http\Resources\admin;

use Illuminate\Http\Resources\Json\JsonResource;

class TermResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $this is term
        return [
            'id' => $this->id,
            'name' => $this->name,
            // 'start_date' => $this->start_date,
            // 'end_date' => $this->end_date,
            'students' => $this->students->count(),
            'masters' => $this->masters->count(),
        ];
    }
}
