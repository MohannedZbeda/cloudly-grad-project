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
            'new_price' => $this->getDiscounts()['new_price'],
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'discounts' => $this->getDiscounts()['discounts'],
            'created_at' => $this->created_at->toDateString()
           ];
    }
}
