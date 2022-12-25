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
            'name' => $this->name,
            'products' => $this->whenLoaded('products'),
            'attributes' => $this->whenLoaded('attributes'),
            'created_at' => $this->created_at->toDateString()
        ];
    }
}
