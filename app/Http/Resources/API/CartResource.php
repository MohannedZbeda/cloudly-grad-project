<?php

namespace App\Http\Resources\API;

use App\Http\Resources\CycleResource;
use App\Models\Variant;
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
            'discount' => $this->cartable->discount_percentage ? $this->cartable->discount_percentage . '%' : null,
            'cycles' => CycleResource::collection($this->cartable_type == Variant::class ? $this->cartable->product->cycles : $this->cartable->cycles),
            'new_price' => $this->cartable->getDiscount(),
            //'type' => $this->cartable_type
        ];
    }
}
