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
         'price' => $this->price,
         'new_price' => $this->getDiscount(),
         'variants' => VariantResource::collection($this->whenLoaded('variants')),
         'discount' => $this->discount_percentage ? $this->discount_percentage.'%' : null,
         'vouchers' => $this->getVouchers(),
         'created_at' => $this->created_at->toDateString()
        ];
    }
}
