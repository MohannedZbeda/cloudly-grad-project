<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AttributeValueResource;
class AttributeResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'advanced' => $this->advanced,
            'ar_type' => !$this->advanced ? 'أساسية' : 'غير أساسية',
            'en_type' => $this->advanced ? 'Advanced' : 'Basic',
            'values' => AttributeValueResource::collection($this->whenLoaded('values')),
            'created_at' => $this->created_at->toDateString()
        ];
    }
}
