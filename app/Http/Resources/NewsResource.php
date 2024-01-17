<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
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
            'id' => $this->id,
            'title'=> $this->title,
            'body'=>$this->body,
            'image'=>$this->image ? asset('storage/news/'.$this->image): null,
            'created_at'=>$this->created_at
        ];
    }
}
