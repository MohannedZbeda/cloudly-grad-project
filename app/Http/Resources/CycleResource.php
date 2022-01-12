<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CycleResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'ar_name' => $this->ar_name,
            'en_name' => $this->en_name,
            'months' => $this->months,
            'enabled' => $this->enabled,
            'created_at' => $this->created_at->toDateString()
        ];
    }
}
