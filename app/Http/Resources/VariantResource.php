<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VariantResource extends JsonResource
{
   
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'ar_name' => $this->ar_name,
            'en_name' => $this->en_name,
            'price' => $this->price,
            'new_price' => $this->getDiscount(),
            'customized_by' => $this->customized_by,
            'attributes' => ValueResource::collection($this->whenLoaded('values')),
            'cycles' => CycleResource::collection($this->whenLoaded('cycles')),
            'discount' => $this->discount_percentage ? $this->discount_percentage.'%' : null,
            'vouchers' => $this->getVouchers(),
            'created_at' => $this->created_at->toDateString(),
            'category_id' => $this->category_id,
            'product' => $this->product->en_name
        ];
    }
}
