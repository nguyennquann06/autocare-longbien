<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StaffAppointmentController;
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
// SERVICES
// =========================

Route::get(
    '/services',
    [ServiceController::class, 'index']
)->name('services.index');

Route::get(
    '/services/{service}',
    [ServiceController::class, 'show']
)->name('services.show');


// =========================
// AUTH
// =========================

Route::get(
    '/register',
    [AuthController::class, 'showRegisterForm']
)->name('register');

Route::post(
    '/register',
    [AuthController::class, 'register']
)->name('register.submit');


Route::get(
    '/login',
    [AuthController::class, 'showLoginForm']
)->name('login');

Route::post(
    '/login',
    [AuthController::class, 'login']
)->name('login.submit');


// =========================
// CUSTOMER APPOINTMENTS
// =========================

// Danh sách lịch hẹn của khách hàng
Route::get(
    '/appointments',
    [AppointmentController::class, 'index']
)
    ->middleware('auth')
    ->name('appointments.index');


// Form đặt lịch
Route::get(
    '/appointments/create',
    [AppointmentController::class, 'create']
)
    ->middleware('auth')
    ->name('appointments.create');


// Lưu lịch hẹn
Route::post(
    '/appointments',
    [AppointmentController::class, 'store']
)
    ->middleware('auth')
    ->name('appointments.store');


// Khách hàng hủy lịch
Route::patch(
    '/appointments/{appointment}/cancel',
    [AppointmentController::class, 'cancel']
)
    ->middleware('auth')
    ->name('appointments.cancel');


// Chi tiết lịch hẹn khách hàng
Route::get(
    '/appointments/{appointment}',
    [AppointmentController::class, 'show']
)
    ->middleware('auth')
    ->name('appointments.show');


// =========================
// STAFF APPOINTMENTS
// =========================

// Danh sách toàn bộ lịch hẹn
Route::get(
    '/staff/appointments',
    [StaffAppointmentController::class, 'index']
)
    ->middleware('auth')
    ->name('staff.appointments.index');


// Cập nhật trạng thái
Route::patch(
    '/staff/appointments/{appointment}/status',
    [StaffAppointmentController::class, 'updateStatus']
)
    ->middleware('auth')
    ->name('staff.appointments.updateStatus');


// Chi tiết lịch hẹn
Route::get(
    '/staff/appointments/{appointment}',
    [StaffAppointmentController::class, 'show']
)
    ->middleware('auth')
    ->name('staff.appointments.show');


// =========================
// VEHICLES
// =========================

Route::get(
    '/vehicles',
    [VehicleController::class, 'index']
)
    ->middleware('auth')
    ->name('vehicles.index');


Route::get(
    '/vehicles/create',
    [VehicleController::class, 'create']
)
    ->middleware('auth')
    ->name('vehicles.create');


Route::post(
    '/vehicles',
    [VehicleController::class, 'store']
)
    ->middleware('auth')
    ->name('vehicles.store');


Route::get(
    '/vehicles/{vehicle}/edit',
    [VehicleController::class, 'edit']
)
    ->middleware('auth')
    ->name('vehicles.edit');


Route::put(
    '/vehicles/{vehicle}',
    [VehicleController::class, 'update']
)
    ->middleware('auth')
    ->name('vehicles.update');


Route::delete(
    '/vehicles/{vehicle}',
    [VehicleController::class, 'destroy']
)
    ->middleware('auth')
    ->name('vehicles.destroy');


Route::get(
    '/vehicles/{vehicle}',
    [VehicleController::class, 'show']
)
    ->middleware('auth')
    ->name('vehicles.show');


Route::get(
    '/vehicle-models/{brandId}',
    [VehicleController::class, 'getModels']
)
    ->middleware('auth')
    ->name('vehicles.models');