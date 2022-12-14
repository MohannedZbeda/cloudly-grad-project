<?php

namespace App\Http\Controllers;

use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use Carbon\Carbon;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function index()
    {
    try {
     $coupons = CouponResource::collection(Coupon::all()->unique('start_date')->unique('end_date'));   
     return response()->json(['status_code' => 200, 'coupons' => $coupons])->setStatusCode(200);

    }
    catch(Error $error) {
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'CouponController, Trying to get coupons'])->setStatusCode(500);  
    }
  }

    public function getCoupon(Request $request)
    {
     try {
     $coupon = new CouponResource(Coupon::where('start_date', $request->start_date)
        ->where('end_date', $request->end_date)->first());   
     return response()->json(['status_code' => 200, 'coupon' => $coupon])->setStatusCode(200);
    }
    catch(Error $error) {
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'CouponController, Trying to get a coupon for update'])->setStatusCode(500);  
    }
    }

    public function store(Request $request)
    {
      try {
        $validator = Validator::make($request->all(), [
            'end_date' => 'required|date|after:yesterday',
            'start_date' => 'required|date|after:yesterday',
            'discount_percentage' => 'required|numeric|between:1,100',
            'amount' => 'required|numeric'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
        $coupons = []; 
        for ($i=0; $i < $request->amount; $i++) { 
            array_push($coupons, 
            [
              'start_date' => $request->start_date,
              'end_date' => $request->end_date,
              'discount_percentage' => $request->discount_percentage,
              'code' => 'TSDC-'.substr(str_shuffle("0123456789"), 0, 5),
              'created_at' => Carbon::now(),
              'updated_at' => Carbon::now()
            
            ]);
        }
        DB::transaction(function() use($coupons){
          Coupon::insert($coupons);  
        }, 3);
        DB::commit();
        return response()->json(['status_code' => 201])->setStatusCode(201);
    } 
    catch(Error $error) {
      DB::rollBack();
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'CouponController, Trying to get a coupon for update'])->setStatusCode(500);  
    }
  }

    public function update(Request $request)
    {
      try {
        $validator = Validator::make($request->all(), [
            'old_end_date' => 'required|date',
            'end_date' => 'required|date|after:yesterday',
            'old_start_date' => 'required|date',
            'start_date' => 'required|date|after:yesterday',
            'discount_percentage' => 'required|numeric|between:1,100',
            'old_discount_percentage' => 'required|numeric|between:1,100'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
          DB::transaction(function() use($request) {
            DB::table('coupons')
            ->where('start_date', $request->old_start_date)
            ->where('end_date', $request->old_end_date)
            ->where('discount_percentage', $request->old_discount_percentage)
            ->update([
              'start_date' => $request->start_date,
              'end_date' => $request->end_date, 
              'discount_percentage' => $request->discount_percentage
            ]);
          });
          
        DB::commit();
        return response()->json(['status_code' => 200])->setStatusCode(200);
    } 
    catch(Error $error) {
      DB::rollBack();
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'CouponController, Trying to get a coupon for update'])->setStatusCode(500);  
    }
  }
}


