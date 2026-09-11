<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Part;
use App\Models\ServiceOrder;
use Illuminate\Support\Facades\Auth;

class StaffDashboardController extends Controller
{
    /**
     * Dashboard dành cho STAFF / ADMIN.
     */
    public function index()
    {
        $this->authorizeStaff();

        $today = now()->toDateString();

        $startOfMonth = now()
            ->copy()
            ->startOfMonth();

        $endOfMonth = now()
            ->copy()
            ->endOfMonth();


        /**
         * Lịch đang chờ nhân viên xác nhận.
         */
        $pendingAppointments =
            Appointment::where(
                'status',
                'PENDING'
            )->count();


        /**
         * Tổng lịch hẹn trong ngày hôm nay.
         */
        $todayAppointments =
            Appointment::whereDate(
                'appointment_date',
                $today
            )->count();


        /**
         * Phiếu bảo dưỡng đang hoạt động.
         *
         * RECEIVED:
         * xe đã được tiếp nhận.
         *
         * IN_PROGRESS:
         * kỹ thuật viên đang thực hiện.
         */
        $activeServiceOrders =
            ServiceOrder::whereIn(
                'status',
                [
                    'RECEIVED',
                    'IN_PROGRESS',
                ]
            )->count();


        /**
         * Phiếu hoàn thành trong tháng hiện tại.
         */
        $completedServiceOrdersThisMonth =
            ServiceOrder::where(
                'status',
                'COMPLETED'
            )
                ->whereBetween(
                    'completed_at',
                    [
                        $startOfMonth,
                        $endOfMonth,
                    ]
                )
                ->count();


        /**
         * Chỉ tính doanh thu từ
         * các hóa đơn đã thanh toán.
         */
        $monthlyRevenue =
            (float) Invoice::where(
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


        /**
         * Hóa đơn đang chờ thanh toán.
         */
        $unpaidInvoices =
            Invoice::where(
                'payment_status',
                Invoice::STATUS_UNPAID
            )->count();


        /**
         * Phụ tùng đang hoạt động
         * có tồn kho <= tồn tối thiểu.
         */
        $lowStockParts =
            Part::where(
                'is_active',
                true
            )
                ->whereColumn(
                    'stock_quantity',
                    '<=',
                    'minimum_stock'
                )
                ->count();


        /**
         * 5 lịch hẹn mới nhất.
         */
        $recentAppointments =
            Appointment::with([
                'customer',
                'vehicle.brand',
                'vehicle.vehicleModel',
            ])
                ->orderByDesc(
                    'created_at'
                )
                ->limit(5)
                ->get();


        /**
         * 5 hóa đơn thanh toán gần nhất.
         */
        $recentPaidInvoices =
            Invoice::with([
                'customer',
                'serviceOrder.vehicle',
            ])
                ->where(
                    'payment_status',
                    Invoice::STATUS_PAID
                )
                ->orderByDesc(
                    'paid_at'
                )
                ->limit(5)
                ->get();


        return view(
            'staff.dashboard',
            compact(
                'pendingAppointments',
                'todayAppointments',
                'activeServiceOrders',
                'completedServiceOrdersThisMonth',
                'monthlyRevenue',
                'unpaidInvoices',
                'lowStockParts',
                'recentAppointments',
                'recentPaidInvoices'
            )
        );
    }


    /**
     * Chỉ STAFF và ADMIN được
     * truy cập Dashboard quản lý.
     */
    private function authorizeStaff(): void
    {
        $user = Auth::user();

        if (
            !$user ||
            !$user->role
        ) {
            abort(
                403,
                'Bạn không có quyền truy cập.'
            );
        }

        if (
            !in_array(
                $user->role->code,
                [
                    'STAFF',
                    'ADMIN',
                ],
                true
            )
        ) {
            abort(
                403,
                'Bạn không có quyền truy cập Dashboard quản lý.'
            );
        }
    }
}