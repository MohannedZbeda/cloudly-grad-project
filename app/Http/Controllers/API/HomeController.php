<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Category;
use App\Http\Resources\API\CategoryApiResource;
use App\Http\Resources\API\ProductApiResource;
use App\Http\Resources\API\PackageApiResource;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $packages = PackageApiResource::collection(Package::with(['products', 'discounts'])->get()); 
        $categories = CategoryApiResource::collection(Category::with('products')->get());
        $products = ProductApiResource::collection(Product::with('discounts')->get());
        return response()->json([
            'status_code' => 200,
            'categories' => $categories,
            'packages' => $packages,
            'products' => $products
        ])->setStatusCode(200);
    }
}
