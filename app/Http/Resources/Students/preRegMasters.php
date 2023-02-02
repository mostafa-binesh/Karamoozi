<?php

namespace App\Http\Resources\Students;

use Illuminate\Http\Resources\Json\JsonResource;

class preRegMasters extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return [
        //     'id' => $this->id,
        //     'name' => $this->user->fullName(),
        // ];
        if ($this->user != []) {
            return [
                'id' => $this->id,
                'name' => $this->user->fullName(),
            ];
        } else {
            return null;
        }
    }
}
