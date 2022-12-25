<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionCycle;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CycleResource;

class CycleController extends Controller
{
    public function index()
    {
        try {
            $cycles = SubscriptionCycle::all();
            return response()->json(['status_code' => 200, 'cycles' => CycleResource::collection($cycles)])->setStatusCode(200);
        } catch (Error $error) {
            return response()->json(['status_code' => 500, 'location' => 'CycleController, Trying to get all cycles', 'error' => $error->getMessage()])->setStatusCode(500);
        }
    }

    public function getCycles()
    {
        try {

            $cycles = SubscriptionCycle::where('enabled', true)->get();
            return response()->json(['status_code' => 200, 'cycles' => CycleResource::collection($cycles)])->setStatusCode(200);
        } catch (Error $error) {
            return response()->json(['status_code' => 500, 'location' => 'CycleController, Trying to get all enabled cycles for crud', 'error' => $error->getMessage()])->setStatusCode(500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:subscribtion_cycles,name',
                'months' => 'required|numeric|min:1'
            ], [
                'name.required' => ['ar' => 'يرجى تحديد إسم دورة الدفع', 'en' => 'Please enter payment cycle name'],
                'name.unique' => ['ar' => 'هذا الإسم مستعمل', 'en' => 'This name is taken'],
                'months.required' => ['ar' => 'يرجى تحديد عدد الأشهر', 'en' => 'Please enter months'],
                'months.numeric' => ['ar' => 'عدد الأشهر يجب أن يكون رقما', 'en' => 'Months should be numeric'],
                'months.min' => ['ar' => 'عدد الأشهر يجب أن لا يقل عن واحد', 'en' => 'Months should be bigger than 1']
            ]);
            if ($validator->fails())
                return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
            $cycle = DB::transaction(function () use ($request) {
                $cycle = new SubscriptionCycle();
                $cycle->name = $request->name;
                $cycle->months = $request->months;
                $cycle->save();
                return $cycle;
            });
            DB::commit();
            return response()->json(['status_code' => 201, 'cycle' => new CycleResource($cycle)])->setStatusCode(201);
        } catch (Error $error) {
            DB::rollBack();
            return response()->json(['status_code' => 500, 'location' => 'CycleController, Trying to get all cycles', 'error' => $error->getMessage()])->setStatusCode(500);
        }
    }

    public function changeState(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cycle_id' => 'required|exists:subscribtion_cycles,id',
                'status' => 'required|boolean',
            ]);
            if ($validator->fails())
                return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
            DB::transaction(function () use ($request) {
                $cycle = SubscriptionCycle::find($request->cycle_id);
                $cycle->enabled = $request->status;
                $cycle->save();
            });
            DB::commit();
            return response()->json(['status_code' => 200, 'cycles' => CycleResource::collection(SubscriptionCycle::all())])->setStatusCode(200);
        } catch (Error $error) {
            DB::rollBack();
            return response()->json(['status_code' => 500, 'location' => 'CycleController, Trying to get all cycles', 'error' => $error->getMessage()])->setStatusCode(500);
        }
    }


    
}
