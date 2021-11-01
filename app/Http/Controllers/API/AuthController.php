<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|unique:users,email,except,'.$request->id ?? '',
            'username' => 'required|string|unique:users,username,except,'.$request->id ?? '',
            'password' => 'required|string'
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity']);
        
        $user = new User();
        $user->name =  $request->name;
        $user->email =  $request->email;
        $user->username =  $request->username;
        $user->password =  Hash::make($request->password);
        $user->api_token =  Str::random(60);
        $user->state = true;
        $user->save();
        
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['status_code' => 201, 'user' => $user, 'token' => $token]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);
        if($validator->fails()) {
            return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity']);
        }
        $user = User::where('username', $request->username)->first();
        if(!$user)
            return response()->json(['status_code' => 422, 'message.ar' => 'لا يوجد مستخدم بهذا الإسم', 'message.en ' => 'No user with that username was found']);
        else if(!Hash::check($request->password, $user->password))
        return response()->json(['status_code' => 422, 'message.ar' => 'رقم سري خاطئ', 'message.en ' => 'Wrong password']);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['status_code' => 200, 'token' => $token]);

    }
}
