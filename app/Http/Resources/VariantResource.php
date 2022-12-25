<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VariantResource extends JsonResource
{
   
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'attributes' => ValueResource::collection($this->whenLoaded('values')),
            'created_at' => $this->created_at->toDateString(),
            'category_id' => $this->category_id,
            'product' => $this->product->name
        ];
    }
}
