<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttributeResource;
use App\Http\Resources\CustomAttributeResource;
use App\Http\Resources\CycleResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\VariantResource;
use App\Models\ProductValue;
use App\Models\Variant;
use Illuminate\Support\Carbon;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Error;
use Illuminate\Validation\Rule;
class VariantController extends Controller
{
    public function index($product_id)
    {
    try {
        $product = Product::find($product_id);
        $variants = VariantResource::collection(Variant::with(['values'])->where('product_id',$product_id)->get());
        $cycles = CycleResource::collection($product->cycles);
        return response()->json(['status_code' => 200, 'variants' => $variants, 'cycles' => $cycles])->setStatusCode(200);
    }
    catch(Error $error) {
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'VariantController, Trying to get all variants'])->setStatusCode(500);  
      
      }    
}

public function getVariants()
    {
    try {
        $variants = VariantResource::collection(Variant::all());
        return response()->json(['status_code' => 200, 'variants' => $variants])->setStatusCode(200);
    }
    catch(Error $error) {
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'VariantController, Trying to get all variants'])->setStatusCode(500);  
      
      }    
}

    public function getProducts()
    {
        try {
        $products = ProductResource::collection(Product::all());
        return response()->json(['status_code' => 200, 'products' => $products])->setStatusCode(200);
    } 
    catch(Error $error) {
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'VariantController, Trying to get products for variant creation'])->setStatusCode(500);  
      
      }  
}

    public function getAttributes(Request $request)
    {
        try {
        $product = Product::find($request->id);   
        $attributes = AttributeResource::collection($product->category->attributes()->with('values')->get());
        return response()->json(['status_code' => 200, 'attributes' => $attributes])->setStatusCode(200);
    }  catch(Error $error) {
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'VariantController, Trying to get attributes for variant creation'])->setStatusCode(500);  
      
      }   
}


    public function getVariant($id)
    {
        try {
        $variant = new VariantResource(Variant::with('values')->find($id));
        return response()->json(['status_code' => 200, 'variant' => $variant])->setStatusCode(200);
    }  catch(Error $error) {
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'VariantController, Trying to get a variant for update'])->setStatusCode(500);  
      }   
}

    public function store(Request $request)
    {
        try {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'name' => 'required|unique:variants,name',
            'price' => 'required|numeric',
            'attributes' => 'required|array',
            'attributes.*.value_id' => 'required|exists:values,id',
            'attributes.*.id' => 'required|exists:attributes,id'
        ], [
            'product_id.required' => ['ar' => 'يرجى تحديد المنتج اللتي ينتمي إليها هذا التفرع', 'en' => 'Please specify product variants'],
            'name.required' => ['ar' => 'يرجى إدخال إسم للمنتج', 'en' => 'Please enter product name'],
            'name.unique' => ['ar' => 'هذا الإسم مستعمل', 'en' => 'This name is taken'],
            'price.required' => ['ar'=> 'يرحى تحديد السعر', 'en' => 'Please specify price'],
            'attributes.required' => ['ar'=> 'يرحى تحديد دخاصية واحدة على الأقل', 'en' => 'Please select at least one attribute']
          ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
        $variant = DB::transaction(function () use($request) {
            $variant = new Variant();
            $variant->product_id = $request->product_id;
            $variant->name = $request->name;
            $variant->price = $request->price;
            $variant->save();
            foreach($request['attributes'] as $attribute) {
              $value = new ProductValue();
              $value->variant_id = $variant->id;
              $value->attribute_id = $attribute['id'];
              $value->value_id = $attribute['value_id'];
              $value->save();
            }
            return $variant;    
        });    
        DB::commit();
        return response()->json(['status_code' => 201, 'variant' => new VariantResource($variant)])->setStatusCode(201);
    }  catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'VariantController, Trying to create a variant'])->setStatusCode(500);  
      }   
}

    public function update(Request $request)
    {
        try {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'name' => [
                'required',
                'string',
                Rule::unique('variants', 'name')->ignore($request->id)
            ],
            'price' => 'required|numeric',
            'attributes' => 'required|array',
            'attributes.*.value_id' => 'required|exists:values,id',
            'attributes.*.id' => 'required|exists:attributes,id',

        ], [
            'product_id.required' => ['ar' => 'يرجى تحديد المنتج اللتي ينتمي إليها هذا التفرع', 'en' => 'Please specify product variants'],
            'name.required' => ['ar' => 'يرجى إدخال إسم للمنتج', 'en' => 'Please enter product name'],
            'name.unique' => ['ar' => 'هذا الإسم مستعمل', 'en' => 'This name is taken'],
            'price.required' => ['ar'=> 'يرحى تحديد السعر', 'en' => 'Please specify price'],
            'attributes.required' => ['ar'=> 'يرحى تحديد دخاصية واحدة على الأقل', 'en' => 'Please select at least one attribute']
          ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
        $variant = DB::transaction(function() use($request) {
            $variant = Variant::find($request->id);
            $variant->product_id = $request->product_id;
            $variant->name = $request->name;
            $variant->price = $request->price;
            $variant->save();
            foreach($request['attributes'] as $attribute) {
              $value = ProductValue::where('variant_id', $variant->id)->where('attribute_id', $attribute['id'])->first();
              $value->value_id = $attribute['value_id'];
              $value->save();
            }

            return $variant;
        });
        DB::commit();
        return response()->json(['status_code' => 200, 'variant' => $variant])->setStatusCode(200);
    } catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'VariantController, Trying to update a variant'])->setStatusCode(500);  
      }    
}
}
