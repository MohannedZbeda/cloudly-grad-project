<?php

namespace App\Http\Controllers;

use App\Models\Value;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ValueResource;

class ValueController extends Controller
{

    public function index($id)
    {
      $values = ValueResource::collection(Value::where('attribute_id', $id)->get());
      return response()->json(['status_code' => 200, 'values' => $values])->setStatusCode(200);
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity'])->setStatusCode(422);
    
        $value = new Value();
        $value->attribute_id = $request->attribute_id;
        $value->value = $request->value;
        $value->save();
        return response()->json(['status_code' => 201, 'value' => new ValueResource($value)])->setStatusCode(201);
    } 
    
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity'])->setStatusCode(422);
    
        $value = Value::find($request->id);
        $value->value = $request->value;
        $value->save();
        return response()->json(['status_code' => 200, 'value' => new ValueResource($value)])->setStatusCode(200);
    } 
}
