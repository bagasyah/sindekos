<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController; // Pastikan untuk mengimpor controller yang benar
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/checkout', [PaymentController::class, 'checkout']);
Route::post('/continue-payment', [PaymentController::class, 'continuePayment']);
Route::post('/update-payment-status', [PaymentController::class, 'updatePaymentStatus']);
Route::get('/check-payment-status/{paymentId}', [PaymentController::class, 'checkPaymentStatus']);
Route::get('/payment-detail/{id}', [PaymentController::class, 'getPaymentDetail']);

