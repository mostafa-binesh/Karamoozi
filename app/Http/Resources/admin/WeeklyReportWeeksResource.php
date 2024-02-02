<?php

namespace App\Http\Resources\admin;

use App\Models\Term;
use Illuminate\Http\Resources\Json\JsonResource;

class WeeklyReportWeeksResource extends JsonResource
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
            'id'=>$this->id,
            'report'=>$this->report,
            'report_date'=>$this->report_date,
            // 'status'=>$this->status,
            'term_id'=>Term::where('id',$this->id)->first()->name
        ];
    }
}
