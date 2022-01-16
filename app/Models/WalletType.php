<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletType extends Model
{
    use HasFactory;

    public function wallets()
    {
        return $this->hasMany(Wallet::class, 'type_id');
    }
}
