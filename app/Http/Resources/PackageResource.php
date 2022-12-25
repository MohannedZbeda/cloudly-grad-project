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
         'variants' => VariantResource::collection($this->whenLoaded('variants')),
         'cycles' => CycleResource::collection($this->whenLoaded('cycles')),
         'created_at' => $this->created_at->toDateString()
        ];
    }
}
