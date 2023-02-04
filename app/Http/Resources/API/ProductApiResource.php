<?php

namespace App\Http\Resources\API;

use App\Http\Resources\CycleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductApiResource extends JsonResource
{
   
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->getFirstMediaUrl(),
            'price' => $this->price,
            'category' => $this->category->name,
            'cycles' => CycleResource::collection($this->whenLoaded('cycles'))
        ];
    }
}
