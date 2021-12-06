<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
         'discounts' => DiscountResource::collection($this->whenLoaded('discounts')),
         'vouchers' => $this->getVouchers(),
         'created_at' => $this->created_at->toDateString()
        ];
    }
}
