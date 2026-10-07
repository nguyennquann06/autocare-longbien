@extends('layouts.app')


@section(
    'title',
    'Theo dõi nhân sự - AutoCare Long Biên'
)


@push('styles')

<style>
    .owner-workforce-detail {
        width: 100%;
        max-width: 1380px;

        margin: 0 auto;

        padding-bottom: 60px;
    }


    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

    .owner-workforce-detail-header {
        position: relative;

        overflow: hidden;

        margin-bottom: 20px;

        padding: 30px;

        border-radius: 24px;

        color: white;

        background:
            linear-gradient(
                120deg,
                #06101e,
                #102b5d 52%,
                #4338ca
            );

        box-shadow:
            0 25px 65px
            rgba(
                15,
                23,
                42,
                0.18
            );
    }


    .owner-workforce-detail-header::after {
        content: "";

        position: absolute;

        width: 340px;
        height: 340px;

        top: -210px;
        right: -80px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(
                    34,
                    211,
                    238,
                    0.28
                ),
                transparent 68%
            );
    }


    .owner-workforce-header-content {
        position: relative;

        z-index: 2;
    }


    .owner-workforce-back {
        display: inline-flex;

        align-items: center;

        gap: 7px;

        margin-bottom: 18px;

        color: #bfdbfe;

        text-decoration: none;

        font-size: 11px;

        font-weight: 850;
    }


    .owner-workforce-back:hover {
        color: white;
    }


    .owner-workforce-profile {
        display: flex;

        align-items: center;

        gap: 14px;
    }


    .owner-workforce-avatar {
        width: 60px;
        height: 60px;

        flex: 0 0 auto;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 50%;

        color: #4338ca;

        background:
            linear-gradient(
                135deg,
                white,
                #e0e7ff
            );

        font-size: 20px;

        font-weight: 900;
    }


    .owner-workforce-profile h1 {
        margin: 0;

        color: white;

        font-size:
            clamp(
                1.8rem,
                4vw,
                3rem
            );

        font-weight: 900;
    }


    .owner-workforce-profile-email {
        margin-top: 4px;

        color: #cbd5e1;

        font-size: 11px;
    }


    .owner-workforce-profile-role {
        display: inline-flex;

        align-items: center;

        gap: 5px;

        margin-top: 8px;

        padding:
            5px 9px;

        border-radius: 999px;

        color: #312e81;

        background: #e0e7ff;

        font-size: 8px;

        font-weight: 900;
    }


    /*
    |--------------------------------------------------------------------------
    | STATS
    |--------------------------------------------------------------------------
    */

    .owner-workforce-detail-stats {
        display: grid;

        grid-template-columns:
            repeat(
                4,
                minmax(
                    0,
                    1fr
                )
            );

        gap: 12px;

        margin-bottom: 20px;
    }


    .owner-workforce-detail-stat {
        padding: 18px;

        border:
            1px solid
            rgba(
                255,
                255,
                255,
                0.9
            );

        border-radius: 17px;

        background:
            rgba(
                255,
                255,
                255,
                0.96
            );

        box-shadow:
            0 15px 38px
            rgba(
                15,
                23,
                42,
                0.07
            );
    }


    .owner-workforce-detail-stat-label {
        color: #64748b;

        font-size: 9px;

        font-weight: 900;

        text-transform: uppercase;
    }


    .owner-workforce-detail-stat-value {
        margin-top: 4px;

        color: #0f172a;

        font-size: 23px;

        font-weight: 900;
    }


    /*
    |--------------------------------------------------------------------------
    | QUICK LINKS
    |--------------------------------------------------------------------------
    */

    .owner-workforce-sections {
        display: flex;

        flex-wrap: wrap;

        gap: 9px;

        margin-bottom: 20px;
    }


    .owner-workforce-section-link {
        min-height: 39px;

        padding:
            9px 13px;

        display: inline-flex;

        align-items: center;

        gap: 7px;

        border:
            1px solid
            #c7d2fe;

        border-radius: 11px;

        color: #4338ca;

        background: #eef2ff;

        text-decoration: none;

        font-size: 10px;

        font-weight: 900;
    }


    .owner-workforce-section-link:hover {
        color: #312e81;

        background: #e0e7ff;
    }


    /*
    |--------------------------------------------------------------------------
    | PANEL
    |--------------------------------------------------------------------------
    */

    .owner-workforce-detail-panel {
        overflow: hidden;

        margin-bottom: 20px;

        scroll-margin-top: 110px;

        border:
            1px solid
            rgba(
                255,
                255,
                255,
                0.9
            );

        border-radius: 20px;

        background:
            rgba(
                255,
                255,
                255,
                0.96
            );

        box-shadow:
            var(--ac-shadow);
    }


    .owner-workforce-detail-panel:target {
        border-color: #818cf8;

        box-shadow:
            0 0 0 3px
            rgba(
                99,
                102,
                241,
                0.09
            ),
            var(--ac-shadow);
    }


    .owner-workforce-detail-panel-header {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 15px;

        padding:
            19px 22px;

        border-bottom:
            1px solid
            #e2e8f0;

        background: #f8fafc;
    }


    .owner-workforce-detail-panel-title {
        display: flex;

        align-items: center;

        gap: 8px;

        margin: 0;

        color: #0f172a;

        font-size: 14px;

        font-weight: 900;
    }


    .owner-workforce-detail-panel-count {
        display: inline-flex;

        min-width: 29px;
        height: 29px;

        align-items: center;

        justify-content: center;

        padding:
            0 8px;

        border-radius: 999px;

        color: #4338ca;

        background: #e0e7ff;

        font-size: 9px;

        font-weight: 900;
    }


    /*
    |--------------------------------------------------------------------------
    | TECHNICIAN STATUS SUMMARY
    |--------------------------------------------------------------------------
    */

    .owner-technician-statuses {
        display: grid;

        grid-template-columns:
            repeat(
                4,
                1fr
            );

        gap: 10px;

        padding:
            18px 22px;

        border-bottom:
            1px solid
            #edf2f7;
    }


    .owner-technician-status {
        padding: 12px;

        border:
            1px solid
            #e2e8f0;

        border-radius: 13px;

        background: #f8fafc;
    }


    .owner-technician-status-label {
        color: #64748b;

        font-size: 8px;

        font-weight: 900;

        text-transform: uppercase;
    }


    .owner-technician-status-value {
        margin-top: 3px;

        color: #0f172a;

        font-size: 18px;

        font-weight: 900;
    }


    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    .owner-monitor-table-wrapper {
        overflow-x: auto;
    }


    .owner-monitor-table {
        width: 100%;

        border-collapse: collapse;
    }


    .owner-monitor-table th,
    .owner-monitor-table td {
        padding:
            13px 15px;

        border-bottom:
            1px solid
            #edf2f7;

        text-align: left;

        vertical-align: middle;
    }


    .owner-monitor-table th {
        color: #64748b;

        background: #f8fafc;

        font-size: 8px;

        font-weight: 900;

        text-transform: uppercase;

        white-space: nowrap;
    }


    .owner-monitor-table td {
        color: #475569;

        font-size: 10px;
    }


    .owner-primary-text {
        color: #0f172a;

        font-weight: 900;
    }


    .owner-secondary-text {
        margin-top: 3px;

        color: #64748b;

        font-size: 9px;
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    .owner-status {
        display: inline-flex;

        align-items: center;

        gap: 4px;

        padding:
            5px 8px;

        border-radius: 999px;

        font-size: 8px;

        font-weight: 900;

        white-space: nowrap;
    }


    .owner-status-received {
        color: #1d4ed8;

        background: #dbeafe;
    }


    .owner-status-progress {
        color: #b45309;

        background: #fef3c7;
    }


    .owner-status-completed,
    .owner-status-paid,
    .owner-status-in {
        color: #047857;

        background: #d1fae5;
    }


    .owner-status-cancelled,
    .owner-status-out {
        color: #b91c1c;

        background: #fee2e2;
    }


    .owner-status-unpaid,
    .owner-status-adjustment,
    .owner-status-pending {
        color: #b45309;

        background: #fef3c7;
    }


    .owner-status-other {
        color: #475569;

        background: #e2e8f0;
    }


    /*
    |--------------------------------------------------------------------------
    | PROGRESS
    |--------------------------------------------------------------------------
    */

    .owner-item-progress {
        min-width: 125px;
    }


    .owner-item-progress-text {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 8px;

        margin-bottom: 5px;

        color: #475569;

        font-size: 9px;

        font-weight: 800;
    }


    .owner-item-progress-track {
        width: 100%;
        height: 6px;

        overflow: hidden;

        border-radius: 999px;

        background: #e2e8f0;
    }


    .owner-item-progress-bar {
        height: 100%;

        border-radius: inherit;

        background:
            linear-gradient(
                90deg,
                #2563eb,
                #4f46e5
            );
    }


    /*
    |--------------------------------------------------------------------------
    | BUTTON
    |--------------------------------------------------------------------------
    */

    .owner-detail-button {
        min-height: 32px;

        padding:
            6px 9px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 5px;

        border:
            1px solid
            #bfdbfe;

        border-radius: 8px;

        color: #1d4ed8;

        background: #eff6ff;

        text-decoration: none;

        font-size: 8px;

        font-weight: 900;

        white-space: nowrap;
    }


    .owner-detail-button:hover {
        color: #1e40af;

        background: #dbeafe;
    }


    /*
    |--------------------------------------------------------------------------
    | INVENTORY
    |--------------------------------------------------------------------------
    */

    .owner-stock-change {
        white-space: nowrap;

        font-weight: 800;
    }


    .owner-stock-arrow {
        margin:
            0 4px;

        color: #94a3b8;
    }


    /*
    |--------------------------------------------------------------------------
    | NOTES
    |--------------------------------------------------------------------------
    */

    .owner-technician-note {
        max-width: 280px;

        color: #475569;

        font-size: 9px;

        line-height: 1.55;

        white-space: normal;
    }


    .owner-no-note {
        color: #94a3b8;

        font-style: italic;
    }


    /*
    |--------------------------------------------------------------------------
    | EMPTY
    |--------------------------------------------------------------------------
    */

    .owner-order-empty {
        padding:
            35px !important;

        color:
            #64748b !important;

        text-align: center !important;
    }


    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 900px) {
        .owner-workforce-detail-stats,
        .owner-technician-statuses {
            grid-template-columns:
                repeat(
                    2,
                    1fr
                );
        }
    }


    @media (max-width: 575px) {
        .owner-workforce-detail-stats,
        .owner-technician-statuses {
            grid-template-columns: 1fr;
        }


        .owner-workforce-detail-header {
            padding: 24px;
        }


        .owner-workforce-section-link {
            width: 100%;

            justify-content: center;
        }
    }
