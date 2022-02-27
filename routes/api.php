<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\WalletController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\InvoiceController;
use App\Http\Controllers\API\TestimonyController;
use App\Http\Controllers\API\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix('/auth')->middleware('api.guest')->group(function () {
    Route::get('/token', [AuthController::class, 'genToken']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/send-reset-email', [AuthController::class, 'send_reset_email']);
    Route::post('/reset-password', [AuthController::class, 'reset_password']);    
});

Route::prefix('/wallets')->middleware(['auth:sanctum', 'role:customer'])->group(function () {
    Route::post('/add-wallet', [WalletController::class, 'addWallet']);
    Route::post('/charge', [WalletController::class, 'chargeWallet']);
});

Route::prefix('/carts')->middleware('auth:sanctum', 'role:customer')->group(function () {
    Route::get('/get-items', [CartController::class, 'getCartItems']);
    Route::post('/add-package', [CartController::class, 'addPackage']);
    Route::post('/add-product', [CartController::class, 'addProduct']);
    Route::post('/add-custom-variant', [CartController::class, 'addCustomVariant']);
    Route::post('/delete-item', [CartController::class, 'removeFromCart']);
    Route::post('/update-quantity', [CartController::class, 'updateQuantity']);
});

Route::prefix('/invoices')->middleware('auth:sanctum', 'role:customer')->group(function () {
    Route::post('/issue-invoice', [InvoiceController::class, 'issueInvoice']);
    Route::post('/checkout', [InvoiceController::class, 'checkout']);
});

Route::prefix('/testimonies')->group(function () {
    Route::get('/', [TestimonyController::class, 'index']);
    Route::middleware('auth:sanctum', 'role:customer')->post('/store', [TestimonyController::class, 'store']);
});

Route::prefix('/home')->group(function () {
    Route::get('/home-page', [HomeController::class, 'index']);   
});

Route::prefix('/store')->group(function () {
    Route::get('/', [StoreController::class, 'index']);   
    Route::post('/search', [StoreController::class, 'search']);   
});

Route::middleware('auth:sanctum', 'role:customer')->post('/logout', [AuthController::class, 'logout']);