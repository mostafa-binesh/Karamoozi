<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IndustrySupervisorReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $this is report
        return [
            'id' => $this->id,
            'desc' => $this->description,
            'date' => $this->date,
        ];
    }
}
