<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Error;

class WalletController extends Controller
{
    public static function addWallet(Request $request, $return = false, $id = null)
    {
      if(!($return && $id)) {
        $validator = Validator::make($request->all(), [
          'type_id' => [
            'required',
            Rule::unique('wallet_types')
            ->where('type_id', $request->type)
            ->where('user_id', auth('sanctum')->user()->id)
          ]
      ]);
      if($validator->fails()) 
        return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
      }
      try {
      $wallet = DB::transaction(function () use($request, $id){    
        $wallet = new Wallet();
        $wallet->type_id = $request->type_id ?? 1;
        $wallet->user_id = $id ?? auth('sanctum')->user()->id;
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
          $wallet = Wallet::find($request->wallet_id);
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
