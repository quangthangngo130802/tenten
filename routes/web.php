<?php

use App\Http\Controllers\Auth\AuthController;
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
});

Route::middleware('auth')->group(function () {
    route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', function () {
        return view('backend.dashboard');
    })->name('dashboard');
});

