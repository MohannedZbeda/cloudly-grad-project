<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Resources\API\TransactionResource;
use Illuminate\Support\Facades\Http;
use Error;

class WalletController extends Controller
{
    public function getTransactions(Request $request)
    {
      try {
        $user = auth('sanctum')->user();
        $transactions = $user->wallet->transactions;
        return response()->json(["transactions" => TransactionResource::collection($transactions)]);
      } catch (Error $error) {
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'WalletController, Trying to get wallet transactions'])->setStatusCode(500);        
      }
  }

  public function chargeOnline(Request $request)
    {
      try {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric'
        ]);
        $token = explode(' ', $request->header('Authorization'))[1];
        if(!isset($token))
          return response()->json(['message' => "Unauthorized"])->setStatusCode(401);
       if ($validator->fails()) 
         return response()->json(['errors' => $validator->errors()])->setStatusCode(402);
        $user = auth('sanctum')->user();
        if(!$user->info || !$user->info->phone)
          return response()->json(['error' => "This user doesn't have a mobile number, Please update your information"])->setStatusCode(402);
        $response = Http::withHeaders([
          'Accept' => 'application/json',
          'Content-Type' => 'application/json',
          'Authorization' => 'Bearer '. env('TLYNC_STORE_TOKEN')
      ])->post(env('TLYNC_URL'), [
          'id' => env('TLYNC_STORE_ID'),
          'amount' => $request->amount,
          'phone' => $user->info->phone,
          'email' => $user->email,
          'frontend_url' => "https://cloudly.ly",
          'backend_url' => url('/api/wallets/'.$user->wallet->id.'/charge'),
      ]);
      if(!$response->json('url'))
        return response()->json(['error' => "Something went wrong with payment, Please try again"])->setStatusCode(402);
        
        return response()->json(['url' => $response->json('url')])->setStatusCode(200); 
      } catch (\Throwable $th) {
          throw $th;
      }  
    }

    public static function chargeWallet(Request $request)
    {
        try {
          $user = auth('sanctum')->user();
          $wallet = $user->wallet;
          $transaction = DB::transaction(function () use($request, $wallet){ 
            $transaction = new Transaction();
            $transaction->description = "Wallet Charge";
            $transaction->wallet_id = $wallet->id;
            $transaction->credit = true;
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

    public static function chargeWithStripe(Request $request)
    {
        try {
          $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric'
        ]);
        if ($validator->fails()) 
          return response()->json(['errors' => $validator->errors()])->setStatusCode(422);
          $user = auth('sanctum')->user();
          $wallet = $user->wallet;
          $transaction = DB::transaction(function () use($request, $wallet){ 
            $transaction = new Transaction();
            $transaction->description = "Wallet Charged With Stripe";
            $transaction->wallet_id = $wallet->id;
            $transaction->credit = true;
            $transaction->amount = (double)$request->amount * 5;
            $transaction->save();
            return $transaction;
          });
          DB::commit();
          return response()->json(['status_code' => 200,
           'message' => 'Wallet Charged', 
           'wallet_balance' => $transaction->wallet->getWalletBalance() 
          ])->setStatusCode(200);  
        } catch(Error $error) {
          DB::rollBack();
          return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'WalletController, Trying to charge user wallet'])->setStatusCode(500);  
            
        }
    }

    public function getBalance()
    {
      try {
        $user = auth('sanctum')->user();
        $wallet = $user->wallet;
        return response()->json(["wallet_balance" => $wallet->getWalletBalance()]);
      } catch (Error $error) {
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'WalletController, Trying to get wallet balance'])->setStatusCode(500);        
      }
    }

   
}
