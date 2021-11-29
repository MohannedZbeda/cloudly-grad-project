<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ValueResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->attribute->id,
            'attribute_ar_name' => $this->attribute->ar_name,
            'attribute_en_name' => $this->attribute->en_name,
            'value' => $this->value,
        ];
    }
}
