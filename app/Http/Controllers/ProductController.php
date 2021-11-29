<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttributeResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Resources\CategoryResource;
use App\Models\Attribute;
use App\Models\ProductValue;

class ProductController extends Controller
{
    public function index()
    {
        $products = ProductResource::collection(Product::with(['values', 'discounts'])->get());
        return response()->json(['status_code' => 200, 'products' => $products])->setStatusCode(200);
    }

    public function getCategories()
    {
        $categories = CategoryResource::collection(Category::all());
        return response()->json(['status_code' => 200, 'categories' => $categories])->setStatusCode(200);
    }

    public function getAttributes(Request $request)
    {
        $categories = AttributeResource::collection(Attribute::where('category_id', $request->id)->get());
        return response()->json(['status_code' => 200, 'attributes' => $categories])->setStatusCode(200);
    }

    public function removeDiscount(Request $request)
    {
        $product = Product::find($request->product_id);
        $product->discounts()->detach($request->discount_id);
        $products = ProductResource::collection(Product::all());
        return response()->json(['status_code' => 200, 'products' => $products])->setStatusCode(200);
    }

    

    public function getProduct($id)
    {
        $product = new ProductResource(Product::with('values')->find($id));
        return response()->json(['status_code' => 200, 'product' => $product])->setStatusCode(200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'ar_name' => 'required|unique:products,ar_name',
            'en_name' => 'required|unique:products,en_name',
            'price' => 'required|numeric',
            'attributes' => 'required|array',
            'attributes.*.value' => 'required',
            'attributes.*.id' => 'required|exists:attributes,id'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
         
        $product = new Product();
        $product->category_id = $request->category_id;
        $product->ar_name = $request->ar_name;
        $product->en_name = $request->en_name;
        $product->price = $request->price;
        $product->save();
        foreach($request['attributes'] as $attribute) {
          $value = new ProductValue();
          $value->product_id = $product->id;
          $value->attribute_id = $attribute['id'];
          $value->value = $attribute['value'];
          $value->save();
        }
        return response()->json(['status_code' => 201, 'product' => new ProductResource($product)])->setStatusCode(201);
    }

    public function update(Request $request)
    {
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
            'price' => 'required|numeric',
            'attributes' => 'required|array',
            'attributes.*.value' => 'required',
            'attributes.*.id' => 'required|exists:attributes,id'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
    
        $product = Product::find($request->id);
        $product->category_id = $request->category_id;
        $product->ar_name = $request->ar_name;
        $product->en_name = $request->en_name;
        $product->price = $request->price;
        $product->save();
        foreach($request['attributes'] as $attribute) {
          $value = ProductValue::where('product_id', $product->id)->where('attribute_id', $attribute['id'])->first();
          $value->value = $attribute['value'];
          $value->save();
        }
        return response()->json(['status_code' => 200, 'product' => $product])->setStatusCode(200);
    }
}
