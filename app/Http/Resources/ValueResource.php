<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ValueResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'created_at' => $this->created_at->toDateString(),
            'attribute' => $this->whenLoaded('attribute')
        ];
    }
}
