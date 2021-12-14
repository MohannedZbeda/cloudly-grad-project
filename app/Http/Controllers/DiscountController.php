<?php

namespace App\Http\Controllers;

use App\Http\Resources\DiscountResource;
use App\Http\Resources\PackageResource;
use App\Http\Resources\ProductResource;
use App\Models\Discount;
use App\Models\Package;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Error;

class DiscountController extends Controller
{
  public function index()
  {
    try {
      $discounts = DiscountResource::collection(Discount::with(['products', 'packages'])->get());
      return response()->json(['status_code' => 200, 'discounts' => $discounts])->setStatusCode(200);
  }
  catch(Error $error) {
    return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'DiscountController, Trying to get all discounts'])->setStatusCode(500);  
  } 
}
  
  public function getDiscount($id)
  {
    try {
      $discount = new DiscountResource(Discount::with(['products', 'packages'])->find($id));
      return response()->json(['status_code' => 200, 'discount' => $discount])->setStatusCode(200);
    } catch (Error $error) {
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'DiscountController, Trying to get a discount for update'])->setStatusCode(500);  
      
    }
      
  }

  public function getItems()
  {
     try {
      $products = ProductResource::collection(Product::all());
      $packages = PackageResource::collection(Package::all());
      return response()->json(['status_code' => 200, 'packages' => $packages, 'products' => $products])->setStatusCode(200); 
     } catch (Error $error) {
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'DiscountController, Trying to get a items for discount creation'])->setStatusCode(500);  
      
    }
      
  }  

  public function store(Request $request)
    {
      try {
        $validator = Validator::make($request->all(), [
            'products' => 'required_without:packages|nullable|exists:products,id',
            'packages' => 'required_without:products|nullable|exists:packages,id',
            'end_date' => 'required|date|after:yesterday',
            'discount_amount' => 'required_without:discount_percentage|nullable|numeric',
            'discount_percentage' => 'required_without:discount_amount|nullable|numeric|between:1,100'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
        $discount = DB::transaction(function() use($request) {
          $discount = new Discount();
          $discount->discount_amount = $request->discount_amount;
          $discount->discount_percentage = $request->discount_percentage;
          $discount->end_date = Carbon::parse($request->end_date);
          $discount->save();
          if($request->products)
            $discount->products()->attach($request->products);
          if($request->packages)
            $discount->packages()->attach($request->packages);
          return $discount;
        }); 
        DB::commit();
        return response()->json(['status_code' => 201, 'discount' => $discount])->setStatusCode(201);
    }
    catch(Error $error) {
      DB::rollBack();
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'CouponController, Trying to create a coupon'])->setStatusCode(500);  
    }
  }

    public function update(Request $request)
    {
      try {
        $validator = Validator::make($request->all(), [
            'products' => 'required_without:packages|nullable|exists:products,id',
            'packages' => 'required_without:products|nullable|exists:packages,id',
            'end_date' => 'required|date|after:yesterday',
            'discount_amount' => 'required_without:discount_percentage|nullable|numeric',
            'discount_percentage' => 'required_without:discount_amount|nullable|numeric|between:1,100'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
       $discount = DB::transaction(function() use($request) {
        $discount = Discount::find($request->id);
        $discount->discount_amount = $request->discount_amount;
        $discount->discount_percentage = $request->discount_percentage;
        $discount->end_date = Carbon::parse($request->end_date);
        $discount->save();
        if($request->products)
          $discount->products()->sync($request->products);
        if($request->packages)
          $discount->packages()->sync($request->packages);
        return $discount;
       });  
        DB::commit();
        return response()->json(['status_code' => 200, 'discount' => $discount])->setStatusCode(200);
    }
    catch(Error $error) {
      DB::rollBack();
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'CouponController, Trying to update a coupon'])->setStatusCode(500);  
    }
  }
}
