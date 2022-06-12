<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CustomerResource;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index()
    {
        try {
            return response()->json(['customers' => CustomerResource::collection(User::whereRoleIs('customer')->get())]);
        } catch (Error $error) {
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'UserController, Trying to get all admins'])->setStatusCode(500);
        }
    }

    public static function chargeWallet(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'wallet_id' => 'required|exists:wallets,id',
                'amount' => 'required|numeric'
            ]);
            if($validator->fails()) 
              return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
            // $user = User::find($request->id);
            // $wallet = Wallet::where('user_id', $user->id)->whereRelation('type', 'type_name', 'balance_wallet')->first();
            $transaction = DB::transaction(function () use ($request) {
                $transaction = new Transaction();
                $transaction->description = "Wallet Charge";
                $transaction->wallet_id = $request->wallet_id;
                $transaction->credit = true;
                $transaction->amount = $request->amount;
                $transaction->save();
                return $transaction;
            });
            DB::commit();
            return response()->json([
                'status_code' => 200,
                'message' => 'Wallet Charged',
                'wallet_balance' => $transaction->wallet->getWalletBalance()
            ])->setStatusCode(200);
        } catch (Error $error) {
            DB::rollBack();
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'WalletController, Trying to charge user wallet'])->setStatusCode(500);
        }
    }


    public function changeState(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $user = User::find($request->id);
                $user->state = $request->state;
                $user->save();
            });
            DB::commit();
            return response()->json(['status_code' => 200, 'message' => 'Customer State Updated', 'customers' => CustomerResource::collection(User::whereRoleIs('customer')->get())]);
        } catch (Error $error) {
            DB::rollBack();
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'CustomerController, Trying to change customer account state'])->setStatusCode(500);
        }
    }
}