</style>

@endpush


@section('content')

<div class="container owner-workforce-detail">

    {{-- =====================================================
        HEADER
    ====================================================== --}}

    <section class="owner-workforce-detail-header">

        <div class="owner-workforce-header-content">

            <a
                href="{{ route('admin.workforce.index') }}"
                class="owner-workforce-back"
            >

                <i class="bi bi-arrow-left"></i>

                Quay lại giám sát nhân sự

            </a>


            <div class="owner-workforce-profile">

                <div class="owner-workforce-avatar">

                    {{
                        mb_strtoupper(
                            mb_substr(
                                $user->name,
                                0,
                                1
                            )
                        )
                    }}

                </div>


                <div>

                    <h1>
                        {{ $user->name }}
                    </h1>


                    <div class="owner-workforce-profile-email">
                        {{ $user->email }}
                    </div>


                    <span class="owner-workforce-profile-role">

                        @if (
                            $roleCode === 'STAFF'
                        )

                            <i class="bi bi-person-workspace"></i>

                        @else

                            <i class="bi bi-tools"></i>

                        @endif


                        {{ $roleCode }}

                    </span>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
        STAFF
    ====================================================== --}}

    @if (
        $roleCode === 'STAFF'
    )

        <section class="owner-workforce-detail-stats">

            <div class="owner-workforce-detail-stat">

                <div class="owner-workforce-detail-stat-label">
                    Phiếu đã tạo
                </div>

                <div class="owner-workforce-detail-stat-value">
                    {{ $user->created_service_orders_count }}
                </div>

            </div>


            <div class="owner-workforce-detail-stat">

                <div class="owner-workforce-detail-stat-label">
                    Hóa đơn đã lập
                </div>

                <div class="owner-workforce-detail-stat-value">
                    {{ $user->created_invoices_count }}
                </div>

            </div>


            <div class="owner-workforce-detail-stat">

                <div class="owner-workforce-detail-stat-label">
                    Giao dịch kho
                </div>

                <div class="owner-workforce-detail-stat-value">
                    {{ $user->inventory_transactions_count }}
                </div>

            </div>


            <div class="owner-workforce-detail-stat">

                <div class="owner-workforce-detail-stat-label">
                    Loại tài khoản
                </div>

                <div class="owner-workforce-detail-stat-value">
                    STAFF
                </div>

            </div>

        </section>


        <nav class="owner-workforce-sections">

            <a
                href="#service-orders"
                class="owner-workforce-section-link"
            >

                <i class="bi bi-clipboard-check"></i>

                Phiếu bảo dưỡng

                <strong>
                    {{ $user->created_service_orders_count }}
                </strong>

            </a>


            <a
                href="#invoices"
                class="owner-workforce-section-link"
            >

                <i class="bi bi-receipt"></i>

                Hóa đơn

                <strong>
                    {{ $user->created_invoices_count }}
                </strong>

            </a>


            <a
                href="#inventory"
                class="owner-workforce-section-link"
            >

                <i class="bi bi-box-seam"></i>

                Giao dịch kho

                <strong>
                    {{ $user->inventory_transactions_count }}
                </strong>

            </a>

        </nav>


        {{-- SERVICE ORDERS --}}

        <section
            id="service-orders"
            class="owner-workforce-detail-panel"
        >

            <div class="owner-workforce-detail-panel-header">

                <h2 class="owner-workforce-detail-panel-title">

                    <i class="bi bi-clipboard-data"></i>

                    Phiếu bảo dưỡng đã tạo

                </h2>


                <span class="owner-workforce-detail-panel-count">
                    {{ $user->created_service_orders_count }}
                </span>

            </div>


            <div class="owner-monitor-table-wrapper">

                <table class="owner-monitor-table">

                    <thead>

                        <tr>
                            <th>Phiếu</th>
                            <th>Khách hàng</th>
                            <th>Xe</th>
                            <th>Trạng thái</th>
                            <th>Kỹ thuật viên</th>
                            <th>Tiếp nhận</th>
                            <th>Hạng mục</th>
                            <th>Chi tiết</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse (
                            $serviceOrders
                            as $order
                        )

                            @php
                                $statusClass =
                                    match (
                                        $order->status
                                    ) {
                                        'RECEIVED' =>
                                            'owner-status-received',

                                        'IN_PROGRESS' =>
                                            'owner-status-progress',

                                        'COMPLETED' =>
                                            'owner-status-completed',

                                        'CANCELLED' =>
                                            'owner-status-cancelled',

                                        default =>
                                            'owner-status-other',
                                    };
                            @endphp


                            <tr>

                                <td>

                                    <div class="owner-primary-text">
                                        {{ $order->order_code }}
                                    </div>

                                    <div class="owner-secondary-text">
                                        #{{ $order->id }}
                                    </div>

                                </td>


                                <td>

                                    {{
                                        $order
                                            ->customer
                                            ?->full_name
                                        ?? '—'
                                    }}

                                </td>


                                <td>

                                    <div class="owner-primary-text">

                                        {{
                                            $order
                                                ->vehicle
                                                ?->brand
                                                ?->name
                                            ?? '—'
                                        }}

                                        {{
                                            $order
                                                ->vehicle
                                                ?->vehicleModel
                                                ?->name
                                            ?? ''
                                        }}

                                    </div>


                                    <div class="owner-secondary-text">

                                        {{
                                            $order
                                                ->vehicle
                                                ?->license_plate
                                            ?? '—'
                                        }}

                                    </div>

                                </td>


                                <td>

                                    <span
                                        class="
                                            owner-status
                                            {{ $statusClass }}
                                        "
                                    >
                                        {{ $order->status }}
                                    </span>

                                </td>


                                <td>

                                    {{
                                        $order
                                            ->technician
                                            ?->name
                                        ?? 'Chưa phân công'
                                    }}

                                </td>


                                <td>

                                    {{
                                        $order
                                            ->received_at
                                            ?->format(
                                                'd/m/Y H:i'
                                            )
                                        ?? '—'
                                    }}

                                </td>


                                <td>
                                    {{ $order->items->count() }}
                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'staff.service-orders.show',
                                            $order->id
                                        ) }}"
                                        class="owner-detail-button"
                                    >

                                        <i class="bi bi-eye"></i>

                                        Xem

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="owner-order-empty"
                                >
                                    Nhân viên chưa tạo
                                    phiếu bảo dưỡng nào.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>


        {{-- INVOICES --}}

        <section
            id="invoices"
            class="owner-workforce-detail-panel"
        >

            <div class="owner-workforce-detail-panel-header">

                <h2 class="owner-workforce-detail-panel-title">

                    <i class="bi bi-receipt-cutoff"></i>

                    Hóa đơn đã lập

                </h2>


                <span class="owner-workforce-detail-panel-count">
                    {{ $user->created_invoices_count }}
                </span>

            </div>


            <div class="owner-monitor-table-wrapper">

                <table class="owner-monitor-table">

                    <thead>

                        <tr>
                            <th>Hóa đơn</th>
                            <th>Khách hàng</th>
                            <th>Phiếu bảo dưỡng</th>
                            <th>Tổng tiền</th>
                            <th>Thanh toán</th>
                            <th>Phương thức</th>
                            <th>Ngày lập</th>
                            <th>Chi tiết</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse (
                            $invoices
                            as $invoice
                        )

                            @php
                                $invoiceStatusClass =
                                    match (
                                        $invoice->payment_status
                                    ) {
                                        'PAID' =>
                                            'owner-status-paid',

                                        'UNPAID' =>
                                            'owner-status-unpaid',

                                        'CANCELLED' =>
                                            'owner-status-cancelled',

                                        default =>
                                            'owner-status-other',
                                    };
                            @endphp


                            <tr>

                                <td>

                                    <div class="owner-primary-text">
                                        {{ $invoice->invoice_code }}
                                    </div>

                                    <div class="owner-secondary-text">
                                        #{{ $invoice->id }}
                                    </div>

                                </td>


                                <td>

                                    {{
                                        $invoice
                                            ->customer
                                            ?->full_name
                                        ?? '—'
                                    }}

                                </td>


                                <td>

                                    <div class="owner-primary-text">

                                        {{
                                            $invoice
                                                ->serviceOrder
                                                ?->order_code
                                            ?? '—'
                                        }}

                                    </div>


                                    <div class="owner-secondary-text">

                                        {{
                                            $invoice
                                                ->serviceOrder
                                                ?->vehicle
                                                ?->license_plate
                                            ?? '—'
                                        }}

                                    </div>

                                </td>


                                <td>

                                    <strong>

                                        {{
                                            number_format(
                                                (float)
                                                $invoice->total_amount,
                                                0,
                                                ',',
                                                '.'
                                            )
                                        }}
                                        đ

                                    </strong>

                                </td>


                                <td>

                                    <span
                                        class="
                                            owner-status
                                            {{ $invoiceStatusClass }}
                                        "
                                    >
                                        {{ $invoice->payment_status }}
                                    </span>

                                </td>


                                <td>

                                    {{
                                        $invoice->payment_method
                                        ?? '—'
                                    }}

                                </td>


                                <td>

                                    {{
                                        $invoice
                                            ->issued_at
                                            ?->format(
                                                'd/m/Y H:i'
                                            )
                                        ?? '—'
                                    }}

                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'staff.invoices.show',
                                            $invoice->id
                                        ) }}"
                                        class="owner-detail-button"
                                    >

                                        <i class="bi bi-eye"></i>

                                        Xem

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="owner-order-empty"
                                >
                                    Nhân viên chưa lập hóa đơn nào.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>


        {{-- INVENTORY --}}

        <section
            id="inventory"
            class="owner-workforce-detail-panel"
        >

            <div class="owner-workforce-detail-panel-header">

                <h2 class="owner-workforce-detail-panel-title">

                    <i class="bi bi-box-seam"></i>

                    Giao dịch kho đã thực hiện

                </h2>


                <span class="owner-workforce-detail-panel-count">
                    {{ $user->inventory_transactions_count }}
                </span>

            </div>


            <div class="owner-monitor-table-wrapper">

                <table class="owner-monitor-table">

                    <thead>

                        <tr>
                            <th>Phụ tùng</th>
                            <th>Loại</th>
                            <th>Số lượng</th>
                            <th>Tồn trước → sau</th>
                            <th>Đơn giá</th>
                            <th>Phiếu liên quan</th>
                            <th>Thời gian</th>
                            <th>Ghi chú</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse (
                            $inventoryTransactions
                            as $transaction
                        )

                            @php
                                $transactionClass =
                                    match (
                                        $transaction
                                            ->transaction_type
                                    ) {
                                        'IN' =>
                                            'owner-status-in',

                                        'OUT' =>
                                            'owner-status-out',

                                        'ADJUSTMENT' =>
                                            'owner-status-adjustment',

                                        default =>
                                            'owner-status-other',
                                    };
                            @endphp


                            <tr>

                                <td>

                                    <div class="owner-primary-text">

                                        {{
                                            $transaction
                                                ->part
                                                ?->name
                                            ?? '—'
                                        }}

                                    </div>


                                    <div class="owner-secondary-text">

                                        {{
                                            $transaction
                                                ->part
                                                ?->code
                                            ?? '—'
                                        }}

                                    </div>

                                </td>


                                <td>

                                    <span
                                        class="
                                            owner-status
                                            {{ $transactionClass }}
                                        "
                                    >
                                        {{ $transaction->transaction_type }}
                                    </span>

                                </td>


                                <td>
                                    <strong>
                                        {{ $transaction->quantity }}
                                    </strong>
                                </td>


                                <td>

                                    <span class="owner-stock-change">

                                        {{ $transaction->quantity_before }}

                                        <span class="owner-stock-arrow">
                                            →
                                        </span>

                                        {{ $transaction->quantity_after }}

                                    </span>

                                </td>


                                <td>

                                    @if (
                                        $transaction->unit_cost
                                        !== null
                                    )

                                        {{
                                            number_format(
                                                (float)
                                                $transaction->unit_cost,
                                                0,
                                                ',',
                                                '.'
                                            )
                                        }}
                                        đ

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    @if (
                                        $transaction
                                            ->serviceOrder
                                    )

                                        <a
                                            href="{{ route(
                                                'staff.service-orders.show',
                                                $transaction
                                                    ->serviceOrder
                                                    ->id
                                            ) }}"
                                            class="owner-detail-button"
                                        >

                                            {{
                                                $transaction
                                                    ->serviceOrder
                                                    ->order_code
                                            }}

                                        </a>

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    {{
                                        $transaction
                                            ->transaction_at
                                            ?->format(
                                                'd/m/Y H:i'
                                            )
                                        ?? '—'
                                    }}

                                </td>


                                <td>
                                    {{ $transaction->note ?: '—' }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="owner-order-empty"
                                >
                                    Nhân viên chưa thực hiện
                                    giao dịch kho nào.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>


    {{-- =====================================================
        TECHNICIAN
    ====================================================== --}}

    @else

        {{-- KPI --}}

        <section class="owner-workforce-detail-stats">

            <div class="owner-workforce-detail-stat">

                <div class="owner-workforce-detail-stat-label">
                    Tổng phiếu được giao
                </div>

                <div class="owner-workforce-detail-stat-value">
                    {{ $user->technician_service_orders_count }}
                </div>

            </div>


            <div class="owner-workforce-detail-stat">

                <div class="owner-workforce-detail-stat-label">
                    Đang phụ trách
                </div>

                <div class="owner-workforce-detail-stat-value">
                    {{ $user->active_technician_orders_count }}
                </div>

            </div>


            <div class="owner-workforce-detail-stat">

                <div class="owner-workforce-detail-stat-label">
                    Đã hoàn thành
                </div>

                <div class="owner-workforce-detail-stat-value">
                    {{ $user->completed_technician_orders_count }}
                </div>

            </div>


            <div class="owner-workforce-detail-stat">

                <div class="owner-workforce-detail-stat-label">
                    Tổng hạng mục kỹ thuật
                </div>

                <div class="owner-workforce-detail-stat-value">
                    {{ $totalTechnicianItems }}
                </div>

            </div>

        </section>


        {{-- QUICK LINKS --}}

        <nav class="owner-workforce-sections">

            <a
                href="#assigned-orders"
                class="owner-workforce-section-link"
            >

                <i class="bi bi-kanban"></i>

                Phiếu được giao

                <strong>
                    {{ $user->technician_service_orders_count }}
                </strong>

            </a>


            <a
                href="#technical-items"
                class="owner-workforce-section-link"
            >

                <i class="bi bi-list-check"></i>

                Hạng mục kỹ thuật

                <strong>
                    {{ $totalTechnicianItems }}
                </strong>

            </a>

        </nav>


        {{-- =================================================
            ASSIGNED ORDERS
        ================================================== --}}

        <section
            id="assigned-orders"
            class="owner-workforce-detail-panel"
        >

            <div class="owner-workforce-detail-panel-header">

                <h2 class="owner-workforce-detail-panel-title">

                    <i class="bi bi-kanban"></i>

                    Phiếu bảo dưỡng được phân công

                </h2>


                <span class="owner-workforce-detail-panel-count">

                    {{
                        $user
                            ->technician_service_orders_count
                    }}

                </span>

            </div>


            {{-- ORDER STATUS COUNTS --}}

            <div class="owner-technician-statuses">

                <div class="owner-technician-status">

                    <div class="owner-technician-status-label">
                        Received
                    </div>

                    <div class="owner-technician-status-value">

                        {{
                            $technicianStatusCounts[
                                'RECEIVED'
                            ]
                            ?? 0
                        }}

                    </div>

                </div>


                <div class="owner-technician-status">

                    <div class="owner-technician-status-label">
                        In Progress
                    </div>

                    <div class="owner-technician-status-value">

                        {{
                            $technicianStatusCounts[
                                'IN_PROGRESS'
                            ]
                            ?? 0
                        }}

                    </div>

                </div>


                <div class="owner-technician-status">

                    <div class="owner-technician-status-label">
                        Completed
                    </div>

                    <div class="owner-technician-status-value">

                        {{
                            $technicianStatusCounts[
                                'COMPLETED'
                            ]
                            ?? 0
                        }}

                    </div>

                </div>


                <div class="owner-technician-status">

                    <div class="owner-technician-status-label">
                        Cancelled
                    </div>

                    <div class="owner-technician-status-value">

                        {{
                            $technicianStatusCounts[
                                'CANCELLED'
                            ]
                            ?? 0
                        }}

                    </div>

                </div>

            </div>


            <div class="owner-monitor-table-wrapper">

                <table class="owner-monitor-table">

                    <thead>

                        <tr>
                            <th>Phiếu</th>
                            <th>Khách hàng</th>
                            <th>Xe</th>
                            <th>Trạng thái</th>
                            <th>Tiến độ hạng mục</th>
                            <th>Bắt đầu</th>
                            <th>Hoàn thành</th>
                            <th>Chi tiết</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse (
                            $serviceOrders
                            as $order
                        )

                            @php
                                $statusClass =
                                    match (
                                        $order->status
                                    ) {
                                        'RECEIVED' =>
                                            'owner-status-received',

                                        'IN_PROGRESS' =>
                                            'owner-status-progress',

                                        'COMPLETED' =>
                                            'owner-status-completed',

                                        'CANCELLED' =>
                                            'owner-status-cancelled',

                                        default =>
                                            'owner-status-other',
                                    };


                                $totalItems =
                                    $order
                                        ->items
                                        ->count();


                                $completedItems =
                                    $order
                                        ->items
                                        ->where(
                                            'status',
                                            'COMPLETED'
                                        )
                                        ->count();


                                $progressPercent =
                                    $totalItems > 0
                                        ? round(
                                            (
                                                $completedItems
                                                /
                                                $totalItems
                                            )
                                            *
                                            100
                                        )
                                        : 0;
                            @endphp


                            <tr>

                                <td>

                                    <div class="owner-primary-text">
                                        {{ $order->order_code }}
                                    </div>

                                    <div class="owner-secondary-text">

                                        Người tạo:

                                        {{
                                            $order
                                                ->creator
                                                ?->name
                                            ?? '—'
                                        }}

                                    </div>

                                </td>


                                <td>

                                    {{
                                        $order
                                            ->customer
                                            ?->full_name
                                        ?? '—'
                                    }}

                                </td>


                                <td>

                                    <div class="owner-primary-text">

                                        {{
                                            $order
                                                ->vehicle
                                                ?->brand
                                                ?->name
                                            ?? '—'
                                        }}

                                        {{
                                            $order
                                                ->vehicle
                                                ?->vehicleModel
                                                ?->name
                                            ?? ''
                                        }}

                                    </div>


                                    <div class="owner-secondary-text">

                                        {{
                                            $order
                                                ->vehicle
                                                ?->license_plate
                                            ?? '—'
                                        }}

                                    </div>

                                </td>


                                <td>

                                    <span
                                        class="
                                            owner-status
                                            {{ $statusClass }}
                                        "
                                    >
                                        {{ $order->status }}
                                    </span>

                                </td>


                                <td>

                                    <div class="owner-item-progress">

                                        <div class="owner-item-progress-text">

                                            <span>
                                                {{ $completedItems }}
                                                /
                                                {{ $totalItems }}
                                            </span>

                                            <span>
                                                {{ $progressPercent }}%
                                            </span>

                                        </div>


                                        <div class="owner-item-progress-track">

                                            <div
                                                class="owner-item-progress-bar"
                                                style="width: {{ $progressPercent }}%;"
                                            ></div>

                                        </div>

                                    </div>

                                </td>


                                <td>

                                    {{
                                        $order
                                            ->started_at
                                            ?->format(
                                                'd/m/Y H:i'
                                            )
                                        ?? '—'
                                    }}

                                </td>


                                <td>

                                    {{
                                        $order
                                            ->completed_at
                                            ?->format(
                                                'd/m/Y H:i'
                                            )
                                        ?? '—'
                                    }}

                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'staff.service-orders.show',
                                            $order->id
                                        ) }}"
                                        class="owner-detail-button"
                                    >

                                        <i class="bi bi-eye"></i>

                                        Xem phiếu

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="owner-order-empty"
                                >
                                    Kỹ thuật viên chưa được
                                    phân công phiếu nào.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>


        {{-- =================================================
            TECHNICAL ITEMS
        ================================================== --}}

        <section
            id="technical-items"
            class="owner-workforce-detail-panel"
        >

            <div class="owner-workforce-detail-panel-header">

                <h2 class="owner-workforce-detail-panel-title">

                    <i class="bi bi-list-check"></i>

                    Tiến độ hạng mục kỹ thuật

                </h2>


                <span class="owner-workforce-detail-panel-count">
                    {{ $totalTechnicianItems }}
                </span>

            </div>


            {{-- ITEM STATUS COUNTS --}}

            <div class="owner-technician-statuses">

                <div class="owner-technician-status">

                    <div class="owner-technician-status-label">
                        Pending
                    </div>

                    <div class="owner-technician-status-value">

                        {{
                            $technicianItemStatusCounts[
                                'PENDING'
                            ]
                            ?? 0
                        }}

                    </div>

                </div>


                <div class="owner-technician-status">

                    <div class="owner-technician-status-label">
                        In Progress
                    </div>

                    <div class="owner-technician-status-value">

                        {{
                            $technicianItemStatusCounts[
                                'IN_PROGRESS'
                            ]
                            ?? 0
                        }}

                    </div>

                </div>


                <div class="owner-technician-status">

                    <div class="owner-technician-status-label">
                        Completed
                    </div>

                    <div class="owner-technician-status-value">

                        {{
                            $technicianItemStatusCounts[
                                'COMPLETED'
                            ]
                            ?? 0
                        }}

                    </div>

                </div>


                <div class="owner-technician-status">

                    <div class="owner-technician-status-label">
                        Cancelled
                    </div>

                    <div class="owner-technician-status-value">

                        {{
                            $technicianItemStatusCounts[
                                'CANCELLED'
                            ]
                            ?? 0
                        }}

                    </div>

                </div>

            </div>


            <div class="owner-monitor-table-wrapper">

                <table class="owner-monitor-table">

                    <thead>

                        <tr>
                            <th>Phiếu</th>
                            <th>Hạng mục</th>
                            <th>Trạng thái</th>
                            <th>Thời lượng dự kiến</th>
                            <th>Ghi chú kỹ thuật</th>
                            <th>Trạng thái phiếu</th>
                            <th>Chi tiết</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse (
                            $technicianItems
                            as $item
                        )

                            @php
                                $itemStatusClass =
                                    match (
                                        $item->status
                                    ) {
                                        'PENDING' =>
                                            'owner-status-pending',

                                        'IN_PROGRESS' =>
                                            'owner-status-progress',

                                        'COMPLETED' =>
                                            'owner-status-completed',

                                        'CANCELLED' =>
                                            'owner-status-cancelled',

                                        default =>
                                            'owner-status-other',
                                    };


                                $itemOrder =
                                    $item->serviceOrder;


                                $itemOrderStatusClass =
                                    match (
                                        $itemOrder?->status
                                    ) {
                                        'RECEIVED' =>
                                            'owner-status-received',

                                        'IN_PROGRESS' =>
                                            'owner-status-progress',

                                        'COMPLETED' =>
                                            'owner-status-completed',

                                        'CANCELLED' =>
                                            'owner-status-cancelled',

                                        default =>
                                            'owner-status-other',
                                    };
                            @endphp


                            <tr>

                                <td>

                                    <div class="owner-primary-text">

                                        {{
                                            $itemOrder
                                                ?->order_code
                                            ?? '—'
                                        }}

                                    </div>


                                    <div class="owner-secondary-text">

                                        {{
                                            $itemOrder
                                                ?->vehicle
                                                ?->license_plate
                                            ?? '—'
                                        }}

                                    </div>

                                </td>


                                <td>

                                    <div class="owner-primary-text">

                                        {{
                                            $item->service_name
                                            ?: (
                                                $item
                                                    ->service
                                                    ?->name
                                                ?? 'Hạng mục #'.$item->id
                                            )
                                        }}

                                    </div>


                                    <div class="owner-secondary-text">

                                        Số lượng:
                                        {{ $item->quantity }}

                                    </div>

                                </td>


                                <td>

                                    <span
                                        class="
                                            owner-status
                                            {{ $itemStatusClass }}
                                        "
                                    >

                                        {{ $item->status }}

                                    </span>

                                </td>


                                <td>

                                    @if (
                                        $item
                                            ->estimated_duration_minutes
                                    )

                                        {{
                                            $item
                                                ->estimated_duration_minutes
                                        }}
                                        phút

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    @if (
                                        filled(
                                            $item->technician_note
                                        )
                                    )

                                        <div class="owner-technician-note">
                                            {{ $item->technician_note }}
                                        </div>

                                    @else

                                        <span class="owner-no-note">
                                            Chưa có ghi chú
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    <span
                                        class="
                                            owner-status
                                            {{ $itemOrderStatusClass }}
                                        "
                                    >

                                        {{
                                            $itemOrder
                                                ?->status
                                            ?? '—'
                                        }}

                                    </span>

                                </td>


                                <td>

                                    @if ($itemOrder)

                                        <a
                                            href="{{ route(
                                                'staff.service-orders.show',
                                                $itemOrder->id
                                            ) }}"
                                            class="owner-detail-button"
                                        >

                                            <i class="bi bi-eye"></i>

                                            Xem phiếu

                                        </a>

                                    @else

                                        —

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="owner-order-empty"
                                >
                                    Kỹ thuật viên chưa có
                                    hạng mục công việc nào.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>

    @endif

</div>

@endsection