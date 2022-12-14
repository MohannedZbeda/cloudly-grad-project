<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryApiResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'ar_name' => $this->ar_name,
            'en_name' => $this->en_name,
            'products' =>   ProductApiResource::collection($this->whenLoaded('products')),
        ];
    }
}
