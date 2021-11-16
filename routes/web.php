<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\ValueController;
Auth::routes(['register' => false]);
Route::prefix('dashboard')->group(function () {
    Route::prefix('/admins')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/auth-admin', [UserController::class, 'GetAuthUser']);
        Route::get('/admin/{id}', [UserController::class, 'GetUser']);
        Route::post('/', [UserController::class, 'store']);    
        Route::put('/', [UserController::class, 'update']);    
        Route::post('/change-state', [UserController::class, 'changeState']);
    });
    
    Route::prefix('/categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{id}', [CategoryController::class, 'getCategory']);
        Route::post('/store', [CategoryController::class, 'store']);
        Route::post('/update', [CategoryController::class, 'update']);
        Route::get('/{id}/attributes', [AttributeController::class, 'index']);
        Route::get('/{category_id}/attributes/{id}', [AttributeController::class, 'getAttribute']);
        Route::post('/attributes/store', [AttributeController::class, 'store']);
        Route::post('/attributes/update', [AttributeController::class, 'update']);
        Route::get('/attributes/{id}/values', [ValueController::class, 'index']);
        Route::post('/attributes/{id}/values/store', [ValueController::class, 'store']);
        Route::post('/attributes/{id}/values/update', [ValueController::class, 'update']);
        
    });

    
    Route::prefix('/products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/get-categories', [ProductController::class, 'getCategories']);
        Route::get('/{id}', [ProductController::class, 'getProduct']);
        Route::post('/store', [ProductController::class, 'store']);
        Route::post('/update', [ProductController::class, 'update']);
        
    });

    Route::post('/logout', function() {
        return Auth::logout();
    }); 
}); 
Route::get('/{any}', [App\Http\Controllers\MainController::class, 'index'])->where('any', '.*');

