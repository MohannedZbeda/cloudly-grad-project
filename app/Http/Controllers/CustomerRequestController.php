<?php

namespace App\Http\Controllers;

use App\Models\CustomerRequest;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Resources\CustomerRequestResource;
class CustomerRequestController extends Controller
{
    public function index()
    {
        return response()->json(['requests' => CustomerRequestResource::collection(CustomerRequest::all())])->setStatusCode(200);
    }

    public function changeStatus($id)
    {
        $request = CustomerRequest::find($id);
        $request->status = true;
        $request->save();
        $sub = Subscription::find($request->sub_id);
        $sub->record = $sub->record . "تم تنفيذ الطلب" ."'". $request->descreption . "' <br>";
        $sub->save();
        return response()->json(['requests' => CustomerRequestResource::collection(CustomerRequest::all())])->setStatusCode(200);
    }
}
