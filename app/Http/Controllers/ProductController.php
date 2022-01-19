<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\DB;
use Error;


class ProductController extends Controller
{
    public function index()
    {
    try {
        $products = ProductResource::collection(Product::all());
        return response()->json(['status_code' => 200, 'products' => $products])->setStatusCode(200);
    }
    catch(Error $error) {
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'ProductController, Trying to get all products'])->setStatusCode(500);  
      
      }    
}

    public function getCategories()
    {
        try {
        $categories = CategoryResource::collection(Category::all());
        return response()->json(['status_code' => 200, 'categories' => $categories])->setStatusCode(200);
    } 
    catch(Error $error) {
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'ProductController, Trying to get categories for product creation'])->setStatusCode(500);  
      
      }  
}

//     public function getAttributes(Request $request)
//     {
//         try {
//         $categories = AttributeResource::collection(Attribute::where('category_id', $request->id)->get());
//         return response()->json(['status_code' => 200, 'attributes' => $categories])->setStatusCode(200);
//     }  catch(Error $error) {
//         return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'ProductController, Trying to get attributes for product creation'])->setStatusCode(500);  
      
//       }   
// }

//     public function removeDiscount(Request $request)
//     {
//         try {
//         $product = Product::find($request->product_id);
//         $product->discounts()->detach($request->discount_id);
//         $products = ProductResource::collection(Product::all());
//         return response()->json(['status_code' => 200, 'products' => $products])->setStatusCode(200);
//     }  catch(Error $error) {
//         return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'ProductController, Trying to remove a discount'])->setStatusCode(500);  
      
//       }   
// }

//     public function addVouchers(Request $request)
//     {
//       try {
//         $vouchers = [];
//         for ($i=0; $i <$request->quantity; $i++) { 
//          array_push($vouchers, [
//              'code' => 'PR-'.substr(str_shuffle("0123456789"), 0, 7),
//              'voucherable_id' => $request->product_id,
//              'voucherable_type' => Product::class,
//               'created_at' => Carbon::now(),
//               'updated_at' => Carbon::now()
//          ]);
//         }
//         DB::transaction(function() use($vouchers) {
//             Voucher::insert($vouchers);
//         });
//         DB::commit();
//         $products = ProductResource::collection(Product::all());
//         return response()->json(['status_code' => 200, 'products' => $products])->setStatusCode(200);
//     }  catch(Error $error) {
//         DB::rollBack();
//         return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'ProductController, Trying to generate vouchers'])->setStatusCode(500);  
      
//       }   

// }


    public function getProduct($id)
    {
        try {
        $product = new ProductResource(Product::find($id));
        return response()->json(['status_code' => 200, 'product' => $product])->setStatusCode(200);
    }  catch(Error $error) {
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'ProductController, Trying to get a product for update'])->setStatusCode(500);  
      }   
}

    public function store(Request $request)
    {
        try {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'ar_name' => 'required|unique:products,ar_name',
            'en_name' => 'required|unique:products,en_name',
            'customizable' => 'required|boolean',
            'custom_attributes' => 'required|array',
            'custom_attributes.*.custom_price' => 'required|numeric',
            'custom_attributes.*.unit_min' => 'required|numeric|min:1',
            'custom_attributes.*.unit_max' => 'required|numeric|min:1',

        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
        $product = DB::transaction(function () use($request) {
            $product = new Product();
            $product->category_id = $request->category_id;
            $product->ar_name = $request->ar_name;
            $product->en_name = $request->en_name;
            $product->customizable = $request->customizable;
            $product->save();
            $product->customAttributes()->create($request->custom_attributes);
            return $product;    
        });    
        DB::commit();
        return response()->json(['status_code' => 201, 'product' => new ProductResource($product)])->setStatusCode(201);
    }  catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'ProductController, Trying to create a Product'])->setStatusCode(500);  
      }   
}

    public function update(Request $request)
    {
        try {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'ar_name' => [
              'required',
              Rule::unique('products', 'ar_name')->ignore($request->id)
              
            ],
            'en_name' => [
                'required',
                Rule::unique('products', 'en_name')->ignore($request->id)
                
            ],
            'customizable' => 'required|boolean',
            'custom_attributes' => 'required|array',
            'custom_attributes.*.custom_price' => 'required|numeric',
            'custom_attributes.*.unit_min' => 'required|numeric|min:1',
            'custom_attributes.*.unit_max' => 'required|numeric|min:1'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
        $product = DB::transaction(function() use($request) {
            $product = Product::find($request->id);
            $product->category_id = $request->category_id;
            $product->ar_name = $request->ar_name;
            $product->en_name = $request->en_name;
            $product->customizable = $request->customizable;
            $product->save();
            $product->customAttributes()->update($request->custom_attributes);
            return $product;
        });
        DB::commit();
        return response()->json(['status_code' => 200, 'product' => $product])->setStatusCode(200);
    } catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'ProductController, Trying to update a Product'])->setStatusCode(500);  
      }    
}
}
