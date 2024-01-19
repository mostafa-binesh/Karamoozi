<?php

namespace App\Http\Resources;

use App\Enums\VerificationStatusEnum;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

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

        // if (empty($this)) return null;
        // // $notCompletedDays = [];
        // foreach ($this as $index => $weeklyReport) {
        //     // dd($weeklyReport[1]);
        //     if($weeklyReport->status != VerificationStatusEnum::NotAvailable) {
        //         array_push($notCompletedDays,[
        //             'title' => Jalalian::fromDateTime($weeklyReport->date)->format('%A'),
        //             'date' => $weeklyReport['date'],
        //         ]);
        //     }
        // }
        return [
            'title' => Jalalian::fromDateTime($this->date)->format('%A'),
            'date' => $this->date->format('Y-m-d'),
            // "week_number" => $this->first()->week_number,
            // "first_day_of_week" => firstDayOfWeek($this->first()->date),
            // 'days' => $notCompletedDays,
            // 'days' => $this['days'],
        ];
    }
}
