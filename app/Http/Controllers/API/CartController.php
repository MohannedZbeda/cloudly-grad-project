<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\CartResource;
use App\Models\CartItem;
use App\Models\Package;
use App\Models\ProductValue;
use App\Models\Variant;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
  function addToCart($id, $type)
  {
    try {
      DB::transaction(function () use ($id, $type) {
        $cart = auth('sanctum')->user()->cart;
        $cartItem = new CartItem();
        $cartItem->cart_id = $cart->id;
        $cartItem->cartable_id =  $id;
        $cartItem->cartable_type = $type;
        $cartItem->save();
      });
      DB::commit();
      return response()->json(['status_code' => 200, 'message' => 'Added To cart'])->setStatusCode(200);
    } catch (Error $error) {
      DB::rollBack();
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'Cart Controller, Trying to add an item to the cart'])->setStatusCode(500);
    }
  }


  function addPackage(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'id' => 'required|exists:packages,id'
    ]);
    if ($validator->fails())
      return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
    return $this->addToCart($request->id, 'App\Models\Package');
  }


  function addProduct(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'id' => 'required|exists:products,id'
    ]);
    if ($validator->fails())
      return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
    return $this->addToCart($request->id, 'App\Models\Product');
  }

  function removeFromCart(Request $request)
  {
    try {
      DB::transaction(function () use ($request) {
        CartItem::destroy($request->id);
      });
      DB::commit();
      return response()->json(['status_code' => 200, 'message' => 'Removed From cart'])->setStatusCode(200);
    } catch (Error $error) {
      DB::rollBack();
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'CartController, Trying to remove an item from the cart'])->setStatusCode(500);
    }
  }


  public function getCartItems()
  {
    try {
      $cart = auth('sanctum')->user()->cart;
      $items = CartResource::collection($cart->items);
      return response()->json(['status_code' => 200, 'items' => $items, 'total' => floatval($cart->getTotal())])->setStatusCode(200);
    } catch (Error $error) {
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'CartController, Trying to get cart items'])->setStatusCode(500);
    }
  }

}
