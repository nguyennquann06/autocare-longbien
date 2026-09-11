<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class CustomerInvoiceController extends Controller
{
    /**
     * Danh sách hóa đơn của khách hàng
     * đang đăng nhập.
     */
    public function index()
    {
        $customer = $this->getAuthenticatedCustomer();

        $invoices = Invoice::with([
            'serviceOrder.vehicle.brand',
            'serviceOrder.vehicle.vehicleModel',
        ])
            ->where(
                'customer_id',
                $customer->id
            )
            ->orderByDesc('issued_at')
            ->get();

        return view(
            'customer.invoices.index',
            compact('invoices')
        );
    }


    /**
     * Chi tiết hóa đơn.
     */
    public function show(
        Invoice $invoice
    ) {
        $customer = $this->getAuthenticatedCustomer();

        /**
         * CUSTOMER chỉ được xem
         * hóa đơn của chính mình.
         */
        if (
            (int) $invoice->customer_id !==
            (int) $customer->id
        ) {
            abort(
                403,
                'Bạn không có quyền xem hóa đơn này.'
            );
        }

        $invoice->load([
            'items',
            'creator',
            'serviceOrder.vehicle.brand',
            'serviceOrder.vehicle.vehicleModel',
            'serviceOrder.technician',
        ]);

        return view(
            'customer.invoices.show',
            compact('invoice')
        );
    }


    /**
     * Lấy hồ sơ CUSTOMER
     * của tài khoản đang đăng nhập.
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