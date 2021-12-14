<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Error;
class UserController extends Controller
{
    
    public function index()
    {
        try {
        return response()->json(['admins' => UserResource::collection(User::all())]);
        }
        catch(Error $error) {
            return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'UserController, Trying to get all admins'])->setStatusCode(500);  
          }   
    }

    public function GetAuthUser()
    {
        try {
        return response()->json(['user' => auth()->user(), 'lang' => App::getLocale()]);
    } catch(Error $error) {
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'UserController, Trying to get currently auth user'])->setStatusCode(500);  
      }    
}

    public function GetUser($id)
    {
        try {
        return response()->json(['admin' => User::find($id)]);
    } catch(Error $error) {
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'UserController, Trying to get a user for update'])->setStatusCode(500);  
      }    
}
    
    
    public function store(Request $request)
    {
      try {
        DB::transaction(function() use($request) {
            $user = new User();
            $user->name = $request->name; 
            $user->email = $request->email; 
            $user->username = $request->username; 
            $user->password = Hash::make($request->password); 
            $user->state = $request->state;
            $user->save();
        });
        DB::commit();
        return response()->json(['status_code' => 201, 'message' => 'Admin Created']);  
    } catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'UserController, Trying to create an admin'])->setStatusCode(500);  
      }    
}

    public function update(Request $request)
    {
     try {
         DB::transaction(function () use($request){
             $user = User::find($request->id);
             $user->name = $request->name; 
             $user->email = $request->email; 
             $user->username = $request->username; 
             $user->state = $request->state;
             $user->save();
         });
         DB::commit();
        return response()->json(['status_code' => 200, 'message' => 'Admin Updated']);  
    } catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'UserController, Trying to update an admin'])->setStatusCode(500);  
      }   
}

    
    
   public function changeState(Request $request)
   {
      try {
       DB::transaction(function () use($request){
           $user = User::find($request->id);
           $user->state = $request->state;
           $user->save();
       });
       DB::commit();
       return response()->json(['status_code' => 200, 'message' => 'Admin State Updated', 'admins' => UserResource::collection(User::all())]);  

    } catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'UserController, Trying to change admin account state'])->setStatusCode(500);  
      }    
   
   }    
}
