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
            'ar_type' => $this->advanced ? 'متقدمة' : 'عادية',
            'en_type' => $this->advanced ? 'Advanced' : 'Basic',
            'created_at' => $this->created_at->toDateString()
        ];
    }
}
