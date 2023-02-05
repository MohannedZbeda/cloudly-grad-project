<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\InvoiceResource;
use Error;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Variant;
use App\Models\Wallet;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
  public function index()
  {
    try {
      $invoices = Invoice::with('items')->where('user_id', auth('sanctum')->user()->id)->get();
      return response()->json(['status_code' => 200, 'invoices' => InvoiceResource::collection($invoices)])->setStatusCode(200);
    } catch (Error $error) {
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'InvoiceController, Trying to get customer invoices'])->setStatusCode(200);
    }
  }

  public function issueInvoice(Request $request)
  {
    try {
      $user = User::with('info')->find(auth('sanctum')->user()->id);
      $cartItems = $user->cart->items;
      if (empty($cartItems->toArray()))
        return response()->json(['status_code' => 422, 'message' => 'Empty Cart'])->setStatusCode(422);

      $new_invoice = DB::transaction(function () use ($request, $cartItems, $user) {
        $invoice = new Invoice();
        $invoice->user_id = $user->id;
        $invoice->total = $user->cart->getTotal();
        $invoice->save();
        $invoice_items = [];
        // foreach (json_decode($request['attributes']) as $item) {
        foreach ($request['attributes'] as $item) {
          array_push($invoice_items, [
            'invoice_id' => $invoice->id,
            'cycle_id' => $item["cycle_id"],
            'name' => $cartItems->where('id', $item["item_id"])->first()->cartable->name,
            'price' => $cartItems->where('id', $item["item_id"])->first()->cartable->price
          ]);
        }
        DB::table('invoice_items')->insert($invoice_items);
        $invoice->total =  $invoice->getTotal();
        $invoice->save();
        DB::table('cart_items')->where('cart_id', $user->cart->id)->delete();


        $new_invoice = Invoice::with('items')->find($invoice->id);

        return $new_invoice;
      });

      DB::commit();
      Mail::to($user->email)->send(new \App\Mail\InvoiceIssued($new_invoice, $user));

      return response()->json(['status_code' => 200, 'message' => 'Invoice Issued', 'invoice' => new InvoiceResource($new_invoice), 'total' => $new_invoice->getTotal()])->setStatusCode(200);
    } catch (Error $error) {
      DB::rollBack();
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'invoiceController, Trying to create an invoice with the cart items'])->setStatusCode(500);
    }
  }

  public function checkout(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'invoice_id' => 'required|exists:invoices,id',
      ]);
      if ($validator->fails())
        return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
      $invoice = Invoice::find($request->invoice_id);
      $invoice_total = $invoice->getTotal();
      if ($invoice->paid) {
        return response()->json([
          'status_code' => 200,
          'message' => 'Invoice is Already Paid'
        ])
          ->setStatusCode(200);
      }
      $user = auth('sanctum')->user();
      $wallet = Wallet::where('user_id', $user->id)->first();
      $wallet_balance = $wallet->getWalletBalance();
      if ($invoice_total > $wallet_balance)
        return response()->json([
          'status_code' => 200,
          'code' => 'INSUFFICIENT_BALANCE',
          'message' => 'You dont have enough balance to pay for this invoice, You need ' . $invoice_total - $wallet_balance . ' more LYDs'
        ])
          ->setStatusCode(200);

      DB::transaction(function () use($invoice, $user,$wallet, $invoice_total) {
        $transaction = new Transaction();
        $transaction->description = "Paying for invoice";
        $transaction->wallet_id = $wallet->id;
        $transaction->amount = $invoice_total;
        $transaction->save();
        $invoice->paid = true;
        $invoice->save();
      
        foreach ($invoice->items as $item) {
          $sub = new Subscription();
          $sub->user_id = $user->id;
          $sub->name = $item->name;
          $sub->record = "تم الإشتراك\n";
          $sub->status = true;
          $sub->save();
        }
      });

      DB::commit();
      return response()->json([
        'status_code' => 200,
        'message' => 'Invoice Paid',
        'wallet_balance' => $wallet->getWalletBalance()
      ])
        ->setStatusCode(200);
    } catch (Error $error) {
      DB::rollBack();
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'InvoiceController, Trying to pay for invoice'])->setStatusCode(500);
    }
  }
}
