<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Error;
use Illuminate\Support\Facades\DB;
class CategoryController extends Controller
{
    public function index()
    {
      try {
        $categories = Category::with('attributes')->get();
        return response()->json(['status_code' => 200, 'categories' => CategoryResource::collection($categories)])->setStatusCode(200);
    }
    catch(Error $error) {
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'CategoryController, Trying to get categories'])->setStatusCode(500);  
        
    }
  }

    public function getCategory($id)
    {
      try {
        $category = Category::with('attributes')->find($id);
        return response()->json(['status_code' => 200, 'category' => $category])->setStatusCode(200);
    }
    catch(Error $error) {
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'CategoryController, Trying to get a category for update'])->setStatusCode(500);  
        
    }
  }

    public function store(Request $request)
    {
      try {
        $validator = Validator::make($request->all(), [
            'ar_name' => 'required|unique:categories,ar_name',
            'en_name' => 'required|unique:categories,en_name'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
        $category = DB::transaction(function() use($request) {
          $category = new Category();
          $category->ar_name = $request->ar_name;
          $category->en_name = $request->en_name;
          $category->save();
          return $category;
        });
        DB::commit();
        return response()->json(['status_code' => 201, 'Category' => $category])->setStatusCode(201);
    }
    catch(Error $error) {
      DB::rollBack();
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'CategoryController, Trying to create a category'])->setStatusCode(500);  
        
    } 
  }

    public function update(Request $request)
    {
      try {
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
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
    
        $category = Category::find($request->id);
        $category->ar_name = $request->ar_name;
        $category->en_name = $request->en_name;
        $category->save();
        return response()->json(['status_code' => 200, 'Category' => $category])->setStatusCode(200);
    }  
    catch(Error $error) {
      DB::rollBack();
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'CategoryController, Trying to get categories'])->setStatusCode(500);  
        
    } 
  }
}
