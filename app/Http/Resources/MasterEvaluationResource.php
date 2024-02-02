<?php

namespace App\Http\Resources;

use App\Models\Option;
use Illuminate\Http\Resources\Json\JsonResource;

class MasterEvaluationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $option_name = Option::where('id',$this->option_id)->first()->name;
        return [
            $option_name => $this->grade,
        ];
    }
}
