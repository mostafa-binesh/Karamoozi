<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class MessagesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return $this->receiver_id;
        return [
            'id'=>$this->id,
            'receiver'=>[
                'id'=>$this->receiver_id,
                'fullNmae'=>User::getName($this->receiver_id),
            ],
            'sender'=>[
                'id'=>$this->sender_id,
                'fullNmae'=>User::getName($this->sender_id),
            ],
            'title'=>$this->title,
            'body'=>$this->body,
            'image'=>asset('storage/messages/'.$this->image),
            'created_at'=>$this->created_at,
        ];
    }
}
