<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'record' => $this->record,
            'status' => $this->status,
            'cycle' => $this->cycle->name,
            'start_date' => $this->created_at->toDateString(),
            'expiry_date' => $this->getExpiryDate()
        ];
    }
}
