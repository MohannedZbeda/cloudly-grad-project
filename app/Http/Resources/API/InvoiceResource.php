<?php

namespace App\Http\Resources\API;

use App\Http\Resources\InvoiceItemResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
   
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => UserResource::collection($this->whenLoaded('user')), 
            'items' => InvoiceItemResource::collection($this->whenLoaded('items')),
            'paid' => $this->paid,
            'total' => $this->total,
            

        ];
    }
}
