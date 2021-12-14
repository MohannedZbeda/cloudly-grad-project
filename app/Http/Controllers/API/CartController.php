<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\CartResource;
use App\Models\CartItem;
use App\Models\Package;
use App\Models\Product;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    function addToCart($id, $type, $quantity)
    {
      try {
        DB::transaction(function() use($id, $type, $quantity) {
          $cartID = auth('sanctum')->user()->cart->id;
          $cartItem = new CartItem();
          $cartItem->cart_id = $cartID;
          $cartItem->quantity = $quantity;
          $cartItem->cartable_id =  $id;
          $cartItem->cartable_type = $type;
          $cartItem->save();
        });
        DB::commit();
        return response()->json(['status_code' => 200, 'message' => 'Added To cart'])->setStatusCode(200);     
      }
     catch(Error $error) {
       DB::rollBack();
       return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'Cart Controller, Trying to add an item to the cart'])->setStatusCode(500);  

     }   
    }

    
    function addPackage(Request $request)
    {
      return $this->addToCart($request->id, Package::class, $request->quantity); 
    }

    function addProduct(Request $request)
    {  
      return $this->addToCart($request->id, Product::class, $request->quantity); 
    }

    function removeFromCart(Request $request)
    {
        try {
        DB::transaction(function() use($request) {
          CartItem::destroy($request->id);
        });  
        DB::commit();
        return response()->json(['status_code' => 200, 'message' => 'Removed From cart'])->setStatusCode(200); 
        
        }
        catch(Error $error) {
          DB::rollBack();
          return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'CartController, Trying to remove an item from the cart'])->setStatusCode(500);  
          
        }
      }


    public function getCartItems()
    {
        try {
          $cart = auth('sanctum')->user()->cart;
          $items = CartResource::collection($cart->items);
          return response()->json(['status_code' => 200, 'items' => $items, 'total' => $cart->getTotal()])->setStatusCode(200); 
        } 
        catch(Error $error) {
          return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'CartController, Trying to remove an item from the cart'])->setStatusCode(500);  
        } 
    }
    public function updateQuantity(Request $request)
    {
      try {
       $item = DB::transaction(function() use($request) {
        $item = CartItem::find($request->id);        
        $item->quantity = $request->quantity;
        $item->save();
       });
       DB::commit();
       return response()->json(['status_code' => 200, 'item' => new CartResource($item)])->setStatusCode(200); 
      }
      catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'CartController, Trying to update item quantity'])->setStatusCode(500);  
      }
    }

    
}
