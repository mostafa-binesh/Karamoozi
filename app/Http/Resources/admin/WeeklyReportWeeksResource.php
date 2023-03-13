<?php

namespace App\Http\Resources\admin;

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
            'id' => 1,
        ];
        // return [
        //     'id' => $this->id,
        //     'title' => $this->title,
        // ];
        // return [
        //     'id' => $this->week_number,
        //     'first_day_of_week' => $this->first_day_of_week,
        // ];
    }
}
