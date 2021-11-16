<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'ar_name' => $this->ar_name,
            'en_name' => $this->en_name,
            'products' => $this->whenLoaded('products'),
            'attributes' => $this->whenLoaded('attributes'),
            'created_at' => $this->created_at->toDateString()
        ];
    }
}
