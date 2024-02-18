<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'rand_id' => $this->rand_id,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'user_name' => $this->username,
            'role' => $this->cRole()
        ];
    }
}
