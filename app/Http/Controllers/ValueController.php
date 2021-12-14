<?php

namespace App\Http\Controllers;

use App\Models\Value;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ValueResource;
use Illuminate\Support\Facades\DB;
use Error;
class ValueController extends Controller
{

    public function index($id)
    {
      try {
      $values = ValueResource::collection(Value::where('attribute_id', $id)->get());
      return response()->json(['status_code' => 200, 'values' => $values])->setStatusCode(200);
    } catch(Error $error) {
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'ValueController, Trying to get all Values of an attribute'])->setStatusCode(500);  
    }    
  }
    
    public function store(Request $request)
    {
      try { 
        $validator = Validator::make($request->all(), [
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity'])->setStatusCode(422);
        $value = DB::transaction(function () use($request){
            
          $value = new Value();
          $value->attribute_id = $request->attribute_id;
          $value->value = $request->value;
          $value->save();
          return $value;
        });
        DB::commit();
        return response()->json(['status_code' => 201, 'value' => new ValueResource($value)])->setStatusCode(201);
    } catch(Error $error) {
      DB::rollBack();
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'ValueController, Trying to create a value'])->setStatusCode(500);  
    }    
  }
    
    public function update(Request $request)
    {
      try {
        $validator = Validator::make($request->all(), [
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity'])->setStatusCode(422);
        $value = DB::transaction(function () use($request){
            
          $value = Value::find($request->id);
          $value->value = $request->value;
          $value->save();
          return $value;
        });
        DB::commit();
        return response()->json(['status_code' => 200, 'value' => new ValueResource($value)])->setStatusCode(200);
    } catch(Error $error) {
      DB::rollBack();
      return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'ValueController, Trying to update a Value'])->setStatusCode(500);  
    }     
  }
}
