<?php

namespace App\Http\Controllers;

use App\Http\Resources\PackageResource;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class PackageController extends Controller
{
    public function index()
    {
        $packages = PackageResource::collection(Package::with(['products', 'discounts'])->get());
        return response()->json(['status_code' => 200, 'packages' => $packages])->setStatusCode(200);
    }

    public function getPackage($id)
    {
        $package = new PackageResource(Package::with('products')->find($id));
        return response()->json(['status_code' => 200, 'package' => $package])->setStatusCode(200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ar_name' => 'required|unique:packages,ar_name',
            'en_name' => 'required|unique:packages,en_name',
            'price' => 'required|numeric',
            'products' => 'required|array|exists:products,id'  
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
    
       $package = new Package();
       $package->ar_name = $request->ar_name;
       $package->en_name = $request->en_name;
       $package->price = $request->price;
       $package->save();
       $package->products()->sync($request->products);

       return response()->json(['status_code' => 201, 'message' => 'Package Created', 'package' => new PackageResource($package)])->setStatusCode(201);


    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ar_name' => [
              'required',
              Rule::unique('packages', 'ar_name')->ignore($request->id)],
            'en_name' => [
              'required',
              Rule::unique('packages', 'en_name')->ignore($request->id)],
            'price' => 'required|numeric',
            'products' => 'required|array|exists:products,id'  
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
    
       $package = Package::find($request->id);
       $package->ar_name = $request->ar_name;
       $package->en_name = $request->en_name;
       $package->price = $request->price;
       $package->save();
       $package->products()->sync($request->products);

       return response()->json(['status_code' => 200, 'message' => 'Package Updated', 'package' => new PackageResource($package)])->setStatusCode(200);


    }

    public function removeDiscount(Request $request)
    {
        $package = Package::find($request->package_id);
        $package->discounts()->detach($request->discount_id);
        $packages = PackageResource::collection(Package::all());
        return response()->json(['status_code' => 200, 'packages' => $packages])->setStatusCode(200);
    }

    
}
