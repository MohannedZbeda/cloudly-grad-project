<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\ValueController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\DiscountController;

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
        Route::post('/get-category-attributes', [ProductController::class, 'getAttributes']);
        Route::post('/remove-discount', [ProductController::class, 'removeDiscount']);
        Route::get('/{id}', [ProductController::class, 'getProduct']);
        Route::post('/store', [ProductController::class, 'store']);
        Route::post('/update', [ProductController::class, 'update']);
        
    });

    Route::prefix('/packages')->group(function () {
        Route::get('/', [PackageController::class, 'index']);
        Route::get('/get-products', [ProductController::class, 'index']);
        Route::get('/get-categories', [PackageController::class, 'getCategories']);
        Route::post('/get-category-attributes', [PackageController::class, 'getAttributes']);
        Route::post('/remove-discount', [PackageController::class, 'removeDiscount']);
        Route::get('/{id}', [PackageController::class, 'getPackage']);
        Route::post('/store', [PackageController::class, 'store']);
        Route::post('/update', [PackageController::class, 'update']);
        
    });

    Route::prefix('/discounts')->group(function () {
        Route::get('/', [DiscountController::class, 'index']);
        Route::get('/get-items', [DiscountController::class, 'getItems']);    
        Route::post('/store', [DiscountController::class, 'store']);
        Route::post('/update', [DiscountController::class, 'update']);
        Route::get('/{id}', [DiscountController::class, 'getDiscount']);
        
    });

    Route::post('/logout', function() {
        return Auth::logout();
    }); 
}); 
Route::get('/{any}', [App\Http\Controllers\MainController::class, 'index'])->where('any', '.*');

