<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Wallet;
class CustomerResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "username" => $this->username,
            "email" => $this->email,
            "phone" => $this->info->phone,
            "state" => $this->state,
            'wallet' => $this->wallet,
            'wallet_balance' => $this->wallet->getWalletBalance(),
            "created_at" => $this->created_at->toDateString()
        ];
    }
}
