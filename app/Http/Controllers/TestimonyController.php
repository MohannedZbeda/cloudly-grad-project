<?php

namespace App\Http\Controllers;

use Error;
use Illuminate\Http\Request;
use App\Http\Resources\TestimonyResource;
use App\Models\Testimony;
use Illuminate\Support\Facades\DB;

class TestimonyController extends Controller
{
    public function index()
    {
        try {
            $testimonies = TestimonyResource::collection(Testimony::with('customer')->get());
            return response()->json(['status_code' => 200, 'testimonies' => $testimonies])->setStatusCode(200);
        } catch (Error $error) {
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'TestimonyController, Trying to get all testimonies'])->setStatusCode(500);
        }
    }

    public function changeState(Request $request)
    {
        try {
            DB::transaction(function () use($request) {
                $testimony = Testimony::find($request->id);
                $testimony->shown = $request->shown;
                $testimony->save();
                return $testimony;
            });
            DB::commit();
            return response()->json(['status_code' => 200, 'testimonies' => TestimonyResource::collection(Testimony::all())])->setStatusCode(200);
        } catch (Error $error) {
            DB::rollBack();
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'TestimonyController, Trying to change testimony state'])->setStatusCode(500);
        }
    }
}
