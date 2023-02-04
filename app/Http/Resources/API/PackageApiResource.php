<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductResource;

class PackageApiResource extends JsonResource
{
  
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'products' => ProductApiResource::collection($this->whenLoaded('products')),
            'created_at' => $this->created_at->toDateString()
           ];
    }
}
