<?php

namespace App\Http\Resources\chats;

use Illuminate\Http\Resources\Json\JsonResource;

class send extends JsonResource
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
            'receiver'=>$this->user_receiver->fullName(),
            'title'=>$this->title,
        ];
    }
}
