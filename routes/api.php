<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HomeController;
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


Route::middleware('auth:sanctum')->post('/user', function (Request $request) {
    return 'Protected Route';
});
Route::prefix('/auth')->middleware('api.guest')->group(function () {
    Route::get('/token', [AuthController::class, 'genToken']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/send-reset-email', [AuthController::class, 'send_reset_email']);
    Route::post('/reset-password', [AuthController::class, 'reset_password']);    
});

Route::prefix('/home')->group(function () {
    Route::get('/home-page', [HomeController::class, 'index']);   
});

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);