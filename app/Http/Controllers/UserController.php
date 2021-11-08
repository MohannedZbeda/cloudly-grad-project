<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    
    public function index()
    {
        return response()->json(['admins' => UserResource::collection(User::all())]);
    }

    public function GetAuthUser()
    {
        return response()->json(['user' => auth()->user(), 'lang' => App::getLocale()]);
    }

    public function GetUser($id)
    {
        return response()->json(['admin' => User::find($id)]);
    }
    
    
    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->name; 
        $user->email = $request->email; 
        $user->username = $request->username; 
        $user->password = Hash::make($request->password); 
        $user->state = $request->state;
        $user->save();
        return response()->json(['status_code' => 201, 'message' => 'Admin Created']);  
    }

    public function update(Request $request)
    {
        $user = User::find($request->id);
        $user->name = $request->name; 
        $user->email = $request->email; 
        $user->username = $request->username; 
        $user->state = $request->state;
        $user->save();
        return response()->json(['status_code' => 200, 'message' => 'Admin Updated']);  
    }

    
    
   public function changeState(Request $request)
   {
       $user = User::find($request->id);
       $user->state = $request->state;
       $user->save();
       return response()->json(['status_code' => 200, 'message' => 'Admin State Updated', 'admins' => UserResource::collection(User::all())]);  

   }
   
    
}
