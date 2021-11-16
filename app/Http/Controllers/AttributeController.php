<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttributeResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Attribute;
class AttributeController extends Controller
{

    public function index($id)
    {
      $attributes = AttributeResource::collection(Attribute::with('values')->where('category_id', $id)->get());
      return response()->json(['status_code' => 200, 'attributes' => $attributes])->setStatusCode(200);
    }

    public function getAttribute($category_id, $id)
    {
      $attribute = new AttributeResource(Attribute::where('category_id', $category_id)->where('id', $id)->first());
      return response()->json(['status_code' => 200, 'attribute' => $attribute])->setStatusCode(200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'ar_name' => 'required|string',
            'en_name' => 'required|string'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity'])->setStatusCode(422);
    
        $attribute = new Attribute();
        $attribute->category_id = $request->category_id;
        $attribute->ar_name = $request->ar_name;
        $attribute->en_name = $request->en_name;
        $attribute->save();
        return response()->json(['status_code' => 201, 'attribute' => $attribute])->setStatusCode(201);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'ar_name' => 'required|string',
            'en_name' => 'required|string'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity'])->setStatusCode(422);
    
        $attribute = Attribute::find($request->id);
        $attribute->category_id = $request->category_id;
        $attribute->ar_name = $request->ar_name;
        $attribute->en_name = $request->en_name;
        $attribute->save();
        return response()->json(['status_code' => 200, 'attribute' => $attribute])->setStatusCode(200);
    }
}
