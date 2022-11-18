<?php

namespace App\Traits;

use App\Http\Resources\pashm;
use Illuminate\Http\Resources\Json\JsonResource;

trait CPaginationTrait
{
    public function scopeCpagination($query, $req, $apiResource = null)
    {
        $count = ceil($query->count() / ($req->perPage ?? 5)); // this has to be declared before continue of the query
        $data = $query->offset((($req->page ?? 1) - 1) * ($req->perPage ?? 5))->limit(($req->perPage ?? 5))->get();
        $apiResource ? $data = $apiResource::collection($data) : null;
        return [
            'current_page' => $req->page ?? 1,
            'total_pages' => $count,
            'per_page' => $req->perPage ?? 5,
            'data' => $data,
        ];
    }
}
