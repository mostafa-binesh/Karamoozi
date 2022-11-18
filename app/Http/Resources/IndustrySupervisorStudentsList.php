<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IndustrySupervisorStudentsList extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    // public function toArray($request)
    // {
        // }
        // public function withResponse($request, $response)
        // {
            //     $jsonResponse = json_decode($response->getContent(), true);
            //     unset($jsonResponse['links'], $jsonResponse['meta']);
            //     $response->setContent(json_encode($jsonResponse));
    // }
    public function toArray($request)
    {
            return parent::toArray($request);
        // return [
        //     'id' => $this->id,
        //     'data' => $this->user->first_name,
        // ];
        // dd($request);
        // dd($this);
        return [
            // 'current_page' => $this->current_page(),
            'req' => $this->req,
            'data' => [
                'id' => $this->id,
                'first_name' => $this->user->first_name,
            ],
        ];
    }
    // public function withResponse($request, $response)
    // {
    //     $data = $response->getData(false);
    //     $prev = $data['links']['prev'];
    //     $next = $data['links']['next'];
    //     $self = $data['links']['self'];
    //     $data['links'] = compact('prev', 'next', 'self');
    //     $response->setData($data);
    // }
}
