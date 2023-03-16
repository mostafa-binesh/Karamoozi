<?php

namespace App\Http\Resources\admin;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'id'=>$this->id,
            'company_name'=>$this->company_name,
            'caption'=>$this->caption,
            'company_grade'=>$this->company_grade,
            'company_boss_id'=>$this->user->fullname(),
            'company_number'=>$this->company_number,
            'company_registry'=>$this->company_registry_code,
        ];
    }
}
