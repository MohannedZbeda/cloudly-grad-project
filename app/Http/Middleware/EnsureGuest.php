<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class EnsureGuest
{
    
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');
        if(!$token)
          return $next($request);
        $token =  explode(" ", $token)[1]; 
        if(!$token)
          return $next($request);
        $token = PersonalAccessToken::findToken($token);
        if(!$token)
          return $next($request);
        return response()->json(['status_code' => 401, 'message' => 'Already Authenticated']);
        
        
    }
}
