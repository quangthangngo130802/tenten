<?php

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CloudController;
use App\Http\Controllers\Admin\HostingController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Customer\CloudController as CustomerCloudController;
use App\Http\Controllers\Customer\HostingController as CustomerHostingController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
route::middleware('guest')->group(function () {
    route::get('', [AuthController::class, 'login'])->name('login');
    route::post('', [AuthController::class, 'authenticate']);
    route::get('dang-ky-tai-khoan', [AuthController::class, 'register'])->name('register');
    route::post('dang-ky-tai-khoan', [AuthController::class, 'submitregister'])->name('submit.register');
    route::get('reset-password', [AuthController::class, 'resetpass'])->name('resetpass');
    route::post('reset-password', [AuthController::class, 'sendResetPassword'])->name('submit.resetpass');
    Route::get('/activate-account/{token}', [AuthController::class, 'activateAccount'])->name('activate.account');

});

Route::middleware('auth')->group(function () {
    route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('dashboard', function () {
        return view('backend.dashboard');
    })->name('dashboard');

    Route::prefix('payment')->name('payment.')->group(function () {
        route::get('', [PaymentController::class, 'recharge'])->name('recharge');
        route::post('', [PaymentController::class, 'createPayment'])->name('recharge.add');
        route::get('cancel', [PaymentController::class, 'cancelUrl'])->name('recharge.cancel');
        route::get('return', [PaymentController::class, 'returnUrl'])->name('recharge.return');

    });

    Route::prefix('admin')->middleware('check.admin')->group(function () {


        Route::prefix('user')->name('user.')->group(function () {
            route::get('', [UserController::class, 'index'])->name('index');
            route::get('create', [UserController::class, 'create'])->name('create');
            route::post('', [UserController::class,'store'])->name('store');
            route::get('{id}/edit', [UserController::class, 'edit'])->name('edit');
            route::put('{id}/edit', [UserController::class, 'update'])->name('update');
            route::post('{id}', [UserController::class, 'delete'])->name('delete');
        });

        Route::prefix('client')->name('client.')->group(function () {
            route::get('', [ClientController::class, 'index'])->name('index');
            route::get('create', [ClientController::class, 'create'])->name('create');
            route::post('', [ClientController::class,'store'])->name('store');
            route::get('{id}/edit', [ClientController::class, 'edit'])->name('edit');
            route::put('{id}/edit', [ClientController::class, 'update'])->name('update');
            route::post('{id}', [ClientController::class, 'delete'])->name('delete');
        });

        Route::prefix('order')->name('order.')->group(function () {
            route::get('{status?}', [OrderController::class, 'index'])->name('index');
            route::get('{id}/show', [OrderController::class, 'show'])->name('show');
            route::post('{id}', [OrderController::class, 'delete'])->name('delete');
        });

        Route::prefix('hosting')->name('hosting.')->group(function () {
            route::get('', [HostingController::class, 'index'])->name('index');
            route::get('create', [HostingController::class, 'create'])->name('create');
            route::post('', [HostingController::class,'store'])->name('store');
            route::get('{id}/edit', [HostingController::class, 'edit'])->name('edit');
            route::put('{id}/edit', [HostingController::class, 'update'])->name('update');
            route::post('{id}', [HostingController::class, 'delete'])->name('delete');
        });
        Route::prefix('cloud')->name('cloud.')->group(function () {
            route::get('{type_id?}', [CloudController::class, 'index'])->name('index');
            route::get('create', [CloudController::class, 'create'])->name('create');
            route::post('', [CloudController::class,'store'])->name('store');
            route::get('{id}/edit', [CloudController::class, 'edit'])->name('edit');
            route::put('{id}/edit', [CloudController::class, 'update'])->name('update');
            route::post('{id}', [CloudController::class, 'delete'])->name('delete');
        });
    });

    Route::prefix('customer')->name('customer.')->group(function () {

        Route::prefix('hosting')->name('hosting.')->group(function () {
            route::get('', [CustomerHostingController::class, 'index'])->name('index');
        });

        Route::prefix('cloud')->name('cloud.')->group(function () {
            route::get('{type_id?}', [CustomerCloudController::class, 'index'])->name('index');
        });
    });

});



Route::get('/get-districts', [AuthController::class, 'getDistricts']);
Route::get('/get-wards', [AuthController::class, 'getWards']);
