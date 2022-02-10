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
use App\Models\CustomAttribute;
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
        $custom_attributes = CustomAttributeResource::collection($product->customAttributes);
        $variants = VariantResource::collection(Variant::with(['values'])->where('product_id',$product_id)->where('customized', false)->get());
        $cycles = CycleResource::collection($product->cycles);
        return response()->json(['status_code' => 200, 'variants' => $variants, 'custom_attributes' => $custom_attributes, 'cycles' => $cycles])->setStatusCode(200);
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

public function addDiscount(Request $request)
    {
        try {
        $validator = Validator::make($request->all(), [
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'variant_id' => 'required|exists:variants,id'
        ]);
        if($validator->fails()) 
            return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);    
        $variant = Variant::find($request->variant_id);
        DB::transaction(function() use($variant, $request) {
        $variant->discount_percentage = $request->discount_percentage;
        $variant->save();
        });
        DB::commit();
        $variants = VariantResource::collection(Variant::all());
        return response()->json(['status_code' => 200, 'variants' => $variants])->setStatusCode(200);
    }  
    catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'VariantController, Trying to add a discount'])->setStatusCode(500);  
      
      }  
}

    public function removeDiscount(Request $request)
    {
        try {
        $variant = Variant::find($request->variant_id);
        DB::transaction(function () use($variant){
            $variant->discount_percentage = null;
            $variant->save();
        });
        DB::commit();
        $variants = VariantResource::collection(Variant::all());
        return response()->json(['status_code' => 200, 'variants' => $variants])->setStatusCode(200);
    }  catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'VariantController, Trying to remove a discount'])->setStatusCode(500);  
      
      }   
}

    public function addVouchers(Request $request)
    {
      try {
        $vouchers = [];
        for ($i=0; $i <$request->quantity; $i++) { 
         array_push($vouchers, [
             'code' => 'PRVA-'.substr(str_shuffle("0123456789"), 0, 7),
             'voucherable_id' => $request->variant_id,
             'voucherable_type' => Variant::class,
              'created_at' => Carbon::now(),
              'updated_at' => Carbon::now()
         ]);
        }
        DB::transaction(function() use($vouchers) {
            Voucher::insert($vouchers);
        });
        DB::commit();
        $variants = VariantResource::collection(Variant::all());
        return response()->json(['status_code' => 200, 'variants' => $variants])->setStatusCode(200);
    }  catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'VariantController, Trying to generate vouchers'])->setStatusCode(500);  
      
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
            'ar_name' => 'required|unique:variants,ar_name',
            'en_name' => 'required|unique:variants,en_name',
            'price' => 'required|numeric',
            'attributes' => 'required|array',
            'attributes.*.value_id' => 'required|exists:values,id',
            'attributes.*.id' => 'required|exists:attributes,id'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
        $variant = DB::transaction(function () use($request) {
            $variant = new Variant();
            $variant->product_id = $request->product_id;
            $variant->ar_name = $request->ar_name;
            $variant->en_name = $request->en_name;
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
            'ar_name' => [
                'required',
                'string',
                Rule::unique('variants', 'ar_name')->ignore($request->id)
            ],
            'en_name' => [
                'required',
                'string',
                Rule::unique('variants', 'en_name')->ignore($request->id)
            ],
            'price' => 'required|numeric',
            'attributes' => 'required|array',
            'attributes.*.value_id' => 'required|exists:values,id',
            'attributes.*.id' => 'required|exists:attributes,id',

        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
        $variant = DB::transaction(function() use($request) {
            $variant = Variant::find($request->id);
            $variant->product_id = $request->product_id;
            $variant->ar_name = $request->ar_name;
            $variant->en_name = $request->en_name;
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
