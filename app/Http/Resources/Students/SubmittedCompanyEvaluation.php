<?php

namespace App\Http\Resources\Students;

use Illuminate\Http\Resources\Json\JsonResource;

class SubmittedCompanyEvaluation extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // evaluation
        if (isset($this->option)) {
            return [
                'id' => $this->id,
                'evaluation' => $this->evaluation,
                'option' => $this->option->name,
            ];
        } 
        // else {
        //     // evaluation which has description
        //     return [
        //         'comment' => $this->description,   
        //     ];
        // }
    }
}
