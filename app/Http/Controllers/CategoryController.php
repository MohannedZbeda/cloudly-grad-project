<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('attributes')->get();
        return response()->json(['status_code' => 200, 'categories' => CategoryResource::collection($categories)])->setStatusCode(200);
    }

    public function getCategory($id)
    {
        $category = Category::with('attributes')->find($id);
        return response()->json(['status_code' => 200, 'category' => $category])->setStatusCode(200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ar_name' => 'required|unique:categories,ar_name',
            'en_name' => 'required|unique:categories,en_name'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity'])->setStatusCode(422);
    
        $category = new Category();
        $category->ar_name = $request->ar_name;
        $category->en_name = $request->en_name;
        $category->save();
        return response()->json(['status_code' => 201, 'Category' => $category])->setStatusCode(201);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ar_name' => [
              'required',
              Rule::unique('categories', 'ar_name')->ignore($request->id)
            ],
            'en_name' => [
                'required',
                Rule::unique('categories', 'en_name')->ignore($request->id)
              ]
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity'])->setStatusCode(422);
    
        $category = Category::find($request->id);
        $category->ar_name = $request->ar_name;
        $category->en_name = $request->en_name;
        $category->save();
        return response()->json(['status_code' => 200, 'Category' => $category])->setStatusCode(200);
    }
}
