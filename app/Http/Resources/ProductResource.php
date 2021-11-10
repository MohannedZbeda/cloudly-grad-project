<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'ar_name' => $this->ar_name,
            'en_name' => $this->en_name,
            'created_at' => $this->created_at->toDateString(),
            'category_id' => $this->category_id,
            'category' => $this->category->en_name
        ];
    }
}
