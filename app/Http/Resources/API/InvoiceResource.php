<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
   
    public function toArray($request)
    {
        return [
            'id' => $this->id
        ];
    }
}
