<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CustomAttributeResource;
use App\Models\CustomAttribute;
use Illuminate\Support\Facades\DB;
use Error;


class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = ProductResource::collection(Product::all());
            return response()->json(['status_code' => 200, 'products' => $products])->setStatusCode(200);
        } catch (Error $error) {
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'ProductController, Trying to get all products'])->setStatusCode(500);
        }
    }

    public function getCategories()
    {
        try {
            $categories = CategoryResource::collection(Category::all());
            return response()->json(['status_code' => 200, 'categories' => $categories])->setStatusCode(200);
        } catch (Error $error) {
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'ProductController, Trying to get categories for product creation'])->setStatusCode(500);
        }
    }

    
    public function getProduct($id)
    {
        try {
            $product = new ProductResource(Product::with(['cycles'])->find($id));
            return response()->json(['status_code' => 200, 'product' => $product])->setStatusCode(200);
        } catch (Error $error) {
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'ProductController, Trying to get a product for update'])->setStatusCode(500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|unique:products,name',
                'cycles' => 'required|array|exists:subscribtion_cycles,id'
            ], [
                'category_id.required' => ['ar' => '???????? ?????????? ?????????? ?????????? ?????????? ?????????? ????????????', 'en' => 'Please specify category products'],
                'name.required' => ['ar' => '???????? ?????????? ?????? ????????????', 'en' => 'Please enter product name'],
                'name.unique' => ['ar' => '?????? ?????????? ????????????', 'en' => 'This name is taken'],
                'advanced.required' => ['ar'=> '???????? ?????????? ???? ?????? ???????? ?????????????? ???????????? ???? ????', 'en' => 'Please specify attribute type'],
                'cycles.required' => ['ar'=> '???????? ?????????? ???????? ?????? ?????????? ?????? ??????????', 'en' => 'Please select at least one payment cycle']
              ]);
            if ($validator->fails())
                return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
            $product = DB::transaction(function () use ($request) {
                $product = new Product();
                $product->category_id = $request->category_id;
                $product->name = $request->name;
                $product->save();
                $product->addMediaFromBase64($request->base64image)->toMediaCollection();
            
                $product->cycles()->attach($request->cycles);
                return $product;
            });

            DB::commit();
            return response()->json(['status_code' => 201, 'product' => new ProductResource($product)])->setStatusCode(201);
        } catch (Error $error) {
            DB::rollBack();
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'ProductController, Trying to create a Product'])->setStatusCode(500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'category_id' => 'required|exists:categories,id',
                'name' => [
                    'required',
                    Rule::unique('products', 'name')->ignore($request->id)

                ],
                'cycles' => 'required|array|exists:subscribtion_cycles,id'

            ], [
                'category_id.required' => ['ar' => '???????? ?????????? ?????????? ?????????? ?????????? ?????????? ????????????', 'en' => 'Please specify category products'],
                'name.required' => ['ar' => '???????? ?????????? ?????? ????????????', 'en' => 'Please enter product name'],
                'name.unique' => ['ar' => '?????? ?????????? ????????????', 'en' => 'This name is taken'],
                'advanced.required' => ['ar'=> '???????? ?????????? ???? ?????? ???????? ?????????????? ???????????? ???? ????', 'en' => 'Please specify attribute type'],
                'cycles.required' => ['ar'=> '???????? ?????????? ???????? ?????? ?????????? ?????? ??????????', 'en' => 'Please select at least one payment cycle']
              ]);
            if ($validator->fails())
                return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
            $product = DB::transaction(function () use ($request) {
                $product = Product::find($request->id);
                $product->category_id = $request->category_id;
                $product->name = $request->name;
                $product->save();
                if($request->base64image) {
                $product->clearMediaCollection();
                $product->addMediaFromBase64($request->base64image)->toMediaCollection();
                }

                $product->cycles()->sync($request->cycles);
                return $product;
            });
            DB::commit();
            return response()->json(['status_code' => 200, 'product' => $product])->setStatusCode(200);
        } catch (Error $error) {
            DB::rollBack();
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'ProductController, Trying to update a Product'])->setStatusCode(500);
        }
    }
}
