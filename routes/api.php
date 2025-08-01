<?php

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ZaloController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

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

Route::middleware('checkToken')->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json(auth()->user());
    });
    Route::post('/user/create', [CustomerController::class, 'store']);

    Route::post('/service/create', [ServiceController::class, 'store']);
});

Route::post('/invoice/quota', [HotelController::class, 'getDetailBill']);

Route::post('/bill/send', [HotelController::class, 'sendToInvoiceSystem']);

Route::middleware('auth.api_token')->post('/invoices', [HotelController::class, 'getData']);

Route::middleware('auth.api_token')->post('/check-order-status', [HotelController::class, 'checkStatus']);

Route::get('/order-status/{orderId}', [HotelController::class, 'orderStatus']);


Route::post('/detail-order', [HotelController::class, 'getDetail']);

Route::middleware('auth.api_token')->post('/invoices', [HotelController::class, 'apiOrderDetal']);

// Route::get('/test', [HotelController::class, 'apiTest']);

/// zalo Zns
Route::post('/add-zalo-message', [ZaloController::class, 'addZaloMessage']);

Route::post('add-zalo-oa', [ZaloController::class, 'addZaloOa']);


Route::post('add-transaction-idsgo', [ZaloController::class, 'addTransaction']);

Route::post('add-template-idsgo', [ZaloController::class, 'addTemplate']);

Route::post('/register-crm', [RegisterController::class, 'register']);
