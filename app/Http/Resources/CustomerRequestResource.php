<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerRequestResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'customer_name' => $this->user->name,
            'sub_name' => $this->sub->name,
            'descreption' => $this->descreption,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateString()
        ];
    }
}
