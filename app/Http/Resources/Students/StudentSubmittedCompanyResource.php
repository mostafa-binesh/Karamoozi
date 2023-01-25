<?php

namespace App\Http\Resources\Students;

use Illuminate\Http\Resources\Json\JsonResource;

use function PHPUnit\Framework\isNull;

class StudentSubmittedCompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'name' => $this->company_name,
            'type' => $this->company_type,
            'number' => $this->company_number,
            'postal_code' => $this->company_postal_code,
            'address' => $this->company_address,
        ];
    }
}
