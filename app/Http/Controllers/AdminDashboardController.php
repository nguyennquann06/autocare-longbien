<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Part;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Dashboard tổng quan dành riêng
     * cho ADMIN / Chủ xưởng.
     */
    public function index(): View
    {
        $today =
            now()->toDateString();

        $startOfMonth =
            now()
                ->copy()
                ->startOfMonth();

        $endOfMonth =
            now()
                ->copy()
                ->endOfMonth();


        /*
        |--------------------------------------------------------------------------
        | ACCOUNT STATISTICS
        |--------------------------------------------------------------------------
        */

        $totalUsers =
            User::query()
                ->count();


        $customerUsers =
            User::query()
                ->whereHas(
                    'role',
                    fn ($query) =>
                        $query->where(
                            'code',
                            'CUSTOMER'
                        )
                )
                ->count();


        $staffUsers =
            User::query()
                ->whereHas(
                    'role',
                    fn ($query) =>
                        $query->where(
                            'code',
                            'STAFF'
                        )
                )
                ->count();


        $technicianUsers =
            User::query()
                ->whereHas(
                    'role',
                    fn ($query) =>
                        $query->where(
                            'code',
                            'TECHNICIAN'
                        )
                )
                ->count();


        $adminUsers =
            User::query()
                ->whereHas(
                    'role',
                    fn ($query) =>
                        $query->where(
                            'code',
                            'ADMIN'
                        )
                )
                ->count();


        /*
        |--------------------------------------------------------------------------
        | GARAGE OPERATION STATISTICS
        |--------------------------------------------------------------------------
        */

        $todayAppointments =
            Appointment::query()
                ->whereDate(
                    'appointment_date',
                    $today
                )
                ->count();


        $pendingAppointments =
            Appointment::query()
                ->where(
                    'status',
                    'PENDING'
                )
                ->count();


        $activeServiceOrders =
            ServiceOrder::query()
                ->whereIn(
                    'status',
                    [
                        'RECEIVED',
                        'IN_PROGRESS',
                    ]
                )
                ->count();


        $unpaidInvoices =
            Invoice::query()
                ->where(
                    'payment_status',
                    Invoice::STATUS_UNPAID
                )
                ->count();


        /*
        |--------------------------------------------------------------------------
        | REVENUE
        |--------------------------------------------------------------------------
        |
        | Chỉ hóa đơn PAID mới được tính
        | là doanh thu thực tế.
        |
        */

        $monthlyRevenue =
            (float)
            Invoice::query()
                ->where(
                    'payment_status',
                    Invoice::STATUS_PAID
                )
                ->whereBetween(
                    'paid_at',
                    [
                        $startOfMonth,
                        $endOfMonth,
                    ]
                )
                ->sum(
                    'total_amount'
                );


        /*
        |--------------------------------------------------------------------------
        | INVENTORY
        |--------------------------------------------------------------------------
        */

        $lowStockCount =
            Part::query()
                ->where(
                    'is_active',
                    true
                )
                ->whereColumn(
                    'stock_quantity',
                    '<=',
                    'minimum_stock'
                )
                ->count();


        $lowStockParts =
            Part::query()
                ->where(
                    'is_active',
                    true
                )
                ->whereColumn(
                    'stock_quantity',
                    '<=',
                    'minimum_stock'
                )
                ->orderBy(
                    'stock_quantity'
                )
                ->orderBy(
                    'name'
                )
                ->limit(6)
                ->get();


        /*
        |--------------------------------------------------------------------------
        | SERVICE ORDER STATUS
        |--------------------------------------------------------------------------
        */

        $serviceOrderStatusCounts = [
            'RECEIVED' =>
                ServiceOrder::query()
                    ->where(
                        'status',
                        'RECEIVED'
                    )
                    ->count(),

            'IN_PROGRESS' =>
                ServiceOrder::query()
                    ->where(
                        'status',
                        'IN_PROGRESS'
                    )
                    ->count(),

            'COMPLETED' =>
                ServiceOrder::query()
                    ->where(
                        'status',
                        'COMPLETED'
                    )
                    ->count(),

            'CANCELLED' =>
                ServiceOrder::query()
                    ->where(
                        'status',
                        'CANCELLED'
                    )
                    ->count(),
        ];


        /*
        |--------------------------------------------------------------------------
        | RECENT PAID INVOICES
        |--------------------------------------------------------------------------
        */

        $recentPaidInvoices =
            Invoice::query()
                ->with([
                    'customer',
                    'creator.role',
                ])
                ->where(
                    'payment_status',
                    Invoice::STATUS_PAID
                )
                ->orderByDesc(
                    'paid_at'
                )
                ->limit(6)
                ->get();


        /*
        |--------------------------------------------------------------------------
        | RECENT USERS
        |--------------------------------------------------------------------------
        */

        $recentUsers =
            User::query()
                ->with(
                    'role'
                )
                ->latest(
                    'id'
                )
                ->limit(6)
                ->get();


        return view(
            'admin.dashboard',
            compact(
                'totalUsers',
                'customerUsers',
                'staffUsers',
                'technicianUsers',
                'adminUsers',
                'todayAppointments',
                'pendingAppointments',
                'activeServiceOrders',
                'unpaidInvoices',
                'monthlyRevenue',
                'lowStockCount',
                'lowStockParts',
                'serviceOrderStatusCounts',
                'recentPaidInvoices',
                'recentUsers'
            )
        );
    }
}