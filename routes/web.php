<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUserController;
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
use App\Http\Middleware\RedirectAdminAfterAuthentication;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    function () {
        return view(
            'home'
        );
    }
)->name(
    'home'
);


/*
|--------------------------------------------------------------------------
| PUBLIC SERVICES
|--------------------------------------------------------------------------
*/

Route::get(
    '/services',
    [
        ServiceController::class,
        'index',
    ]
)->name(
    'services.index'
);


Route::get(
    '/services/{service}',
    [
        ServiceController::class,
        'show',
    ]
)->name(
    'services.show'
);


/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/

Route::get(
    '/register',
    [
        AuthController::class,
        'showRegisterForm',
    ]
)
    ->middleware(
        RedirectAdminAfterAuthentication::class
    )
    ->name(
        'register'
    );


Route::post(
    '/register',
    [
        AuthController::class,
        'register',
    ]
)
    ->middleware(
        RedirectAdminAfterAuthentication::class
    )
    ->name(
        'register.submit'
    );


Route::get(
    '/login',
    [
        AuthController::class,
        'showLoginForm',
    ]
)
    ->middleware(
        RedirectAdminAfterAuthentication::class
    )
    ->name(
        'login'
    );


Route::post(
    '/login',
    [
        AuthController::class,
        'login',
    ]
)
    ->middleware(
        RedirectAdminAfterAuthentication::class
    )
    ->name(
        'login.submit'
    );


/*
|--------------------------------------------------------------------------
| GOOGLE OAUTH
|--------------------------------------------------------------------------
*/

Route::get(
    '/auth/google/redirect',
    [
        AuthController::class,
        'redirectToGoogle',
    ]
)->name(
    'auth.google.redirect'
);


Route::get(
    '/auth/google/callback',
    [
        AuthController::class,
        'handleGoogleCallback',
    ]
)->name(
    'auth.google.callback'
);


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post(
    '/logout',
    [
        AuthController::class,
        'logout',
    ]
)
    ->middleware(
        'auth'
    )
    ->name(
        'logout'
    );


/*
|--------------------------------------------------------------------------
| ADMIN / GARAGE OWNER AREA
|--------------------------------------------------------------------------
|
| ADMIN là Chủ xưởng.
|
| Chỉ ADMIN được truy cập /admin/*
|
*/

