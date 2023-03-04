<?php

namespace App\Http\Resources\chats;

use Illuminate\Http\Resources\Json\JsonResource;

class message extends JsonResource
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
            'body'=>$this->body,
            'sender_name'=>$this->user_sender->fullname(),
            'receiver_name'=>$this->user_receiver->fullname(),
        ];
    }
}
