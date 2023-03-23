<?php

namespace App\Http\Resources\chats;

use Illuminate\Http\Resources\Json\JsonResource;
// ! name doesn't represent concept perfectly! name needs to be changed later probably
class ReceiveResource extends JsonResource
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
            'sender'=>$this->sender->fullName(),
            'title'=>$this->title,
        ];
    }
}
