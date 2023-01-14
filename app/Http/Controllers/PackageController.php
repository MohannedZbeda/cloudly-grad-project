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
        $packages = PackageResource::collection(Package::with(['products', 'cycles'])->get());
        return response()->json(['status_code' => 200, 'packages' => $packages])->setStatusCode(200);
    }
      catch(Error $error) {
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'PackageController, Trying to get all packages'])->setStatusCode(500);  
      
      }  
}

    public function getPackage($id)
    {
        try {
        $package = new PackageResource(Package::with(['products', 'cycles'])->find($id));
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
            'name' => 'required|unique:packages,name',
            'price' => 'required|numeric',
            'products' => 'required|array|exists:products,id',  
            'cycles' => 'required|array|exists:subscribtion_cycles,id'  
        ], [
          'name.required' => ['ar' => 'يرجى إدخال إسم للباقة', 'en' => 'Please enter package name'],
          'name.unique' => ['ar' => 'هذا الإسم مستعمل', 'en' => 'This name is taken'],
          'price.required' => ['ar' => 'يرجى إدخال سعر الباقة', 'en' => 'Please enter package price'],
          'products.required' => ['ar'=> 'يرحى تحديد على الأفل منتج واحد للباقة', 'en' => 'Please specify at least one product'],
          'cycles.required' => ['ar'=> 'يرحى تحديد دورة دفع واحدة على الأقل', 'en' => 'Please select at least one payment cycle']
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
      $package = DB::transaction(function() use($request) {
        $package = new Package();
        $package->name = $request->name;
        $package->price = $request->price;
        $package->save();
        $package->products()->sync($request->products);
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
            'name' => [
              'required',
              Rule::unique('packages', 'name')->ignore($request->id)],
            'price' => 'required|numeric',
            'products' => 'required|array|exists:products,id',  
            'cycles' => 'required|array|exists:subscribtion_cycles,id'  
        ], [
          'name.required' => ['ar' => 'يرجى إدخال إسم للباقة', 'en' => 'Please enter package name'],
          'name.unique' => ['ar' => 'هذا الإسم مستعمل', 'en' => 'This name is taken'],
          'price.required' => ['ar' => 'يرجى إدخال سعر الباقة', 'en' => 'Please enter package price'],
          'products.required' => ['ar'=> 'يرحى تحديد على الأفل منتج واحد للباقة', 'en' => 'Please specify at least one product'],
          'cycles.required' => ['ar'=> 'يرحى تحديد دورة دفع واحدة على الأقل', 'en' => 'Please select at least one payment cycle']
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
       $package = DB::transaction(function() use($request) {
        $package = Package::find($request->id);
        $package->name = $request->name;
        $package->price = $request->price;
        $package->save();
        $package->products()->sync($request->products);
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

     
}
