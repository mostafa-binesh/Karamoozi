<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleTableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // ! $this is "schedule_table": [
        //     "08:00,12:00,14:00,18:00",
        //     "08:00,12:00,14:00,18:00",
        //     "00:00,00:00,00:00,00:00",
        //     "08:00,12:00,14:00,18:00",
        //     "08:00,13:00,13:30,18:00",
        //     "00:00,00:00,00:00,00:00"
        // ! ]
        $table = [];
        $days = [
            'شنبه',
            'یک شنبه',
            'دو شنبه',
            'سه شنبه',
            'چهار شنبه',
            'پنج شنبه',
            'جمعه',
        ];
        $i = 0;
        // ! i donno why i need to add first foreach, but it's necessary
        foreach ($this as $schedules) {
            foreach ($schedules as $schedule) {
                $times = explode(",", $schedule);
                array_push($table, [
                    $days[$i++] => [
                        'ms' => $times[0],
                        'me' => $times[1],
                        'es' => $times[2],
                        'ee' => $times[3],
                    ],
                ]);
            }
        }
        return $table;
    }
}
