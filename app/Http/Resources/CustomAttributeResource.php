<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomAttributeResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'attribute' => [
                'id' => $this->attribute->id,
                'ar' => $this->attribute->ar_name,
                'en' => $this->attribute->en_name
            ],
            'custom_price' => $this->custom_price,
            'unit_max' => $this->unit_max,
            'unit_min' => $this->unit_min
        ];
    }
}
