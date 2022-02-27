<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestimonyResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'customer_name' => $this->customer->name,
            'statement' => $this->statement,
            'shown_text' => $this->shown ? ['ar' => 'مرئي', 'en' => 'Shown'] : ['ar' => 'مخفي', 'en' => 'Hidden'],
            'shown' => $this->shown,
            'created_at' => $this->created_at->toDateString()
        ];
    }
}
