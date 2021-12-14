<?php

namespace App\Http\Controllers;

//use Illuminate\Http\JsonResponse;
//use Illuminate\Support\Facades\Auth;
use Error;
//use Illuminate\Http\Request;

class MainController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index()
    {
        try {
        return view('main', ['user' => auth()->user()]);
     }
     catch(Error $error) {
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'MainController, Trying to get user data'])->setStatusCode(500);  
      
      } 
    }

   
    
}
