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
            'old_price' => $this->price,
            'new_price' => $this->getDiscounts()['new_price'],
            'attributes' => ValueResource::collection($this->whenLoaded('values')),
            'discounts' => DiscountResource::collection($this->whenLoaded('discounts')),
            'vouchers' => $this->getVouchers(),

            // 'refundable' => [
            //   'ar' => $this->refundable ? 'قابل للإرجاع' : 'غير قابل للإرجاع',
            //   'en' => $this->refundable ? 'Yes' : 'No' 
            // ],
            'created_at' => $this->created_at->toDateString(),
            'category_id' => $this->category_id,
            'product' => $this->product->en_name
        ];
    }
}
