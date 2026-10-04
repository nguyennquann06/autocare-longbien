@extends('layouts.app')


@section(
    'title',
    'Quản trị hệ thống - AutoCare Long Biên'
)


@push('styles')

<style>
    .owner-dashboard {
        width: 100%;
        max-width: 1420px;

        margin: 0 auto;

        padding-bottom: 45px;
    }


    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .owner-hero {
        position: relative;

        overflow: hidden;

        margin-bottom: 24px;

        padding: 38px;

        border-radius: 28px;

        color: white;

        background:
            linear-gradient(
                120deg,
                #06101e 0%,
                #102b5d 48%,
                #3730a3 100%
            );

        box-shadow:
            0 28px 70px
            rgba(15, 23, 42, 0.20);
    }


    .owner-hero::before {
        content: "";

        position: absolute;

        width: 420px;
        height: 420px;

        right: -130px;
        top: -260px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, 0.36),
                transparent 68%
            );
    }


    .owner-hero-content {
        position: relative;

        z-index: 2;
    }


    .owner-chip {
        display: inline-flex;

        align-items: center;

        gap: 8px;

        margin-bottom: 16px;

        padding:
            7px 12px;

        border:
            1px solid
            rgba(255, 255, 255, 0.15);

        border-radius: 999px;

        color: #dbeafe;

        background:
            rgba(255, 255, 255, 0.08);

        font-size: 10px;

        font-weight: 900;

        letter-spacing: 0.08em;

        text-transform: uppercase;
    }


    .owner-chip-dot {
        width: 8px;
        height: 8px;

        border-radius: 50%;

        background: #67e8f9;

        box-shadow:
            0 0 12px
            #67e8f9;
    }


    .owner-hero h1 {
        margin: 0;

        color: white;

        font-size:
            clamp(
                2.2rem,
                5vw,
                4rem
            );

        font-weight: 900;

        letter-spacing: -0.055em;
    }


    .owner-hero p {
        max-width: 760px;

        margin:
            12px 0 0;

        color: #cbd5e1;

        font-size: 14px;

        line-height: 1.8;
    }


    /*
    |--------------------------------------------------------------------------
    | KPI
    |--------------------------------------------------------------------------
    */

    .owner-kpi-grid {
        display: grid;

        grid-template-columns:
            repeat(
                4,
                minmax(0, 1fr)
            );

        gap: 15px;

        margin-bottom: 24px;
    }


    .owner-kpi {
        min-height: 150px;

        padding: 20px;

        border:
            1px solid
            rgba(255, 255, 255, 0.88);

        border-radius: 19px;

        background:
            rgba(255, 255, 255, 0.94);

        box-shadow:
            var(--ac-shadow);

        backdrop-filter:
            blur(16px);
    }


    .owner-kpi-icon {
        width: 44px;
        height: 44px;

        display: flex;

        align-items: center;

        justify-content: center;

        margin-bottom: 14px;

        border-radius: 14px;

        color: #2563eb;

        background: #eff6ff;

        font-size: 19px;
    }


    .owner-kpi.success
    .owner-kpi-icon {
        color: #059669;

        background: #ecfdf5;
    }


    .owner-kpi.warning
    .owner-kpi-icon {
        color: #d97706;

        background: #fff7ed;
    }


    .owner-kpi.purple
    .owner-kpi-icon {
        color: #7c3aed;

        background: #f5f3ff;
    }


    .owner-kpi-label {
        color: #64748b;

        font-size: 10px;

        font-weight: 850;

        text-transform: uppercase;

        letter-spacing: 0.05em;
    }


    .owner-kpi-value {
        margin-top: 6px;

        color: #0f172a;

        font-size: 25px;

        font-weight: 900;

        letter-spacing: -0.04em;
    }


    .owner-kpi-value.money {
        color: #059669;

        font-size: 22px;
    }


    /*
    |--------------------------------------------------------------------------
    | ROLE SUMMARY
    |--------------------------------------------------------------------------
    */

    .owner-role-grid {
        display: grid;

        grid-template-columns:
            repeat(
                5,
                minmax(0, 1fr)
            );

        gap: 12px;

        margin-bottom: 24px;
    }


    .owner-role-card {
        padding: 16px;

        border:
            1px solid
            #e2e8f0;

        border-radius: 16px;

        background:
            rgba(255, 255, 255, 0.92);
    }


    .owner-role-label {
        color: #64748b;

        font-size: 9px;

        font-weight: 900;

        letter-spacing: 0.05em;

        text-transform: uppercase;
    }


    .owner-role-value {
        margin-top: 4px;

        color: #0f172a;

        font-size: 21px;

        font-weight: 900;
    }


    /*
    |--------------------------------------------------------------------------
    | CONTENT
    |--------------------------------------------------------------------------
    */

    .owner-content-grid {
        display: grid;

        grid-template-columns:
            minmax(0, 1.2fr)
            minmax(320px, 0.8fr);

        gap: 20px;

        margin-bottom: 24px;
    }


    .owner-panel {
        overflow: hidden;

        border:
            1px solid
            rgba(255, 255, 255, 0.88);

        border-radius: 21px;

        background:
            rgba(255, 255, 255, 0.94);

        box-shadow:
            var(--ac-shadow);

        backdrop-filter:
            blur(16px);
    }


    .owner-panel-header {
        padding:
            21px 23px;

        border-bottom:
            1px solid
            #edf1f6;
    }


    .owner-panel-title {
        display: flex;

        align-items: center;

        gap: 10px;

        margin: 0;

        color: #0f172a;

        font-size: 17px;

        font-weight: 900;
    }


    .owner-panel-title i {
        color: #2563eb;
    }


    .owner-panel-subtitle {
        margin-top: 5px;

        color: #64748b;

        font-size: 11px;
    }


    .owner-panel-body {
        padding: 22px;
    }


    /*
    |--------------------------------------------------------------------------
    | SERVICE ORDER STATUS
    |--------------------------------------------------------------------------
    */

    .owner-status-row {
        display: flex;

        justify-content: space-between;

        align-items: center;

        gap: 15px;

        padding: 13px 0;

        border-bottom:
            1px solid
            #edf2f7;
    }


    .owner-status-row:last-child {
        border-bottom: none;
    }


    .owner-status-name {
        color: #475569;

        font-size: 12px;

        font-weight: 750;
    }


    .owner-status-value {
        min-width: 38px;

        padding:
            5px 9px;

        border-radius: 999px;

        color: #1d4ed8;

        background: #dbeafe;

        text-align: center;

        font-size: 11px;

        font-weight: 900;
    }


    /*
    |--------------------------------------------------------------------------
    | STOCK
    |--------------------------------------------------------------------------
    */

    .owner-stock-row {
        padding: 13px 0;

        border-bottom:
            1px solid
            #edf2f7;
    }


    .owner-stock-row:last-child {
        border-bottom: none;
    }


    .owner-stock-name {
        color: #0f172a;

        font-size: 12px;

        font-weight: 850;
    }


    .owner-stock-meta {
        margin-top: 4px;

        color: #64748b;

        font-size: 10px;
    }


    .owner-stock-danger {
        color: #dc2626;

        font-weight: 900;
    }


    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    .owner-table-wrapper {
        overflow-x: auto;
    }


    .owner-table {
        width: 100%;

        border-collapse: collapse;
    }


    .owner-table th,
    .owner-table td {
        padding:
            13px 20px;

        border-bottom:
            1px solid
            #edf2f7;

        text-align: left;

        vertical-align: middle;
    }


    .owner-table th {
        color: #64748b;

        background: #f8fafc;

        font-size: 9px;

        font-weight: 900;

        text-transform: uppercase;

        letter-spacing: 0.05em;
    }


    .owner-table td {
        color: #475569;

        font-size: 11px;
    }


    .owner-table-name {
        color: #0f172a;

        font-weight: 850;
    }


    .owner-role-badge {
        display: inline-flex;

        padding:
            5px 8px;

        border-radius: 999px;

        color: #1d4ed8;

        background: #dbeafe;

        font-size: 8px;

        font-weight: 900;
    }


    /*
    |--------------------------------------------------------------------------
    | QUICK ACTIONS
    |--------------------------------------------------------------------------
    */

    .owner-action-grid {
        display: grid;

        grid-template-columns:
            repeat(
                4,
                minmax(0, 1fr)
            );

        gap: 14px;

        margin-bottom: 24px;
    }


    .owner-action {
        min-height: 145px;

        padding: 20px;

        border-radius: 18px;

        color: white;

        text-decoration: none;

        background:
            linear-gradient(
                145deg,
                #07111f,
                #0d3476
            );

        box-shadow:
            0 18px 40px
            rgba(13, 52, 118, 0.18);

        transition:
            transform 0.22s ease,
            box-shadow 0.22s ease;
    }


    .owner-action:hover {
        color: white;

        transform:
            translateY(-4px);

        box-shadow:
            0 24px 55px
            rgba(13, 52, 118, 0.26);
    }


    .owner-action-icon {
        width: 43px;
        height: 43px;

        display: flex;

        align-items: center;

        justify-content: center;

        margin-bottom: 15px;

        border-radius: 13px;

        color: #67e8f9;

        background:
            rgba(255, 255, 255, 0.09);

        font-size: 18px;
    }


    .owner-action-title {
        font-size: 12px;

        font-weight: 900;
    }


    .owner-action-text {
        margin-top: 6px;

        color: #bfdbfe;

        font-size: 9px;

        line-height: 1.6;
    }


    /*
    |--------------------------------------------------------------------------
    | RECENT INVOICES
    |--------------------------------------------------------------------------
    */

    .owner-invoice-row {
        display: flex;

        justify-content: space-between;

        gap: 16px;

        padding: 13px 0;

        border-bottom:
            1px solid
            #edf2f7;
    }


    .owner-invoice-row:last-child {
        border-bottom: none;
    }


    .owner-invoice-code {
        color: #0f172a;

        font-size: 11px;

        font-weight: 900;
    }


    .owner-invoice-customer {
        margin-top: 3px;

        color: #64748b;

        font-size: 10px;
    }


    .owner-invoice-amount {
        color: #059669;

        font-size: 12px;

        font-weight: 900;

        white-space: nowrap;
    }


    .owner-empty {
        padding: 22px;

        border:
            1px dashed
            #cbd5e1;

        border-radius: 14px;

        color: #64748b;

        background: #f8fafc;

        text-align: center;

        font-size: 11px;
    }


    @media (max-width: 1199px) {
        .owner-kpi-grid,
        .owner-action-grid {
            grid-template-columns:
                repeat(
                    2,
                    1fr
                );
        }


        .owner-role-grid {
            grid-template-columns:
                repeat(
                    3,
                    1fr
                );
        }
    }


    @media (max-width: 991px) {
        .owner-content-grid {
            grid-template-columns: 1fr;
        }
    }


    @media (max-width: 575px) {
        .owner-kpi-grid,
        .owner-role-grid,
        .owner-action-grid {
            grid-template-columns: 1fr;
        }


        .owner-hero {
            padding: 26px;
        }
    }
