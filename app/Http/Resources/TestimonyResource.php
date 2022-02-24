<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestimonyResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'customer' => UserResource::collection($this->whenLoaded('customer')),
            'statement' => $this->statement,
            'created_at' => $this->created_at->toDateString()
        ];
    }
}
