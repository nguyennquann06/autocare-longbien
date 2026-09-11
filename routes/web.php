<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =========================
// HOME
// =========================

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

// Danh sách xe
Route::get('/vehicles', [VehicleController::class, 'index'])
    ->middleware('auth')
    ->name('vehicles.index');


// Form thêm xe
Route::get('/vehicles/create', [VehicleController::class, 'create'])
    ->middleware('auth')
    ->name('vehicles.create');


// Lưu xe mới
Route::post('/vehicles', [VehicleController::class, 'store'])
    ->middleware('auth')
    ->name('vehicles.store');


// Form chỉnh sửa xe
Route::get('/vehicles/{vehicle}/edit', [VehicleController::class, 'edit'])
    ->middleware('auth')
    ->name('vehicles.edit');


// Cập nhật xe
Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update'])
    ->middleware('auth')
    ->name('vehicles.update');


// Xóa xe
Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])
    ->middleware('auth')
    ->name('vehicles.destroy');


// Xem chi tiết xe
Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show'])
    ->middleware('auth')
    ->name('vehicles.show');


// Lấy dòng xe theo hãng
Route::get('/vehicle-models/{brandId}', [VehicleController::class, 'getModels'])
    ->middleware('auth')
    ->name('vehicles.models');