Route::middleware([
    'auth',
    'role:ADMIN',
])
    ->prefix(
        'admin'
    )
    ->name(
        'admin.'
    )
    ->group(
        function () {

            /*
            |--------------------------------------------------------------------------
            | DASHBOARD
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/dashboard',
                [
                    AdminDashboardController::class,
                    'index',
                ]
            )->name(
                'dashboard'
            );


            /*
            |--------------------------------------------------------------------------
            | USER MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/users',
                [
                    AdminUserController::class,
                    'index',
                ]
            )->name(
                'users.index'
            );


            Route::get(
                '/users/{user}',
                [
                    AdminUserController::class,
                    'show',
                ]
            )->name(
                'users.show'
            );


            Route::patch(
                '/users/{user}/role',
                [
                    AdminUserController::class,
                    'updateRole',
                ]
            )->name(
                'users.role.update'
            );
        }
    );


/*
|--------------------------------------------------------------------------
| CUSTOMER AREA
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:CUSTOMER',
])->group(
    function () {

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
        )->name(
            'customer.dashboard'
        );


        /*
        |--------------------------------------------------------------------------
        | CHATBOT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/chat',
            [
                ChatController::class,
                'index',
            ]
        )->name(
            'chat.index'
        );


        Route::post(
            '/chat/messages',
            [
                ChatController::class,
                'storeMessage',
            ]
        )->name(
            'chat.messages.store'
        );


        /*
        |--------------------------------------------------------------------------
        | VEHICLES
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/vehicles',
            [
                VehicleController::class,
                'index',
            ]
        )->name(
            'vehicles.index'
        );


        Route::get(
            '/vehicles/create',
            [
                VehicleController::class,
                'create',
            ]
        )->name(
            'vehicles.create'
        );


        Route::post(
            '/vehicles',
            [
                VehicleController::class,
                'store',
            ]
        )->name(
            'vehicles.store'
        );


        Route::get(
            '/vehicles/{vehicle}/edit',
            [
                VehicleController::class,
                'edit',
            ]
        )->name(
            'vehicles.edit'
        );


        Route::put(
            '/vehicles/{vehicle}',
            [
                VehicleController::class,
                'update',
            ]
        )->name(
            'vehicles.update'
        );


        Route::delete(
            '/vehicles/{vehicle}',
            [
                VehicleController::class,
                'destroy',
            ]
        )->name(
            'vehicles.destroy'
        );


        Route::get(
            '/vehicles/{vehicle}',
            [
                VehicleController::class,
                'show',
            ]
        )->name(
            'vehicles.show'
        );


        Route::get(
            '/vehicle-models/{brandId}',
            [
                VehicleController::class,
                'getModels',
            ]
        )->name(
            'vehicles.models'
        );


        /*
        |--------------------------------------------------------------------------
        | APPOINTMENTS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/appointments',
            [
                AppointmentController::class,
                'index',
            ]
        )->name(
            'appointments.index'
        );


        Route::get(
            '/appointments/create',
            [
                AppointmentController::class,
                'create',
            ]
        )->name(
            'appointments.create'
        );


        Route::post(
            '/appointments',
            [
                AppointmentController::class,
                'store',
            ]
        )->name(
            'appointments.store'
        );


        Route::patch(
            '/appointments/{appointment}/cancel',
            [
                AppointmentController::class,
                'cancel',
            ]
        )->name(
            'appointments.cancel'
        );


        Route::get(
            '/appointments/{appointment}',
            [
                AppointmentController::class,
                'show',
            ]
        )->name(
            'appointments.show'
        );


        /*
        |--------------------------------------------------------------------------
        | MAINTENANCE HISTORY
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/maintenance-history',
            [
                MaintenanceHistoryController::class,
                'index',
            ]
        )->name(
            'maintenance-history.index'
        );


        Route::get(
            '/maintenance-history/{serviceOrder}',
            [
                MaintenanceHistoryController::class,
                'show',
            ]
        )->name(
            'maintenance-history.show'
        );


        /*
        |--------------------------------------------------------------------------
        | CUSTOMER INVOICES
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/my-invoices',
            [
                CustomerInvoiceController::class,
                'index',
            ]
        )->name(
            'customer.invoices.index'
        );


        Route::get(
            '/my-invoices/{invoice}',
            [
                CustomerInvoiceController::class,
                'show',
            ]
        )->name(
            'customer.invoices.show'
        );
    }
);


/*
|--------------------------------------------------------------------------
| STAFF OPERATIONS
|--------------------------------------------------------------------------
|
| ADMIN là Chủ xưởng nên vẫn có toàn bộ
| quyền nghiệp vụ của STAFF.
|
*/

Route::middleware([
    'auth',
    'role:STAFF,ADMIN',
])
    ->prefix(
        'staff'
    )
    ->name(
        'staff.'
    )
    ->group(
        function () {

            /*
            |--------------------------------------------------------------------------
            | STAFF DASHBOARD
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/dashboard',
                [
                    StaffDashboardController::class,
                    'index',
                ]
            )->name(
                'dashboard'
            );


            /*
            |--------------------------------------------------------------------------
            | APPOINTMENTS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/appointments',
                [
                    StaffAppointmentController::class,
                    'index',
                ]
            )->name(
                'appointments.index'
            );


            Route::patch(
                '/appointments/{appointment}/status',
                [
                    StaffAppointmentController::class,
                    'updateStatus',
                ]
            )->name(
                'appointments.updateStatus'
            );


            Route::get(
                '/appointments/{appointment}',
                [
                    StaffAppointmentController::class,
                    'show',
                ]
            )->name(
                'appointments.show'
            );


            /*
            |--------------------------------------------------------------------------
            | SERVICE ORDERS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/appointments/{appointment}/service-order/create',
                [
                    StaffServiceOrderController::class,
                    'create',
                ]
            )->name(
                'service-orders.create'
            );


            Route::post(
                '/appointments/{appointment}/service-order',
                [
                    StaffServiceOrderController::class,
                    'store',
                ]
            )->name(
                'service-orders.store'
            );


            Route::get(
                '/service-orders/{serviceOrder}',
                [
                    StaffServiceOrderController::class,
                    'show',
                ]
            )->name(
                'service-orders.show'
            );


            Route::post(
                '/service-orders/{serviceOrder}/parts',
                [
                    StaffServiceOrderController::class,
                    'addPart',
                ]
            )->name(
                'service-orders.parts.store'
            );


            /*
            |--------------------------------------------------------------------------
            | INVOICES
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/service-orders/{serviceOrder}/invoice/create',
                [
                    StaffInvoiceController::class,
                    'create',
                ]
            )->name(
                'invoices.create'
            );


            Route::post(
                '/service-orders/{serviceOrder}/invoice',
                [
                    StaffInvoiceController::class,
                    'store',
                ]
            )->name(
                'invoices.store'
            );


            Route::patch(
                '/invoices/{invoice}/pay',
                [
                    StaffInvoiceController::class,
                    'pay',
                ]
            )->name(
                'invoices.pay'
            );


            Route::get(
                '/invoices/{invoice}',
                [
                    StaffInvoiceController::class,
                    'show',
                ]
            )->name(
                'invoices.show'
            );


            /*
            |--------------------------------------------------------------------------
            | INVENTORY
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/parts',
                [
                    StaffPartController::class,
                    'index',
                ]
            )->name(
                'parts.index'
            );


            Route::get(
                '/parts/{part}/stock-in',
                [
                    StaffPartController::class,
                    'showStockInForm',
                ]
            )->name(
                'parts.stock-in.form'
            );


            Route::post(
                '/parts/{part}/stock-in',
                [
                    StaffPartController::class,
                    'stockIn',
                ]
            )->name(
                'parts.stock-in'
            );
        }
    );


/*
|--------------------------------------------------------------------------
| TECHNICIAN AREA
|--------------------------------------------------------------------------
|
| Quyền giám sát/can thiệp của ADMIN
| đối với TECHNICIAN sẽ được triển khai
| ở bước riêng.
|
*/

Route::middleware([
    'auth',
    'role:TECHNICIAN',
])
    ->prefix(
        'technician'
    )
    ->name(
        'technician.'
    )
    ->group(
        function () {

            Route::get(
                '/service-orders',
                [
                    TechnicianServiceOrderController::class,
                    'index',
                ]
            )->name(
                'service-orders.index'
            );


            Route::get(
                '/service-orders/{serviceOrder}',
                [
                    TechnicianServiceOrderController::class,
                    'show',
                ]
            )->name(
                'service-orders.show'
            );


            Route::patch(
                '/service-orders/{serviceOrder}/start',
                [
                    TechnicianServiceOrderController::class,
                    'start',
                ]
            )->name(
                'service-orders.start'
            );


            Route::patch(
                '/service-orders/{serviceOrder}/items/{item}',
                [
                    TechnicianServiceOrderController::class,
                    'updateItemStatus',
                ]
            )->name(
                'service-orders.items.update'
            );


            Route::patch(
                '/service-orders/{serviceOrder}/complete',
                [
                    TechnicianServiceOrderController::class,
                    'complete',
                ]
            )->name(
                'service-orders.complete'
            );
        }
    );