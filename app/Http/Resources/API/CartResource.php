<?php

namespace App\Http\Resources\API;

use App\Http\Resources\CycleResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{

    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'name' => $this->cartable->name,
            'image' => $this->cartable->getFirstMediaUrl() ?? 'http://159.223.232.157:5000/images/cloudly_logo.png',
            'price' => $this->cartable->price,
            'cycles' => CycleResource::collection($this->cartable->cycles->where('enabled', true)),
        ];
    }
}
