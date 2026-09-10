<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Trang chủ
Route::get('/', function () {
    return view('home');
})->name('home');


// =========================
// AUTH
// =========================

// Đăng ký
Route::get('/register', [AuthController::class, 'showRegisterForm'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.submit');

// Đăng nhập
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');


// =========================
// VEHICLES
// =========================

// Trang thêm phương tiện
Route::get('/vehicles/create', [VehicleController::class, 'create'])
    ->middleware('auth')
    ->name('vehicles.create');

// Lưu phương tiện
Route::post('/vehicles', [VehicleController::class, 'store'])
    ->middleware('auth')
    ->name('vehicles.store');

// Lấy dòng xe theo hãng
Route::get('/vehicle-models/{brandId}', [VehicleController::class, 'getModels'])
    ->middleware('auth')
    ->name('vehicles.models');