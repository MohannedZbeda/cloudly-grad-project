<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Error;

class WalletController extends Controller
{
    public static function addWallet(Request $request, $return = false, $id = null)
    {
      try {
      $wallet = DB::transaction(function () use($request, $id){    
        $wallet = new Wallet();
        $wallet->user_id = $id ?? auth('sanctum')->user()->id;
        $wallet->name = $request->wallet_name ?? 'Default';    
        $wallet->save();
        return $wallet;
      });
      DB::commit();
      if($return) {
        return;
      }
      return response()->json(['status_code' => 201, 'wallet' => $wallet]);
    }
    catch(Error $error) {
      DB::rollBack();
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'WalletController, Trying to create user wallet'])->setStatusCode(500);  
        
    }
  }

    public static function chargeWallet(Request $request)
    {
        try {
          $wallet = auth('sanctum')->user()->wallet;
          $transaction = DB::transaction(function () use($request, $wallet){ 
            $transaction = new Transaction();
            $transaction->description = "Wallet Charge";
            $transaction->wallet_id = $wallet->id;
            $transaction->debit = true;
            $transaction->amount = $request->amount;
            $transaction->save();
            return $transaction;
          });
          DB::commit();
          return response()->json(['status_code' => 200,
           'message' => 'Wallet Charged', 
           'wallet_balance' => $transaction->wallet->getWalletBalance() ])->setStatusCode(200);  
        } catch(Error $error) {
          DB::rollBack();
          return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'WalletController, Trying to charge user wallet'])->setStatusCode(500);  
            
        }
    }

   
}
