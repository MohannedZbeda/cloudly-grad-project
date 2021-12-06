<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public static function addWallet(Request $request, $return = false, $id = null)
    {
        
      $wallet = new Wallet();
      $wallet->user_id = $id ?? auth('sanctum')->user()->id;
      $wallet->name = $request->wallet_name ?? 'Default Wallet';    
      $wallet->save();
      if($return) {
          return;
      }
      return response()->json(['status_code' => 201, 'wallet' => $wallet]);
    }
}
