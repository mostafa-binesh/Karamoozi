<?php

namespace App\Http\Resources\Master;

use App\Http\Resources\admin\StudentFormsStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class MasterStudents extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        // $this is student
        return [
            'id' => $this->id,
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'student_number' => $this->student_number,
            'entrance_year' => $this->entrance_year,
            'status'=>[
                'stage'=>$this->stage,
                'pre_reg_verified'=>$this->pre_reg_verified,
                // 'forms'=>StudentFormsStatus::make($this)
            ]
        ];
    }
}
