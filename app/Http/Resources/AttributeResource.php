<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'ar_name' => $this->ar_name,
            'en_name' => $this->en_name,
            'advanced' => $this->advanced,
            'created_at' => $this->created_at->toDateString()
        ];
    }
}
