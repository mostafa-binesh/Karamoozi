<?php

namespace App\Http\Resources\chats;

use Illuminate\Http\Resources\Json\JsonResource;

class receive extends JsonResource
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
            'sender'=>$this->user_sender->fullName(),
            'title'=>$this->title,
        ];
    }
}
