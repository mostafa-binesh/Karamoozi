<?php

namespace App\Http\Resources;

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
        return
            [
                "id" => $this->id,
                "name" => $this->name,
                "start_date" => $this->start_date,
                "end_date" => $this->end_date,
                "is_active" => $this->is_active,
            ];
    }
}
