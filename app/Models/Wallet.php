<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Error;

class Wallet extends Model
{
    use HasFactory;
   public function getWalletBalance()
   {
     
    try {
          $balance = $this->transactions->sum(function($t) { 
            return $t->debit ? $t->amount : $t->amount * -1; 
        });
          return $balance;
        } catch(Error $error) {
           return throw $error;     
        }  
   }
    public function type() {
      return $this->belongsTo(WalletType::class);
    }

    public function user() {
      return $this->belongsTo(User::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
      }
}
