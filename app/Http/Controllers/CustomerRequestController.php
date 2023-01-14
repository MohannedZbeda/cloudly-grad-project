<?php

namespace App\Http\Controllers;

use App\Models\CustomerRequest;
use Illuminate\Http\Request;

class CustomerRequestController extends Controller
{
    public function index()
    {
        return response()->json(['requests' => CustomerRequest::all()])->setStatusCode(200);
    }
}
