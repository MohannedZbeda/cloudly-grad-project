<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    
    public function toArray($request)
    {

    return [
        'item_id' => $this->id,
        'ar_name' => $this->cartable->ar_name,
        'en_name' => $this->cartable->en_name,
        'old_price' => $this->cartable->price,
        'discount' => $this->cartable->discount_percentage . '%',
        'new_price' => $this->cartable->getDiscount()
    ];
    }
}
