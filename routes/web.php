<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\CustomerInvoiceController;
use App\Http\Controllers\MaintenanceHistoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StaffAppointmentController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\StaffInvoiceController;
use App\Http\Controllers\StaffPartController;
use App\Http\Controllers\StaffServiceOrderController;
use App\Http\Controllers\TechnicianServiceOrderController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
})->name('home');


/*
|--------------------------------------------------------------------------
| SERVICES
|--------------------------------------------------------------------------
*/

Route::get(
    '/services',
    [ServiceController::class, 'index']
)->name('services.index');

Route::get(
    '/services/{service}',
    [ServiceController::class, 'show']
)->name('services.show');


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

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


/**
 * Logout bắt buộc dùng POST.
 *
 * Không dùng GET /logout vì đăng xuất
 * là hành động làm thay đổi trạng thái session.
 */
Route::post(
    '/logout',
    [AuthController::class, 'logout']
)
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| AI CHATBOT
|--------------------------------------------------------------------------
*/

Route::get(
    '/chat',
    [ChatController::class, 'index']
)
    ->middleware('auth')
    ->name('chat.index');

Route::post(
    '/chat/messages',
    [
        ChatController::class,
        'storeMessage',
    ]
)
    ->middleware('auth')
    ->name('chat.messages.store');


/*
|--------------------------------------------------------------------------
| CUSTOMER DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get(
    '/customer/dashboard',
    [
        CustomerDashboardController::class,
        'index',
    ]
)
    ->middleware('auth')
    ->name('customer.dashboard');


/*
|--------------------------------------------------------------------------
| CUSTOMER APPOINTMENTS
|--------------------------------------------------------------------------
*/

Route::get(
    '/appointments',
    [AppointmentController::class, 'index']
)
    ->middleware('auth')
    ->name('appointments.index');

Route::get(
    '/appointments/create',
    [AppointmentController::class, 'create']
)
    ->middleware('auth')
    ->name('appointments.create');

Route::post(
    '/appointments',
    [AppointmentController::class, 'store']
)
    ->middleware('auth')
    ->name('appointments.store');

Route::patch(
    '/appointments/{appointment}/cancel',
    [AppointmentController::class, 'cancel']
)
    ->middleware('auth')
    ->name('appointments.cancel');

Route::get(
    '/appointments/{appointment}',
    [AppointmentController::class, 'show']
)
    ->middleware('auth')
    ->name('appointments.show');


/*
|--------------------------------------------------------------------------
| CUSTOMER MAINTENANCE HISTORY
|--------------------------------------------------------------------------
*/

Route::get(
    '/maintenance-history',
    [MaintenanceHistoryController::class, 'index']
)
    ->middleware('auth')
    ->name('maintenance-history.index');

Route::get(
    '/maintenance-history/{serviceOrder}',
    [MaintenanceHistoryController::class, 'show']
)
    ->middleware('auth')
    ->name('maintenance-history.show');


/*
|--------------------------------------------------------------------------
| CUSTOMER INVOICES
|--------------------------------------------------------------------------
*/

Route::get(
    '/my-invoices',
    [CustomerInvoiceController::class, 'index']
)
    ->middleware('auth')
    ->name('customer.invoices.index');

Route::get(
    '/my-invoices/{invoice}',
    [CustomerInvoiceController::class, 'show']
)
    ->middleware('auth')
    ->name('customer.invoices.show');


/*
|--------------------------------------------------------------------------
| STAFF DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get(
    '/staff/dashboard',
    [
        StaffDashboardController::class,
        'index',
    ]
)
    ->middleware('auth')
    ->name('staff.dashboard');


/*
|--------------------------------------------------------------------------
| STAFF APPOINTMENTS
|--------------------------------------------------------------------------
*/

Route::get(
    '/staff/appointments',
    [StaffAppointmentController::class, 'index']
)
    ->middleware('auth')
    ->name('staff.appointments.index');

Route::patch(
    '/staff/appointments/{appointment}/status',
    [
        StaffAppointmentController::class,
        'updateStatus',
    ]
)
    ->middleware('auth')
    ->name('staff.appointments.updateStatus');

Route::get(
    '/staff/appointments/{appointment}',
    [StaffAppointmentController::class, 'show']
)
    ->middleware('auth')
    ->name('staff.appointments.show');


