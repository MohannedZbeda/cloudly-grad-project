<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ValueResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->attribute->id,
            'name' => $this->attribute->name,
            'advanced' => $this->attribute->advanced,
            'values' => $this->attribute->values,
            'value' => $this->value->value,
            'value_id' => $this->value->id
        ];
    }
}
