<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'image' => $this->getFirstMediaUrl(),
            'cycles' => CycleResource::collection($this->whenLoaded('cycles')),
            'created_at' => $this->created_at->toDateString(),
            'category_id' => $this->category_id,
            'category' => $this->category->name
        ];
    }
}
