<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'ar_name' => $this->ar_name,
            'en_name' => $this->en_name,
            'customizable' => $this->customizable,
            'customizable_text' => $this->customizable ? ['ar' =>  'نعم', 'en' => 'Yes'] : ['ar' => 'لا', 'en' => 'No'],
            'custom_attributes' => CustomAttributeResource::collection($this->whenLoaded('customAttributes')),
            'created_at' => $this->created_at->toDateString(),
            'category_id' => $this->category_id,
            'category' => $this->category->en_name
        ];
    }
}
