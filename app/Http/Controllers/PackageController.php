<?php

namespace App\Http\Controllers;

use App\Http\Resources\PackageResource;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Error;
class PackageController extends Controller
{
    public function index()
    {
        try {
        $packages = PackageResource::collection(Package::with(['variants', 'cycles'])->get());
        return response()->json(['status_code' => 200, 'packages' => $packages])->setStatusCode(200);
    }
      catch(Error $error) {
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'PackageController, Trying to get all packages'])->setStatusCode(500);  
      
      }  
}

    public function getPackage($id)
    {
        try {
        $package = new PackageResource(Package::with(['variants', 'cycles'])->find($id));
        return response()->json(['status_code' => 200, 'package' => $package])->setStatusCode(200);
    }  
    catch(Error $error) {
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'PackageController, Trying to get a package for update'])->setStatusCode(500);  
      
      }  
} 

    public function store(Request $request)
    { 
        try {
        $validator = Validator::make($request->all(), [
            'ar_name' => 'required|unique:packages,ar_name',
            'en_name' => 'required|unique:packages,en_name',
            'price' => 'required|numeric',
            'variants' => 'required|array|exists:variants,id',  
            'cycles' => 'required|array|exists:subscribtion_cycles,id'  
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
      $package = DB::transaction(function() use($request) {
        $package = new Package();
        $package->ar_name = $request->ar_name;
        $package->en_name = $request->en_name;
        $package->price = $request->price;
        $package->save();
        $package->variants()->sync($request->variants);
        $package->cycles()->sync($request->cycles);
        return $package;
      });
       DB::commit();
       return response()->json(['status_code' => 201, 'message' => 'Package Created', 'package' => new PackageResource($package)])->setStatusCode(201);


    }
    catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'PackageController, Trying to create a package'])->setStatusCode(500);  
      
      }  
 }

    public function update(Request $request)
    {
        try {
        $validator = Validator::make($request->all(), [
            'ar_name' => [
              'required',
              Rule::unique('packages', 'ar_name')->ignore($request->id)],
            'en_name' => [
              'required',
              Rule::unique('packages', 'en_name')->ignore($request->id)],
            'price' => 'required|numeric',
            'variants' => 'required|array|exists:variants,id',  
            'cycles' => 'required|array|exists:subscribtion_cycles,id'  
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
       $package = DB::transaction(function() use($request) {
        $package = Package::find($request->id);
        $package->ar_name = $request->ar_name;
        $package->en_name = $request->en_name;
        $package->price = $request->price;
        $package->save();
        $package->variants()->sync($request->variants);
        $package->cycles()->sync($request->cycles);
        return $package;
       });
       DB::commit();
       return response()->json(['status_code' => 200, 'message' => 'Package Updated', 'package' => new PackageResource($package)])->setStatusCode(200);
    }
    catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'PackageController, Trying to update a package'])->setStatusCode(500);  
      
      }   
    }

    public function addDiscount(Request $request)
    {
        try {
        $validator = Validator::make($request->all(), [
          'discount_percentage' => 'required|numeric|min:0|max:100',
          'package_id' => 'required|exists:packages,id'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
        $package = Package::find($request->package_id);
        DB::transaction(function() use($package, $request) {
        $package->discount_percentage = $request->discount_percentage;
        $package->save();
        });
        DB::commit();
        $packages = PackageResource::collection(Package::all());
        return response()->json(['status_code' => 200, 'packages' => $packages])->setStatusCode(200);
    }  
    catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'PackageController, Trying to add a discount'])->setStatusCode(500);  
      
      }  
}

    public function removeDiscount(Request $request)
    {
        try {
        $package = Package::find($request->package_id);
        DB::transaction(function () use($package) {
          $package->discount_percentage = null;
          $package->save();
        });
        DB::commit();
        $packages = PackageResource::collection(Package::all());
        return response()->json(['status_code' => 200, 'packages' => $packages])->setStatusCode(200);
    }  
    catch(Error $error) {
      DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'PackageController, Trying to remove a discount'])->setStatusCode(500);  
      
      }  
}

    public function addVouchers(Request $request)
    {
        try {
       $vouchers = [];
        for ($i=0; $i <$request->quantity; $i++) { 
         array_push($vouchers, [
             'code' => 'PA-'.substr(str_shuffle("0123456789"), 0, 7),
             'voucherable_id' => $request->package_id,
             'voucherable_type' => Package::class,
             'created_at' => Carbon::now(),
             'updated_at' => Carbon::now()
         ]);
        }
        DB::transaction(function() use($vouchers) {
            Voucher::insert($vouchers);
        });
        DB::commit();
        $packages = PackageResource::collection(Package::all());
        return response()->json(['status_code' => 200, 'packages' => $packages])->setStatusCode(200);
    } 
    catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'PackageController, Trying to add vouchers'])->setStatusCode(500);  
      
      }   
}

    
}
