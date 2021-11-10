<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Resources\CategoryResource;
class ProductController extends Controller
{
    public function index()
    {
        $products = ProductResource::collection(Product::all());
        return response()->json(['status_code' => 200, 'products' => $products])->setStatusCode(200);
    }

    public function getCategories()
    {
        $categories = CategoryResource::collection(Category::all());
        return response()->json(['status_code' => 200, 'categories' => $categories])->setStatusCode(200);
    }

    

    public function getProduct($id)
    {
        $product = new ProductResource(Product::find($id));
        return response()->json(['status_code' => 200, 'product' => $product])->setStatusCode(200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'ar_name' => 'required|unique:products,ar_name',
            'en_name' => 'required|unique:products,en_name'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity'])->setStatusCode(422);
    
        $product = new Product();
        $product->category_id = $request->category_id;
        $product->ar_name = $request->ar_name;
        $product->en_name = $request->en_name;
        $product->save();
        return response()->json(['status_code' => 201, 'product' => $product])->setStatusCode(201);
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
                
                ]
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity'])->setStatusCode(422);
    
        $product = Product::find($request->id);
        $product->category_id = $request->category_id;
        $product->ar_name = $request->ar_name;
        $product->en_name = $request->en_name;
        $product->save();
        return response()->json(['status_code' => 200, 'product' => $product])->setStatusCode(200);
    }
}
