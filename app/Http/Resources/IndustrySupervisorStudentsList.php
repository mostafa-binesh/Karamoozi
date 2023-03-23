<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IndustrySupervisorStudentsList extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // ! TODO wtf?!
        return parent::toArray($request);
        return [
            'req' => $this->req,
            'data' => [
                'id' => $this->id,
                'first_name' => $this->user->first_name,
            ],
        ];
    }
}
