<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttributeResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Attribute;
use Error;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
{

    public function index($id)
    {
      try {
      $attributes = AttributeResource::collection(Attribute::where('category_id', $id)->get());
      return response()->json(['status_code' => 200, 'attributes' => $attributes])->setStatusCode(200);
    }
     catch(Error $error) {
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'AttributeController, Trying to return all attributes'])->setStatusCode(500);  
        
    }
    }

    public function getAttribute($category_id, $id)
    {
      try {
      $attribute = new AttributeResource(Attribute::where('category_id', $category_id)->where('id', $id)->first());
      return response()->json(['status_code' => 200, 'attribute' => $attribute])->setStatusCode(200);
    }
    catch(Error $error) {
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'AttributeController, Trying to return all attributes for product creation'])->setStatusCode(500);  
        
    }
    }

    public function store(Request $request)
    {
      try {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'ar_name' => 'required|string',
            'en_name' => 'required|string'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
        $attribute = DB::transaction(function() use($request) {
          $attribute = new Attribute();
          $attribute->category_id = $request->category_id;
          $attribute->ar_name = $request->ar_name;
          $attribute->en_name = $request->en_name;
          $attribute->save();
          return $attribute;
        });   
        DB::commit();
        return response()->json(['status_code' => 201, 'attribute' => $attribute])->setStatusCode(201);
    }
    catch(Error $error) {
      DB::rollBack();
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'AttributeController, Trying to create an attribute'])->setStatusCode(500);  
        
    }
  }
    public function update(Request $request)
    {
      try {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'ar_name' => 'required|string',
            'en_name' => 'required|string'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
          $attribute = DB::transaction(function() use($request) {
            $attribute = Attribute::find($request->id);
            $attribute->category_id = $request->category_id;
            $attribute->ar_name = $request->ar_name;
            $attribute->en_name = $request->en_name;
            $attribute->save();
            return $attribute;
          });
    
        DB::commit();
        return response()->json(['status_code' => 200, 'attribute' => $attribute])->setStatusCode(200);
    }
    catch(Error $error) {
      DB::rollBack();
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'AttributeController, Trying to create an attribute'])->setStatusCode(500);  
        
    }
  }
}