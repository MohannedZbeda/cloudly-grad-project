<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    public function type() {
      return $this->belongsTo(WalletType::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
      }
}
