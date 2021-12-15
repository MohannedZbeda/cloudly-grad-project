<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\InvoiceResource;
use Error;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        try {
            $invoices = auth('sanctum')->user()->invoices;
            return response()->json(['status_code' => 200, 'invoices' => InvoiceResource::collection($invoices)])->setStatusCode(200);
        } catch (Error $error) {
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'InvoiceController, Trying to get customer invoices'])->setStatusCode(200);
            
        }
    }

    public function issueInvoice()
    {
      try {
        $invoice = DB::transaction(function() {
          $user_cart = auth('sanctum')->user()->cart;
          $invoice = new Invoice();    
          $invoice->user_id = auth('sanctum')->user()->id;
          $invoice->save();
          $invoice_items = [];
          foreach ($user_cart->items as $item) {
             array_push($invoice_items, [
               'invoice_id' => $invoice->id,
               'quantity' => $item->quantity,
               'invoiceable_id' => $item->cartable_id,
               'invoiceable_type' => $item->cartable_type,
             ]);
          }
          InvoiceItem::insert($invoice_items);  
          DB::table('cart_items')->where('cart_id', $user_cart->id)->delete();
          return $invoice;
        });

        DB::commit();
        return response()->json(['status_code' => 200, 'message' => 'Invoice Issued', 'invoice' => $invoice->with('items')->get(), 'total' => $invoice->getTotal()])->setStatusCode(200);
       
      }
      catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'CartController, Trying to create an invoice with the cart items'])->setStatusCode(500);  
      }
    }

    public function checkout(Request $request)
    {
      try {
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required|exists:invoices,id',
            'wallet_id' => 'required|exists:wallets,id',
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
        $invoice = Invoice::find($request->invoice_id);
        $invoice_total = $invoice->getTotal();
        if($invoice->paid) {
            return response()->json([
                'status_code' => 200,
                'message' => 'Invoice is Already Paid'])
              ->setStatusCode(200);
        }
        $wallet = Wallet::find($request->wallet_id);
        $wallet_balance = $wallet->getWalletBalance();
        if($invoice_total > $wallet_balance)
        return response()->json([
            'status_code' => 200, 
            'code' => 'INSUFFICIENT_BALANCE', 
            'message' => 'You dont have enough balance to pay for this invoice, You need '. $invoice_total - $wallet_balance. ' more LYDs'])
          ->setStatusCode(200);
        DB::transaction(function() use($wallet, $invoice){
          $transaction = new Transaction();
          $transaction->wallet_id = $wallet->id;
          $transaction->description = "Customer Paid for invoice NO ". $invoice->id ;
          $transaction->amount = $invoice->getTotal();
          $transaction->save();
          $invoice->paid = true;
          $invoice->save();
        });

        DB::commit();
        return response()->json([
          'status_code' => 200, 
          'message' => 'Invoice Paid', 
          'wallet_balance' => $wallet->getWalletBalance()])
        ->setStatusCode(200);
       
      }
      catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'InvoiceController, Trying to pay for invoice'])->setStatusCode(500);  
      }
    }
}
