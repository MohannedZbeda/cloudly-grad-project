<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Error;
class UserController extends Controller
{
    
    public function index()
    {
        try {
        return response()->json(['admins' => UserResource::collection(User::whereHas("roles", function($q){ $q->where("name", "<>","customer")->where('name', '<>', 'super_admin'); })->get())]);
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
        return response()->json(['admin' => new UserResource(User::find($id))]);
    } catch(Error $error) {
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'UserController, Trying to get a user for update'])->setStatusCode(500);  
      }    
}
    
    public function getRoles()
    {
        try {
            $roles = Role::all()->except([Role::where('name', 'super_admin')->first()->id, Role::where('name', 'customer')->first()->id]);
            return response()->json(['status_code' => 200, 'roles' => $roles])->setStatusCode(200);
        } catch (Error $error) {
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'AuthController, Trying to get roles'])->setStatusCode(500);          
        }
    }    

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,id'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
      try {
        DB::transaction(function() use($request) {
            $user = new User();
            $user->name = $request->name; 
            $user->email = $request->email; 
            $user->username = $request->username; 
            $user->password = Hash::make($request->password); 
            $user->state = $request->state;
            $user->save();
            $user->attachRole($request->role);
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => [
              'required',
               Rule::unique('users', 'email')->ignore($request->id)  
            ],
            'username' => [
                'required',
                'string',
                 Rule::unique('users', 'username')->ignore($request->id)  
              ],
            'role_id' => 'required|exists:roles,id'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
         DB::transaction(function () use($request){
             $user = User::find($request->id);
             $user->name = $request->name; 
             $user->email = $request->email; 
             $user->username = $request->username; 
             $request->password ?  $user->password = Hash::make($request->password) : ''; 
             $user->state = $request->state;
             $user->save();
             $user->detachRoles($user->roles);
             $user->attachRole($request->role_id);
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
