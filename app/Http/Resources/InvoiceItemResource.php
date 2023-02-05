<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceItemResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cycle' => new CycleResource($this->whenLoaded('cycle')),
            'price' => $this->getTotal()
        ];
    }
}
