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
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CycleController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\VariantController;

Auth::routes(['register' => false]);

Route::prefix('dashboard')->middleware('auth')->group(function () {
    Route::get('/get-auth-admin', [UserController::class, 'GetAuthUser']);
    Route::prefix('/admins')->middleware('role:super_admin')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/get-roles', [UserController::class, 'getRoles']);
        Route::get('/admin/{id}', [UserController::class, 'GetUser']);
        Route::post('/', [UserController::class, 'store']);    
        Route::put('/', [UserController::class, 'update']);    
        Route::post('/change-state', [UserController::class, 'changeState']);
    });
    
    Route::prefix('/categories')->middleware('role:super_admin|marketing_admin')->middleware('role:super_admin|marketing_admin')->group(function () {
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

    
    Route::prefix('/products')->middleware('role:super_admin|marketing_admin')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/get-categories', [ProductController::class, 'getCategories']);
        Route::get('/{id}', [ProductController::class, 'getProduct']);
        Route::post('/store', [ProductController::class, 'store']);
        Route::post('/update', [ProductController::class, 'update']);        
    });
    
    Route::prefix('/variants')->middleware('role:super_admin|marketing_admin')->group(function () {
        Route::get('/{product_id}', [VariantController::class, 'index']);
        Route::get('/get-products', [VariantController::class, 'getProducts']);
        Route::post('/get-product-attributes', [VariantController::class, 'getAttributes']);
        Route::post('/add-discount', [VariantController::class, 'addDiscount']);
        Route::post('/remove-discount', [VariantController::class, 'removeDiscount']);
        Route::post('/add-vouchers', [VariantController::class, 'addVouchers']);
        Route::get('/variant/{id}', [VariantController::class, 'getVariant']);
        Route::post('/store', [VariantController::class, 'store']);
        Route::post('/update', [VariantController::class, 'update']);
        
    });

    Route::prefix('/packages')->middleware('role:super_admin|marketing_admin')->group(function () {
        Route::get('/', [PackageController::class, 'index']);
        Route::get('/get-variants', [VariantController::class, 'getVariants']);
        Route::get('/get-categories', [PackageController::class, 'getCategories']);
        Route::post('/get-category-attributes', [PackageController::class, 'getAttributes']);
        Route::post('/add-discount', [PackageController::class, 'addDiscount']);
        Route::post('/remove-discount', [PackageController::class, 'removeDiscount']);
        Route::post('/add-vouchers', [PackageController::class, 'addVouchers']);
        Route::get('/{id}', [PackageController::class, 'getPackage']);
        Route::post('/store', [PackageController::class, 'store']);
        Route::post('/update', [PackageController::class, 'update']);
        
    });

    Route::prefix('/cycles')->middleware('role:super_admin|marketing_admin')->group(function () {
        Route::get('/', [CycleController::class, 'index']);
        Route::get('/get-cycles', [CycleController::class, 'getCycles']);
        Route::post('/store', [CycleController::class, 'store']);
        Route::post('/change-state', [CycleController::class, 'changeState']);
    });
    
    Route::prefix('/discounts')->middleware('role:super_admin|marketing_admin')->group(function () {
        Route::get('/', [DiscountController::class, 'index']);
        Route::get('/get-items', [DiscountController::class, 'getItems']);    
        Route::post('/store', [DiscountController::class, 'store']);
        Route::post('/update', [DiscountController::class, 'update']);
        Route::get('/{id}', [DiscountController::class, 'getDiscount']);
        
    });

    Route::prefix('/coupons')->middleware('role:super_admin|marketing_admin')->group(function () {
        Route::get('/', [CouponController::class, 'index']);
        Route::post('/', [CouponController::class, 'getCoupon']);
        Route::get('/get-items', [DiscountController::class, 'getItems']);    
        Route::post('/store', [CouponController::class, 'store']);
        Route::post('/update', [CouponController::class, 'update']);
        Route::get('/{id}', [DiscountController::class, 'getDiscount']);
        
    });

    Route::prefix('/faqs')->middleware('role:super_admin|marketing_admin')->group(function () {
        Route::get('/', [FAQController::class, 'index']);
        Route::post('/store', [FAQController::class, 'store']);
        Route::post('/update', [FAQController::class, 'update']);
        Route::delete('/delete/{id}', [FAQController::class, 'delete']);
        Route::get('/{id}', [FAQController::class, 'getFAQ']);
        
    });

    Route::post('/logout', function() {
        return Auth::logout();
    }); 
}); 
Route::middleware('auth')->get('/{any}', [App\Http\Controllers\MainController::class, 'index'])->where('any', '.*');

