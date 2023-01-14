<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
         'id' => $this->id,
         'name' => $this->name,
         'price' => $this->price,
         'products' => ProductResource::collection($this->whenLoaded('products')),
         'cycles' => CycleResource::collection($this->whenLoaded('cycles')),
         'created_at' => $this->created_at->toDateString()
        ];
    }
}
