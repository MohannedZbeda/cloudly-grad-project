<?php

namespace App\Http\Resources\API;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    
    public function toArray($request)
    {

    return [
        'item_id' => $this->id,
        'ar_name' => $this->cartable->ar_name,
        'en_name' => $this->cartable->en_name,
        'quantity' => $this->quantity,
        'old_price' => $this->cartable->price,
        'new_price' => $this->cartable->getDiscounts()['new_price'],
        'discounts' => $this->cartable->getDiscounts()['discounts']
    ];
    }
}
