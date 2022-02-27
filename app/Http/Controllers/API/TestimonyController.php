<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Testimony;
use App\Http\Resources\API\TestimonyResource;
use Error;
class TestimonyController extends Controller
{
    public function index(Request $request)
    {
        try {
            $testimonies = TestimonyResource::collection(Testimony::with('customer')->where('shown', true)->get());
            return response()->json(['status_code' => 200, 'testimonies' => $testimonies])->setStatusCode(200);
        } catch (Error $error) {
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'TestimonyAPIController, Trying to get all testimonies'])->setStatusCode(500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'statement' => 'required|string',
            ]);
            if ($validator->fails())
                return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
            DB::transaction(function () use ($request) {
                $testimony = new Testimony();
                $testimony->customer_id =  auth('sanctum')->user()->id;
                $testimony->statement =  $request->statement;
                $testimony->save();
                return;
            });

            DB::commit();
            return response()->json(['status_code' => 200, 'message' => 'Well Recieved, Thank you.'])->setStatusCode(200);
        } catch (Error $error) {
            DB::rollBack();
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'TestimonyAPIController, Trying to create a testimony'])->setStatusCode(500);
        }
    }
}
