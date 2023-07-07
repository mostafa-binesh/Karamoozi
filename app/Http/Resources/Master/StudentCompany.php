<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentCompany extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $this is company
        return [
            'name' => $this->company_name,
            'type' => $this->companyType(),
            'number' => $this->company_number,
            'postal_code' => $this->company_postal_code,
            'address' => $this->company_address,
        ];
    }
}
