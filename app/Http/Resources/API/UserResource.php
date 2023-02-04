<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Wallet;

class UserResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->info ? $this->info->phone : '',
            'wallet_balance' => Wallet::where('user_id', $this->id)->first()->getWalletBalance()
        ];
    }
}
