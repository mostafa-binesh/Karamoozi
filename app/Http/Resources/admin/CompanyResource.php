<?php

namespace App\Http\Resources\admin;

use App\Models\University_faculty;
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
            'company_number'=>$this->company_number,
            'company_phone'=>$this->company_phone,
            'company_registry'=>$this->company_registry_code,
            'company_address'=>$this->company_address,
            'company_category'=>$this->company_category,
            'company_postal_code'=>$this->company_postal_code,
            'company_type'=>$this->company_type,
            'verified'=> $this->verified? 1 : 0,
            'company_boss_data'=>$this->IndustrySupervisor==null ? '' : $this->IndustrySupervisor->user,
            'image' => $this->image ? asset('/storage/companies/'.$this->image): null,
            // 'company_boss_data'=>$this->user
        ];
    }
}
