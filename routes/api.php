<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\WalletController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\InvoiceController;
use App\Http\Controllers\API\TestimonyController;
use App\Http\Controllers\API\StoreController;
use App\Http\Controllers\API\NewsletterEmailController;
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
    Route::post('/charge-online', [WalletController::class, 'chargeOnline']);
    Route::post('/{id}/charge', [WalletController::class, 'chargeWallet']);
    Route::post('/stripe-charge', [WalletController::class, 'chargeWithStripe']);
    Route::get('/get-balance', [WalletController::class, 'getBalance']);
    Route::get('/get-transactions', [WalletController::class, 'getTransactions']);
});

Route::prefix('/home')->group(function () {
    Route::get('/', [HomeController::class, 'index']);   
});

Route::prefix('/carts')->middleware(['auth:sanctum', 'role:customer'])->group(function () {
    Route::get('/get-items', [CartController::class, 'getCartItems']);
    Route::post('/add-package', [CartController::class, 'addPackage']);
    Route::post('/add-product', [CartController::class, 'addProduct']);
    Route::post('/delete-item', [CartController::class, 'removeFromCart']);
});

Route::prefix('/invoices')->middleware('auth:sanctum', 'role:customer')->group(function () {
    Route::get('/', [InvoiceController::class, 'index']);
    Route::post('/issue-invoice', [InvoiceController::class, 'issueInvoice']);
    Route::post('/checkout', [InvoiceController::class, 'checkout']);
});

Route::prefix('/subs')->middleware('auth:sanctum', 'role:customer')->group(function () {
    Route::get('/', [InvoiceController::class, 'index']);
    Route::post('/issue-invoice', [InvoiceController::class, 'issueInvoice']);
    Route::post('/checkout', [InvoiceController::class, 'checkout']);
});



Route::middleware('auth:sanctum', 'role:customer')->post('/logout', [AuthController::class, 'logout']);