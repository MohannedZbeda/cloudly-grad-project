<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;






Auth::routes(['register' => false]);

Route::prefix('dashboard')->group(function () {
    Route::prefix('/admins')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/auth-admin', [UserController::class, 'GetAuthUser']);
        Route::get('/admin/{id}', [UserController::class, 'GetUser']);
        Route::post('/', [UserController::class, 'store']);    
        Route::put('/', [UserController::class, 'update']);    
    });   
    Route::post('/logout', function() {
        return Auth::logout();
    }); 
}); 
Route::get('/{any}', [App\Http\Controllers\MainController::class, 'index'])->where('any', '.*');

