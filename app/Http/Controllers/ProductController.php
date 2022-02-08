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
            $products = ProductResource::collection(Product::with('customAttributes')->get());
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

    public function getCustomAttributes($id)
    {
        try {
            $attribute = new CustomAttributeResource(CustomAttribute::find($id));
            return response()->json(['status_code' => 200, 'attribute' => $attribute])->setStatusCode(200);
        } catch (Error $error) {
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'ProductController, Trying to get custom attribute for update creation'])->setStatusCode(500);
        }
    }

    public function updateCustomAttribute(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:custom_attributes,id',
                'custom_price' => 'required|numeric',
                'unit_max' => 'required|numeric|gte:unit_min',
                'unit_min' => 'required|numeric|lte:unit_max'
            ]);
            if ($validator->fails())
                return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
            DB::transaction(function () use ($request) {
                $attribute = CustomAttribute::find($request->id);
                $attribute->custom_price = $request->custom_price;
                $attribute->unit_max = $request->unit_max;
                $attribute->unit_min = $request->unit_min;
                $attribute->save();
                return $attribute;
            });

            DB::commit();
            return response()->json(['status_code' => 200])->setStatusCode(200);
        } catch (Error $error) {
            DB::rollBack();
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'ProductController, Trying to create a Product'])->setStatusCode(500);
        }
    }
    public function getProduct($id)
    {
        try {
            $product = new ProductResource(Product::with('cycles')->find($id));
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
                'ar_name' => 'required|unique:products,ar_name',
                'en_name' => 'required|unique:products,en_name',
                'customizable' => 'required|boolean',
                'custom_attributes' => 'required_if:customizable,1|array',
                'custom_attributes.*.custom_price' => 'required_if:customizable,1|numeric',
                'custom_attributes.*.unit_min' => 'required_if:customizable,1|numeric|min:1',
                'custom_attributes.*.unit_max' => 'required_if:customizable,1|numeric|min:1',
                'cycles' => 'required|array|exists:subscribtion_cycles,id'


            ]);
            if ($validator->fails())
                return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
            $product = DB::transaction(function () use ($request) {
                $product = new Product();
                $product->category_id = $request->category_id;
                $product->ar_name = $request->ar_name;
                $product->en_name = $request->en_name;
                $product->customizable = $request->customizable;
                $product->save();
                if($request->custom_attributes) {
                $attributes = array_map(function ($attribute) use ($product) {
                    return [
                        'product_id' => $product->id,
                        'attribute_id' => $attribute['attribute_id'],
                        'custom_price' => $attribute['custom_price'],
                        'unit_max' => $attribute['unit_max'],
                        'unit_min' => $attribute['unit_min']

                    ];
                }, $request->custom_attributes);
                DB::table('custom_attributes')->insert($attributes);
            }
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
                'ar_name' => [
                    'required',
                    Rule::unique('products', 'ar_name')->ignore($request->id)

                ],
                'en_name' => [
                    'required',
                    Rule::unique('products', 'en_name')->ignore($request->id)

                ],
                'customizable' => 'required|boolean',
                'custom_attributes' => 'required_if:customizable,1|array',
                'custom_attributes.*.custom_price' => 'required_if:customizable,1|numeric',
                'custom_attributes.*.unit_min' => 'required_if:customizable,1|numeric|min:1',
                'custom_attributes.*.unit_max' => 'required_if:customizable,1|numeric|min:1',
                'cycles' => 'required|array|exists:subscribtion_cycles,id'

            ]);
            if ($validator->fails())
                return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
            $product = DB::transaction(function () use ($request) {
                $product = Product::find($request->id);
                $wasCustom = $product->customizable;
                $product->category_id = $request->category_id;
                $product->ar_name = $request->ar_name;
                $product->en_name = $request->en_name;
                $product->customizable = $request->customizable;
                $product->save();
                if(!$wasCustom && $request->custom_attributes) {
                    $attributes = array_map(function ($attribute) use ($product) {
                        return [
                            'product_id' => $product->id,
                            'attribute_id' => $attribute['attribute_id'],
                            'custom_price' => $attribute['custom_price'],
                            'unit_max' => $attribute['unit_max'],
                            'unit_min' => $attribute['unit_min']
    
                        ];
                    }, $request->custom_attributes);
                    DB::table('custom_attributes')->insert($attributes);
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
