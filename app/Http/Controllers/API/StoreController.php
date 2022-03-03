<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\PackageApiResource;
use App\Http\Resources\API\CategoryApiResource;
use App\Http\Resources\API\ProductApiResource;
use App\Models\Package;
use App\Models\Product;
use App\Models\Category;
use Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    public function index()
    {
        try {
            $packages = PackageApiResource::collection(Package::with(['variants', 'discounts'])->get());
            $categories = CategoryApiResource::collection(Category::with('products')->get());
            $products = ProductApiResource::collection(Product::with(['variants' => function ($q) {
                $q->where('customized', false);
            }])->get());
            return response()->json([
                'status_code' => 200,
                'categories' => $categories,
                'packages' => $packages,
                'products' => $products,
            ])->setStatusCode(200);
        } catch (Error $error) {
            DB::rollBack();
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'StoreController, Trying to get data to fill the store'])->setStatusCode(500);
        }
    }

    public function search(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'search_query' => 'required|string'
            ]);
            if ($validator->fails())
                return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
            $packages = PackageApiResource::collection(Package::with(['variants', 'discounts'])
                ->where('ar_name', 'LIKE', "%{$request->search_query}%")
                ->orWhere('en_name', 'LIKE', "%{$request->search_query}%")->get());
            $products = ProductApiResource::collection(Product::with(['variants' => function ($q) {
                $q->where('customized', false);
            }])->where('ar_name', 'LIKE', "%{$request->search_query}%")
                ->orWhere('en_name', 'LIKE', "%{$request->search_query}%")->get());
            $categories_products = ProductApiResource::collection(Category::where('ar_name', 'LIKE', "%{$request->search_query}%")
                ->orWhere('en_name', 'LIKE', "%{$request->search_query}%")->get());
            return response()->json([
                'status_code' => 200,
                'packages' => $packages,
                'products' => $products,
                'categories'
            ])->setStatusCode(200);
        } catch (Error $error) {
            DB::rollBack();
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'StoreController, Trying to get data to fill the store'])->setStatusCode(500);
        }
    }
}
