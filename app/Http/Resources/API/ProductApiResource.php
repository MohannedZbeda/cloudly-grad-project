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
            'ar_name' => $this->ar_name,
            'en_name' => $this->en_name,
            'image' => $this->getFirstMediaUrl(),
            'category' => [
              'ar_name' => $this->category->ar_name,
              'en_name' => $this->category->en_name
            ],
            'variants' => VariantApiResource::collection($this->whenLoaded('variants')),
            'cycles' => CycleResource::collection($this->whenLoaded('cycles'))
        ];
    }
}
