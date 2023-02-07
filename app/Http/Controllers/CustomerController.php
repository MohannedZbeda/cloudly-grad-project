<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CustomerResource;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Subscription;
use Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\API\TransactionResource;
use App\Http\Resources\API\SubscriptionResource;
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
            $transaction = DB::transaction(function () use ($request) {
                $transaction = new Transaction();
                $transaction->description = auth()->user()->name ." Charged Customer Wallet";
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


    public function getTransactions($id)
    {
        try {
            $wallet = Wallet::where('user_id', $id)->first();
            $transactions = Transaction::where('wallet_id', $wallet->id)->get();
            return response()->json(['status_code' => 200, 'transactions' => TransactionResource::collection($transactions)]);
        } catch (Error $error) {
            DB::rollBack();
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'CustomerController, Trying to get customer transactions'])->setStatusCode(500);
        }
    }
    public function getSubs($id)
    {
        try {
            $subs = Subscription::where('user_id', $id)->get();
            return response()->json(['status_code' => 200, 'subs' => SubscriptionResource::collection($subs)]);
        } catch (Error $error) {
            DB::rollBack();
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'CustomerController, Trying to get customer transactions'])->setStatusCode(500);
        }
    }

    public function changeSubStatus($id, Request $request)
    {
        try {
            $sub = Subscription::find($request->id);
            $sub->status = !$sub->status;
            $sub->save();
            $sub->record = $sub->record . " تم " . ($sub->status ? "تفعيل الإشتراك" : "تعطيل الإشتراك");
            $sub->record = $sub->record . "<br>";
            $sub->save();
            $subs = Subscription::where('user_id', $id)->get();
            return response()->json(['status_code' => 200, 'subs' => SubscriptionResource::collection($subs)]);
        } catch (Error $error) {
            DB::rollBack();
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'CustomerController, Trying to get customer transactions'])->setStatusCode(500);
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
