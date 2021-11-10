<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\CategoryApiResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = CategoryApiResource::collection(Category::with('products')->get());
        return response()->json(['status_code' => 200, 'categories' => $categories])->setStatusCode(200);
        
    }
}
