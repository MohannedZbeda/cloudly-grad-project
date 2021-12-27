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
            'ar_name' => $this->ar_name,
            'en_name' => $this->en_name,
            'old_price' => $this->price,
            'new_price' => $this->getDiscount(),
            'discount' => $this->discount_percentage ? $this->discount_percentage.'%' : null,
            'variants' => VariantApiResource::collection($this->whenLoaded('variants')),
            'created_at' => $this->created_at->toDateString()
           ];
    }
}
