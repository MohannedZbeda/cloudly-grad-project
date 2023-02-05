<?php

namespace App\Http\Controllers;

use App\Models\CustomerRequest;
use App\Models\Subscription;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserSubsController extends Controller
{
    public function index($id) {
        try {
           $subs = Subscription::where('user_id', $id)->get();
           return response()->json(['subs' => $subs]);
        }
        catch(Error $error) {
            return response()->json($error);
        }
    }

    public function subRequests($id, $sub_id) {
        try {
           $requests = CustomerRequest::where('customer_id', $id)->where('sub_id', $sub_id)->get();
           return response()->json(['requests' => $requests]);
        }
        catch(Error $error) {
            return response()->json($error);
        }
    }

    public function createRequest($id, $sub_id, Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'descreption' => 'required'
              ]);
              if ($validator->fails())
                return response()->json(['status_code' => 422, 'message' => 'Please Enter Description'])->setStatusCode(422);
           DB::transaction(function () use($id, $sub_id, $request){
              $customer_request = new CustomerRequest();
              $customer_request->customer_id = $id;
              $customer_request->sub_id = $sub_id;
              $customer_request->status = false;
              $customer_request->descreption = (string)$request->descreption;
              $customer_request->save(); 
           });
           DB::commit();
           return response()->json(['message' => 'Request Added, Thank you for contacting us']);
        }
        catch(Error $error) {
            DB::rollBack();
            return response()->json($error);
        }
    }
}
