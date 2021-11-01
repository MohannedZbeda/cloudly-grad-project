<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    
    public function index()
    {
        return ['admins' => UserResource::collection(User::all())];
    }

    public function GetAuthUser()
    {
        return new JsonResponse(['user' => auth()->user(), 'lang' => App::getLocale()]);
    }

    public function GetUser($id)
    {
        return new JsonResponse(['admin' => User::find($id)]);
    }
    public function create(Request $request)
    {
       
    }

    
    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->name; 
        $user->email = $request->email; 
        $user->username = $request->username; 
        $user->password = Hash::make($request->password); 
        $user->avatar = $request->avatar;
        $user->state = $request->state;
        $user->save();
        return new JsonResponse(['message' => 'Admin Created']);  
    }

    public function update(Request $request)
    {
        $user = User::find($request->id);
        $user->name = $request->name; 
        $user->email = $request->email; 
        $user->username = $request->username; 
        $user->avatar = $request->avatar;
        $user->state = $request->state;
        $user->save();
        return new JsonResponse(['message' => 'Admin Updated']);  
    }

    
    public function show($id)
    {
        
    }

    
    public function edit($id)
    {
        
    }

    
    public function destroy($id)
    {
        
    }
}
