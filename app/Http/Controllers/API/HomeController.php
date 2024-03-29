<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Category;
use App\Http\Resources\API\CategoryApiResource;
use App\Http\Resources\API\ProductApiResource;
use App\Http\Resources\API\PackageApiResource;
use App\Http\Resources\FaqResource;
use App\Models\FAQ;
use App\Models\Product;
use Error;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        try {
        $packages = PackageApiResource::collection(Package::with(['products'])->get()); 
        $faqs = FaqResource::collection(FAQ::all());
        $categories = CategoryApiResource::collection(Category::with('products')->get());
        $products = ProductApiResource::collection(Product::all());
        return response()->json([
            'status_code' => 200,
            'categories' => $categories,
            'packages' => $packages,
            'products' => $products,
            'faqs' => $faqs
        ])->setStatusCode(200);
    }
    catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'HomeController, Trying to get data to fill homepage'])->setStatusCode(500);  
      }
}
}
