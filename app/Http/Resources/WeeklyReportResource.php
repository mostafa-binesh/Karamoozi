<?php

namespace App\Http\Resources;

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
        // i didn't create a new resource for day
        // didn't know how to handle 
        // return parent::toArray($request);

        if (empty($this)) return null;
        $notCompletedDays = [];
        foreach ($this['days'] as $day) {
            if(!$day['is_done']) {
                array_push($notCompletedDays,[
                    'title' => $day['title'],
                    'date' => $day['date'],
                ]);
            }
        }
        return [
            "week_number" => $this['week_number'],
            "first_day_of_week" => $this['first_day_of_week'],
            'days' => $notCompletedDays,
            // 'days' => $this['days'],
        ];
    }
}