/*
|--------------------------------------------------------------------------
| STAFF SERVICE ORDERS
|--------------------------------------------------------------------------
*/

Route::get(
    '/staff/appointments/{appointment}/service-order/create',
    [
        StaffServiceOrderController::class,
        'create',
    ]
)
    ->middleware('auth')
    ->name('staff.service-orders.create');

Route::post(
    '/staff/appointments/{appointment}/service-order',
    [
        StaffServiceOrderController::class,
        'store',
    ]
)
    ->middleware('auth')
    ->name('staff.service-orders.store');

Route::post(
    '/staff/service-orders/{serviceOrder}/parts',
    [
        StaffServiceOrderController::class,
        'addPart',
    ]
)
    ->middleware('auth')
    ->name('staff.service-orders.parts.store');


/*
|--------------------------------------------------------------------------
| STAFF INVOICES
|--------------------------------------------------------------------------
*/

Route::get(
    '/staff/service-orders/{serviceOrder}/invoice/create',
    [
        StaffInvoiceController::class,
        'create',
    ]
)
    ->middleware('auth')
    ->name('staff.invoices.create');

Route::post(
    '/staff/service-orders/{serviceOrder}/invoice',
    [
        StaffInvoiceController::class,
        'store',
    ]
)
    ->middleware('auth')
    ->name('staff.invoices.store');

Route::patch(
    '/staff/invoices/{invoice}/pay',
    [
        StaffInvoiceController::class,
        'pay',
    ]
)
    ->middleware('auth')
    ->name('staff.invoices.pay');

Route::get(
    '/staff/invoices/{invoice}',
    [
        StaffInvoiceController::class,
        'show',
    ]
)
    ->middleware('auth')
    ->name('staff.invoices.show');


/*
|--------------------------------------------------------------------------
| STAFF SERVICE ORDER DETAIL
|--------------------------------------------------------------------------
*/

Route::get(
    '/staff/service-orders/{serviceOrder}',
    [
        StaffServiceOrderController::class,
        'show',
    ]
)
    ->middleware('auth')
    ->name('staff.service-orders.show');


/*
|--------------------------------------------------------------------------
| STAFF INVENTORY / PARTS
|--------------------------------------------------------------------------
*/

Route::get(
    '/staff/parts',
    [
        StaffPartController::class,
        'index',
    ]
)
    ->middleware('auth')
    ->name('staff.parts.index');

Route::get(
    '/staff/parts/{part}/stock-in',
    [
        StaffPartController::class,
        'showStockInForm',
    ]
)
    ->middleware('auth')
    ->name('staff.parts.stock-in.form');

Route::post(
    '/staff/parts/{part}/stock-in',
    [
        StaffPartController::class,
        'stockIn',
    ]
)
    ->middleware('auth')
    ->name('staff.parts.stock-in');


/*
|--------------------------------------------------------------------------
| TECHNICIAN SERVICE ORDERS
|--------------------------------------------------------------------------
*/

Route::get(
    '/technician/service-orders',
    [
        TechnicianServiceOrderController::class,
        'index',
    ]
)
    ->middleware('auth')
    ->name('technician.service-orders.index');

Route::get(
    '/technician/service-orders/{serviceOrder}',
    [
        TechnicianServiceOrderController::class,
        'show',
    ]
)
    ->middleware('auth')
    ->name('technician.service-orders.show');

Route::patch(
    '/technician/service-orders/{serviceOrder}/start',
    [
        TechnicianServiceOrderController::class,
        'start',
    ]
)
    ->middleware('auth')
    ->name('technician.service-orders.start');

Route::patch(
    '/technician/service-orders/{serviceOrder}/items/{item}',
    [
        TechnicianServiceOrderController::class,
        'updateItemStatus',
    ]
)
    ->middleware('auth')
    ->name('technician.service-orders.items.update');

Route::patch(
    '/technician/service-orders/{serviceOrder}/complete',
    [
        TechnicianServiceOrderController::class,
        'complete',
    ]
)
    ->middleware('auth')
    ->name('technician.service-orders.complete');


/*
|--------------------------------------------------------------------------
| VEHICLES
|--------------------------------------------------------------------------
*/

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