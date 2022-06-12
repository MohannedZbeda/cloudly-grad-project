<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Wallet;
class CustomerResource extends JsonResource
{
    
    public function toArray($request)
    {
        $wallet = Wallet::where('user_id', $this->id)->whereRelation('type', 'type_name', 'balance_wallet')->first();
        return [
            "id" => $this->id,
            "name" => $this->name,
            "username" => $this->username,
            "email" => $this->email,
            "phone" => $this->info->phone,
            "state" => $this->state,
            'wallet' => $wallet,
            'wallet_balance' => $wallet->getWalletBalance(),
            "created_at" => $this->created_at->toDateString()
        ];
    }
}
