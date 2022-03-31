<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceItemResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'ar_name' => $this->invoiceable->ar_name,
            'en_name' => $this->invoiceable->en_name,
            'cycle' => new CycleResource($this->whenLoaded('cycle')),
            'price' => $this->getTotal()
        ];
    }
}
