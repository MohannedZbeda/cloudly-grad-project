<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\CustomerRequestController;
use App\Http\Controllers\ValueController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\CycleController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\VariantController;
use App\Http\Controllers\NewsletterEmailController;

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

    Route::prefix('/customers')->middleware('role:super_admin')->group(function () {
        Route::get('/', [CustomerController::class, 'index']);
        Route::get('/customer/{id}', [CustomerController::class, 'GetCustomer']);
        Route::post('/', [CustomerController::class, 'store']);
        Route::put('/', [CustomerController::class, 'update']);
        Route::post('/charge-wallet', [CustomerController::class, 'chargeWallet']);
        Route::get('/{id}/transactions', [CustomerController::class, 'getTransactions']);
        Route::post('/change-state', [CustomerController::class, 'changeState']);
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
        Route::get('/get-products', [VariantController::class, 'getProducts']);
        Route::get('/{product_id}', [VariantController::class, 'index']);
        Route::post('/get-product-attributes', [VariantController::class, 'getAttributes']);
        Route::get('/variant/{id}', [VariantController::class, 'getVariant']);
        Route::post('/store', [VariantController::class, 'store']);
        Route::post('/update', [VariantController::class, 'update']);
    });

    Route::prefix('/packages')->middleware('role:super_admin|marketing_admin')->group(function () {
        Route::get('/', [PackageController::class, 'index']);
        Route::get('/get-variants', [VariantController::class, 'getVariants']);
        Route::get('/get-categories', [PackageController::class, 'getCategories']);
        Route::post('/get-category-attributes', [PackageController::class, 'getAttributes']);
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
    Route::prefix('/faqs')->middleware('role:super_admin|marketing_admin')->group(function () {
        Route::get('/', [FAQController::class, 'index']);
        Route::post('/store', [FAQController::class, 'store']);
        Route::post('/update', [FAQController::class, 'update']);
        Route::delete('/delete/{id}', [FAQController::class, 'delete']);
        Route::get('/{id}', [FAQController::class, 'getFAQ']);
    });
    Route::prefix('/customer-requests')->middleware('role:super_admin|marketing_admin|sales_admin')->group(function () {
        Route::get('/', [CustomerRequestController::class, 'index']);
    });

    Route::post('/logout', function () {
        return Auth::logout();
    });
});

// Route::get('/lol', function () {
//     $user = new UserResource(User::with('info')->where('id', 2)->first());
//     $invoice = new InvoiceResource(Invoice::with('items')->where('id', 2)->first());
//     return view('emails.invoice-issued')->with(['invoice' => $invoice, 'user' => $user]);
// });
Route::middleware('auth')->get('/{any}', [App\Http\Controllers\MainController::class, 'index'])->where('any', '.*');
