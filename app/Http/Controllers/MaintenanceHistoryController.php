<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use Illuminate\Support\Facades\Auth;

class MaintenanceHistoryController extends Controller
{
    /**
     * Danh sách lịch sử bảo dưỡng
     * của khách hàng đang đăng nhập.
     */
    public function index()
    {
        $customer = $this->getAuthenticatedCustomer();

        /**
         * Chỉ lấy những phiếu đã hoàn thành.
         *
         * Đồng thời chỉ lấy dữ liệu của
         * chính khách hàng đang đăng nhập.
         */
        $serviceOrders = ServiceOrder::with([
            'vehicle.brand',
            'vehicle.vehicleModel',
            'technician',
            'items',
        ])
            ->where(
                'customer_id',
                $customer->id
            )
            ->where(
                'status',
                'COMPLETED'
            )
            ->orderByDesc('completed_at')
            ->get();

        return view(
            'maintenance-history.index',
            compact('serviceOrders')
        );
    }


    /**
     * Chi tiết một lần bảo dưỡng.
     */
    public function show(
        ServiceOrder $serviceOrder
    ) {
        $customer = $this->getAuthenticatedCustomer();

        /**
         * Không cho khách xem
         * phiếu của khách hàng khác.
         */
        if (
            (int) $serviceOrder->customer_id !==
            (int) $customer->id
        ) {
            abort(
                403,
                'Bạn không có quyền xem lịch sử bảo dưỡng này.'
            );
        }

        /**
         * Lịch sử khách hàng chỉ hiển thị
         * các phiếu đã hoàn thành.
         */
        if (
            $serviceOrder->status !==
            'COMPLETED'
        ) {
            abort(404);
        }

        $serviceOrder->load([
            'appointment',
            'vehicle.brand',
            'vehicle.vehicleModel',
            'technician',
            'items.service',
        ]);

        return view(
            'maintenance-history.show',
            compact('serviceOrder')
        );
    }


    /**
     * Lấy hồ sơ CUSTOMER
     * của tài khoản hiện tại.
     */
    private function getAuthenticatedCustomer()
    {
        $user = Auth::user();

        if (!$user) {
            abort(
                403,
                'Bạn chưa đăng nhập.'
            );
        }

        $user->load([
            'role',
            'customer',
        ]);

        /**
         * STAFF, ADMIN, TECHNICIAN
         * không sử dụng khu vực này.
         */
        if (
            !$user->role ||
            $user->role->code !== 'CUSTOMER'
        ) {
            abort(
                403,
                'Chức năng này chỉ dành cho khách hàng.'
            );
        }

        if (!$user->customer) {
            abort(
                403,
                'Không tìm thấy hồ sơ khách hàng.'
            );
        }

        return $user->customer;
    }
}