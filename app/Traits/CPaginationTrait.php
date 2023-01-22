<?php

namespace App\Traits;

use App\Http\Resources\pashm;
use Illuminate\Http\Resources\Json\JsonResource;

trait CPaginationTrait
{
    public function scopeCpagination($query, $req, $apiResource = null)
    {
        $count = ceil($query->count() / ($req->perPage ?? 5)); // this has to be declared before continue of the query
        $count==0 ? $count = 1 : null;
        if($req->page && $req->page > $count) {
            return response()->json([
                'data' => [],
                'message' => 'صفحه ی درخواستی از تعداد صفحات موجود بیشتر است'
            ],400);
        }
        $data = $query->offset((($req->page ?? 1) - 1) * ($req->perPage ?? 5))->limit(($req->perPage ?? 5))->get();
        $apiResource ? $data = $apiResource::collection($data) : null;
        return [
            'meta' => [
                'current_page' => $req->page ?? 1,
                'total_pages' => $count,
                'per_page' => $req->perPage ?? 5,
            ],
            'data' => $data,
        ];
    }
}
