<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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

   public function reserveBalance($from_id, $amount)
   { 
    try { 
      DB::transaction(function() use($from_id, $amount) {
          $transaction = new Transaction();
          $transaction->wallet_id = $from_id;
          $transaction->credit = false;
          $transaction->amount = $amount;
          $transaction->description = 'Amount Reserved for subscription';
          $transaction->save();

          $transaction = new Transaction();
          $transaction->wallet_id = $this->id;
          $transaction->credit = true;
          $transaction->amount = $amount;
          $transaction->description = 'Reserved Amount stored in customer reservation wallet';
          $transaction->save();
      });
        } catch(Error $error) {
           return $error;     
        }  
   }
    public function type() {
      return $this->belongsTo(WalletType::class, 'type_id');
    }

    public function user() {
      return $this->belongsTo(User::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
      }
}
