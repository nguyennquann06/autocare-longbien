<?php

namespace App\Http\Controllers;

use App\Services\CustomerContextService;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    /**
     * Dashboard khách hàng.
     */
    public function index(
        CustomerContextService
            $customerContextService
    ) {
        $customer =
            $this
                ->getAuthenticatedCustomer();


        /**
         * Danh sách xe.
         */
        $vehicles =
            $customerContextService
                ->getVehicles(
                    $customer
                );


        /**
         * Lịch hẹn sắp tới.
         */
        $upcomingAppointments =
            $customerContextService
                ->getUpcomingAppointments(
                    $customer,
                    5
                );


        /**
         * Bảo dưỡng gần nhất.
         */
        $recentMaintenance =
            $customerContextService
                ->getRecentMaintenance(
                    $customer,
                    5
                );


        /**
         * Hóa đơn gần nhất.
         */
        $recentInvoices =
            $customerContextService
                ->getRecentInvoices(
                    $customer,
                    5
                );


        /**
         * Hóa đơn chưa thanh toán.
         */
        $unpaidSummary =
            $customerContextService
                ->getUnpaidInvoiceSummary(
                    $customer,
                    5
                );


        $unpaidInvoiceCount =
            $unpaidSummary['count'];


        $unpaidInvoiceAmount =
            $unpaidSummary['amount'];


        /**
         * Gợi ý bảo dưỡng.
         *
         * Dashboard và Chatbot hiện dùng
         * cùng một thuật toán duy nhất.
         */
        $maintenanceRecommendations =
            $customerContextService
                ->getMaintenanceRecommendations(
                    $customer,
                    $vehicles,
                    6
                );


        return view(
            'customer.dashboard',
            compact(
                'customer',
                'vehicles',
                'upcomingAppointments',
                'recentMaintenance',
                'recentInvoices',
                'unpaidInvoiceCount',
                'unpaidInvoiceAmount',
                'maintenanceRecommendations'
            )
        );
    }


    /**
     * Lấy CUSTOMER hiện tại.
     */
    private function getAuthenticatedCustomer()
    {
        $user =
            Auth::user();


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


        if (
            !$user->role
            ||
            $user->role->code
            !== 'CUSTOMER'
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