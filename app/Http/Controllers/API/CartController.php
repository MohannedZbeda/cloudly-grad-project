<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\CartResource;
use App\Models\CartItem;
use App\Models\CustomAttribute;
use App\Models\Package;
use App\Models\Product;
use App\Models\ProductValue;
use App\Models\Variant;
use Attribute;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    function addToCart($id, $type)
    {
      try {
        DB::transaction(function() use($id, $type) {
          $cart = auth('sanctum')->user()->cart;
          $cartItem = new CartItem();
          $cartItem->cart_id = $cart->id;
          $cartItem->cartable_id =  $id;
          $cartItem->cartable_type = $type;
          $cartItem->save();
          $cart->total = $cart->getTotal();
          $cart->save();
          
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
      $validator = Validator::make($request->all(), [
        'id' => 'required|exists:packages,id'
    ]);
    if($validator->fails()) 
      return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
      return $this->addToCart($request->id, Package::class); 
    }

    function addCustomVariant(Request $request)
    {  
      $validator = Validator::make($request->all(), [
        'product_id' => 'required|exists:products,id',
        'attributes.*.attribute_id' => 'required|exists:attributes,id',
        'attributes.*.value' => 'required|numeric'
    ]);
    if($validator->fails()) 
      return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
      $variant = DB::transaction(function () use($request){
          $variant = new Variant();
          $variant->product_id = $request->product_id;
          $variant->customized = true;
        $variant->customized_by = auth('sanctum')->user()->name;
          $variant->price = Variant::getPrice($request->product_id, json_decode($request['attributes']));
          $variant->save();
          foreach(json_decode($request['attributes']) as $attribute) {
            $value = new ProductValue();
            $value->variant_id = $variant->id;
            $value->attribute_id = $attribute->attribute_id;
            $value->value = $attribute->value;
            $value->save();
          }
          return $variant;
      });
      return $this->addToCart($variant->id, Variant::class); 
    }

    function addProduct(Request $request)
    {  
      $validator = Validator::make($request->all(), [
        'id' => 'required|exists:variants,id'
    ]);
    if($validator->fails()) 
      return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
      return $this->addToCart($request->id, Variant::class); 
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
          return response()->json(['status_code' => 200, 'items' => $items, 'total' => $cart->total])->setStatusCode(200); 
        } 
        catch(Error $error) {
          return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'CartController, Trying to get cart items'])->setStatusCode(500);  
        } 
    }
    // public function updateQuantity(Request $request)
    // {
    //   try {
    //     $validator = Validator::make($request->all(), [
    //       'quantity' => 'required|numeric|min:1',
    //   ]);
    //   if($validator->fails()) 
    //     return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
    //    $item = DB::transaction(function() use($request) {
    //     $item = CartItem::find($request->id);        
    //     $item->quantity = $request->quantity;
    //     $item->save();
    //    });
    //    DB::commit();
    //    return response()->json(['status_code' => 200, 'item' => new CartResource($item)])->setStatusCode(200); 
    //   }
    //   catch(Error $error) {
    //     DB::rollBack();
    //     return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'CartController, Trying to update item quantity'])->setStatusCode(500);  
    //   }
    // }

    
}