</style>

@endpush


@section('content')

<div class="container owner-dashboard">

    <section
        class="owner-hero"
        data-reveal="zoom"
    >

        <div class="owner-hero-content">

            <div class="owner-chip">

                <span class="owner-chip-dot"></span>

                Garage Owner Control Center

            </div>


            <h1>
                Tổng quan chủ xưởng
            </h1>


            <p>
                Theo dõi hoạt động vận hành,
                doanh thu, nhân sự, phiếu bảo dưỡng
                và tình trạng kho phụ tùng
                của AutoCare Long Biên.
            </p>

        </div>

    </section>


    <section class="owner-kpi-grid">

        <div class="owner-kpi success">

            <div class="owner-kpi-icon">
                <i class="bi bi-cash-stack"></i>
            </div>

            <div class="owner-kpi-label">
                Doanh thu tháng
            </div>

            <div class="owner-kpi-value money">

                {{
                    number_format(
                        $monthlyRevenue,
                        0,
                        ',',
                        '.'
                    )
                }} đ

            </div>

        </div>


        <div class="owner-kpi">

            <div class="owner-kpi-icon">
                <i class="bi bi-calendar2-day"></i>
            </div>

            <div class="owner-kpi-label">
                Lịch hẹn hôm nay
            </div>

            <div class="owner-kpi-value">
                {{ $todayAppointments }}
            </div>

        </div>


        <div class="owner-kpi purple">

            <div class="owner-kpi-icon">
                <i class="bi bi-tools"></i>
            </div>

            <div class="owner-kpi-label">
                Phiếu đang xử lý
            </div>

            <div class="owner-kpi-value">
                {{ $activeServiceOrders }}
            </div>

        </div>


        <div class="owner-kpi warning">

            <div class="owner-kpi-icon">
                <i class="bi bi-box-seam"></i>
            </div>

            <div class="owner-kpi-label">
                Phụ tùng cần chú ý
            </div>

            <div class="owner-kpi-value">
                {{ $lowStockCount }}
            </div>

        </div>


        <div class="owner-kpi warning">

            <div class="owner-kpi-icon">
                <i class="bi bi-hourglass-split"></i>
            </div>

            <div class="owner-kpi-label">
                Lịch chờ xác nhận
            </div>

            <div class="owner-kpi-value">
                {{ $pendingAppointments }}
            </div>

        </div>


        <div class="owner-kpi warning">

            <div class="owner-kpi-icon">
                <i class="bi bi-receipt"></i>
            </div>

            <div class="owner-kpi-label">
                Hóa đơn chưa thanh toán
            </div>

            <div class="owner-kpi-value">
                {{ $unpaidInvoices }}
            </div>

        </div>


        <div class="owner-kpi">

            <div class="owner-kpi-icon">
                <i class="bi bi-people-fill"></i>
            </div>

            <div class="owner-kpi-label">
                Tổng tài khoản
            </div>

            <div class="owner-kpi-value">
                {{ $totalUsers }}
            </div>

        </div>

    </section>


    <section class="owner-role-grid">

        <div class="owner-role-card">
            <div class="owner-role-label">
                Tất cả tài khoản
            </div>
            <div class="owner-role-value">
                {{ $totalUsers }}
            </div>
        </div>


        <div class="owner-role-card">
            <div class="owner-role-label">
                Customer
            </div>
            <div class="owner-role-value">
                {{ $customerUsers }}
            </div>
        </div>


        <div class="owner-role-card">
            <div class="owner-role-label">
                Staff
            </div>
            <div class="owner-role-value">
                {{ $staffUsers }}
            </div>
        </div>


        <div class="owner-role-card">
            <div class="owner-role-label">
                Technician
            </div>
            <div class="owner-role-value">
                {{ $technicianUsers }}
            </div>
        </div>


        <div class="owner-role-card">
            <div class="owner-role-label">
                Admin
            </div>
            <div class="owner-role-value">
                {{ $adminUsers }}
            </div>
        </div>

    </section>


    <section class="owner-action-grid">

        <a
            href="{{ route('staff.dashboard') }}"
            class="owner-action"
        >

            <div class="owner-action-icon">
                <i class="bi bi-speedometer2"></i>
            </div>

            <div class="owner-action-title">
                Trung tâm vận hành
            </div>

            <div class="owner-action-text">
                Truy cập toàn bộ dashboard
                nghiệp vụ đang dùng bởi STAFF.
            </div>

        </a>


        <a
            href="{{ route('staff.appointments.index') }}"
            class="owner-action"
        >

            <div class="owner-action-icon">
                <i class="bi bi-calendar-check"></i>
            </div>

            <div class="owner-action-title">
                Lịch hẹn
            </div>

            <div class="owner-action-text">
                Theo dõi và xử lý toàn bộ
                lịch hẹn của garage.
            </div>

        </a>


        <a
            href="{{ route('staff.parts.index') }}"
            class="owner-action"
        >

            <div class="owner-action-icon">
                <i class="bi bi-box-seam"></i>
            </div>

            <div class="owner-action-title">
                Kho phụ tùng
            </div>

            <div class="owner-action-text">
                Kiểm soát tồn kho và
                hoạt động nhập phụ tùng.
            </div>

        </a>


        <a
            href="{{ route('services.index') }}"
            class="owner-action"
        >

            <div class="owner-action-icon">
                <i class="bi bi-wrench-adjustable"></i>
            </div>

            <div class="owner-action-title">
                Danh mục dịch vụ
            </div>

            <div class="owner-action-text">
                Theo dõi dịch vụ,
                giá và chu kỳ bảo dưỡng.
            </div>

        </a>

    </section>


    <section class="owner-content-grid">

        <div class="owner-panel">

            <div class="owner-panel-header">

                <h2 class="owner-panel-title">

                    <i class="bi bi-kanban"></i>

                    Trạng thái phiếu bảo dưỡng

                </h2>

                <div class="owner-panel-subtitle">
                    Toàn bộ phiếu bảo dưỡng
                    đang có trong hệ thống.
                </div>

            </div>


            <div class="owner-panel-body">

                <div class="owner-status-row">

                    <span class="owner-status-name">
                        Đã tiếp nhận
                    </span>

                    <span class="owner-status-value">
                        {{ $serviceOrderStatusCounts['RECEIVED'] }}
                    </span>

                </div>


                <div class="owner-status-row">

                    <span class="owner-status-name">
                        Đang thực hiện
                    </span>

                    <span class="owner-status-value">
                        {{ $serviceOrderStatusCounts['IN_PROGRESS'] }}
                    </span>

                </div>


                <div class="owner-status-row">

                    <span class="owner-status-name">
                        Đã hoàn thành
                    </span>

                    <span class="owner-status-value">
                        {{ $serviceOrderStatusCounts['COMPLETED'] }}
                    </span>

                </div>


                <div class="owner-status-row">

                    <span class="owner-status-name">
                        Đã hủy
                    </span>

                    <span class="owner-status-value">
                        {{ $serviceOrderStatusCounts['CANCELLED'] }}
                    </span>

                </div>

            </div>

        </div>


        <div class="owner-panel">

            <div class="owner-panel-header">

                <h2 class="owner-panel-title">

                    <i class="bi bi-exclamation-triangle"></i>

                    Tồn kho cần chú ý

                </h2>

                <div class="owner-panel-subtitle">
                    Phụ tùng có tồn kho nhỏ hơn
                    hoặc bằng mức tồn tối thiểu.
                </div>

            </div>


            <div class="owner-panel-body">

                @forelse (
                    $lowStockParts
                    as $part
                )

                    <div class="owner-stock-row">

                        <div class="owner-stock-name">
                            {{ $part->name }}
                        </div>

                        <div class="owner-stock-meta">

                            Mã:
                            {{ $part->code }}

                            ·

                            Tồn:

                            <span class="owner-stock-danger">
                                {{ $part->stock_quantity }}
                                {{ $part->unit }}
                            </span>

                            ·

                            Tối thiểu:
                            {{ $part->minimum_stock }}

                        </div>

                    </div>

                @empty

                    <div class="owner-empty">
                        Không có phụ tùng nào
                        dưới mức tồn tối thiểu.
                    </div>

                @endforelse

            </div>

        </div>

    </section>


    <section class="owner-content-grid">

        <div class="owner-panel">

            <div class="owner-panel-header">

                <h2 class="owner-panel-title">

                    <i class="bi bi-wallet2"></i>

                    Thanh toán gần đây

                </h2>

                <div class="owner-panel-subtitle">
                    Chỉ hiển thị hóa đơn đã thanh toán.
                </div>

            </div>


            <div class="owner-panel-body">

                @forelse (
                    $recentPaidInvoices
                    as $invoice
                )

                    <div class="owner-invoice-row">

                        <div>

                            <div class="owner-invoice-code">
                                {{ $invoice->invoice_code }}
                            </div>

                            <div class="owner-invoice-customer">

                                {{
                                    $invoice
                                        ->customer
                                        ?->full_name
                                    ?? 'Không xác định'
                                }}

                                @if ($invoice->creator)

                                    · lập bởi

                                    {{
                                        $invoice
                                            ->creator
                                            ->name
                                    }}

                                @endif

                            </div>

                        </div>


                        <div class="owner-invoice-amount">

                            {{
                                number_format(
                                    $invoice->total_amount,
                                    0,
                                    ',',
                                    '.'
                                )
                            }} đ

                        </div>

                    </div>

                @empty

                    <div class="owner-empty">
                        Chưa có hóa đơn đã thanh toán.
                    </div>

                @endforelse

            </div>

        </div>


        <div class="owner-panel">

            <div class="owner-panel-header">

                <h2 class="owner-panel-title">

                    <i class="bi bi-person-badge"></i>

                    Tài khoản gần đây

                </h2>

                <div class="owner-panel-subtitle">
                    Bước sau sẽ bổ sung
                    quản lý và phân quyền tài khoản.
                </div>

            </div>


            <div class="owner-table-wrapper">

                <table class="owner-table">

                    <thead>

                        <tr>
                            <th>Tài khoản</th>
                            <th>Vai trò</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse (
                            $recentUsers
                            as $user
                        )

                            <tr>

                                <td>

                                    <div class="owner-table-name">
                                        {{ $user->name }}
                                    </div>

                                    <div>
                                        {{ $user->email }}
                                    </div>

                                </td>

                                <td>

                                    <span class="owner-role-badge">

                                        {{
                                            $user
                                                ->role
                                                ?->code
                                            ?? 'CHƯA GÁN'
                                        }}

                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="2">

                                    Chưa có tài khoản.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </section>

</div>

@endsection