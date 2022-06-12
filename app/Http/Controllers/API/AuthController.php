<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\Wallet;
use App\Models\WalletType;
use Carbon\Carbon;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\API\UserResource;

class AuthController extends Controller
{
    public function genToken(Request $request)
    {
        try {
            if($request->cookie('token'))
            { 
                $token = PersonalAccessToken::findToken($request->cookie('token'));
                if(!$token)
                  return response()->json(['message' => 'Unauthorized'])->setStatusCode(401);
                $user = $token->tokenable;
                DB::table('personal_access_tokens')
                ->where('id', $token->id)
                ->delete();
                cookie()->forget('token');
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json(['token' => $token, 'user' => $user])->withCookie('token', $token, 10080)->setStatusCode(200);
            
            }
            return response()->json(['message' => 'Unauthorized'])->setStatusCode(401);   
        }
        catch(Error $error) {
          return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'AuthController, Trying to generate a token for the user'])->setStatusCode(500);  

        }

        
    }
    

    public function send_reset_email(Request $request)
    {
        try {
        $validator = Validator::make($request->all(), ['email' => 'required|email']);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity'])->setStatusCode(422);
        
        $user = User::where('email', $request->email)->first();
        if(!$user)
          return response()->json([
            'status_code' => 404,
            'ar_message' => 'لم يتم العثور على بريد إلكتروني بهذا العنوان',
            'en_message' => 'Your Email Address was not found in our database'])
            ->setStatusCode(404);
        $user_info = DB::transaction(function() use($user) {
            $user_info = UserInfo::where('user_id', $user->id)->first();
            $user_info->password_reset_code = substr(str_shuffle("0123456789"), 0, 5);
            $user_info->save();
            return $user_info;
        });
         
        DB::commit();
        Mail::to($user->email)->send(new \App\Mail\ResetPasswordMail($user_info->password_reset_code, $user->name));
        return response()->json(['status_code' => 200, 'message' => 'Email Sent'])->setStatusCode(200);
       }
      catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'AuthController, Trying to send password reset email'])->setStatusCode(500);  
          
      }
    }

    public function reset_password(Request $request)
    {
        try {
        if(!$request->email)
          return response()->json(['status_code' => 422, 'ar_message' => 'يرجى تحديد البريد الإكتروني', 'en_message' => 'Please specify an email'])->setStatusCode(422);  
        $user = User::where('email', $request->email)->first();
        $validator = Validator::make($request->all(), [
        'code' => [
            'required',
            Rule::in([$user->info->password_reset_code]), 
        ],
        'password' => 'required|confirmed',
        'email' => 'required|exists:users,email'

        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity'])->setStatusCode(422);
        DB::transaction(function() use($request, $user) {
            $user->password = Hash::make($request->password);
            $user->save();
            $user_info = UserInfo::where('user_id', $user->id)->first();
            $user_info->password_reset_code = null;
            $user_info->save();
        });
        DB::commit();
        return response()->json(['status_code' => 200, 'message' => 'Password Has Been Reset'])->setStatusCode(200);
    }
     catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'AuthController, Trying to reset user password'])->setStatusCode(500);   
     }
        }
    
    public function register(Request $request) {
        try {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|unique:users,email',
            'username' => [
            'required',
            'string',
            Rule::unique('users')
            ],
            'password' => 'required|confirmed',
            'phone' => [
                'required',
                'string',
              Rule::unique('user_info')->ignore($request->id, 'user_id')
            ]
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
        $user = DB::transaction(function() use($request) {
            $user = new User();
            $user->name =  $request->name;
            $user->email =  $request->email;
            $user->username =  $request->username;
            $user->password =  Hash::make($request->password);
            $user->api_token =  Str::random(60);
            $user->state = true;
            $user->save();
    
            $user_info = new UserInfo();
            $user_info->user_id = $user->id;
            $user_info->phone = $request->phone;
            $user_info->save();
            
            Wallet::insert([
              'type_id' => WalletType::where('type_name', 'balance_wallet')->first()->id,
              'user_id' => $user->id,
              'created_at' => Carbon::now(),
              'updated_at' => Carbon::now()
            ]);
            Wallet::insert([
              'type_id' => WalletType::where('type_name', 'reservation_wallet')->first()->id,
              'user_id' => $user->id,
              'created_at' => Carbon::now(),
              'updated_at' => Carbon::now()
            ]);
            
            $user_cart = new Cart();
            $user_cart->user_id =  $user->id;
            $user_cart->save();
            $user->attachRole('customer');
            return $user;
        });
        
        $token = $user->createToken('auth_token')->plainTextToken;
        DB::commit();
        return response()
               ->json([
                   'status_code' => 201,
                   'user' => User::with('info')->find($user->id),
                   'token' => $token
                ])
                ->withCookie('token', $token, 10080)
                ->setStatusCode(201);
    }
    catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'AuthController, Trying to register a user'])->setStatusCode(500);   
     }
    }

    public function login(Request $request)
    {
        try {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);
        if($validator->fails()) {
            return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity'])->setStatusCode(422);
        }
        $user = User::where('username', $request->username)->first();
        if(!$user)
            return response()->json(['status_code' => 422, 'ar_message' => 'لا يوجد مستخدم بهذا الإسم', 'en_message ' => 'No user with that username was found'])->setStatusCode(422);
        else if(!Hash::check($request->password, $user->password))
        return response()->json(['status_code' => 422, 'ar_message' => 'رقم سري خاطئ', 'en_message ' => 'Wrong password'])->setStatusCode(422);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['status_code' => 200, 'token' => $token, 'user' => new UserResource($user)])->withCookie('token', $token, 10080)->setStatusCode(200);
    }
    catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'AuthController, Trying to login a user'])->setStatusCode(500);   
     }
    }

    public function logout()
    {
        try {
        return response()
        ->json(['status_code' => 200, 'message' => 'Logged Out']) 
        ->withCookie('token', 'no-token', -10080)->setStatusCode(200);
      }
      catch(Error $error) {
        DB::rollBack();
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'AuthController, Trying to logout a user'])->setStatusCode(500);   
     }
    }
}
