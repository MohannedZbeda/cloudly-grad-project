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
          $balance = Transaction::where('wallet_id', $this->id)->get()->sum(function($t) { 
            return $t->credit ? $t->amount : $t->amount * -1; 
        });
          return $balance;
        } catch(Error $error) {
           return $error;     
        }  
   }

    public function user() {
      return $this->belongsTo(User::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
      }
}
