<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class TransactionResource extends JsonResource
{
   
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'credit' => $this->credit,
            'amount' => floatval($this->amount),
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s')
        ];
    }
}
