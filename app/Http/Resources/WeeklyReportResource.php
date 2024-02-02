<?php

namespace App\Http\Resources;

use App\Models\Term;
use Illuminate\Http\Resources\Json\JsonResource;

class WeeklyReportResource extends JsonResource
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
            'status'=>$this->status,
            'term_id'=>Term::where('id',$this->term_id)->first()->name
        ];
    }
}
