@extends('layouts.app')


@section(
    'title',
    'Nhật ký hoạt động - AutoCare Long Biên'
)


@push('styles')

<style>
    .activity-log-page {
        width: 100%;
        max-width: 1450px;

        margin: 0 auto;

        padding-bottom: 60px;
    }


    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .activity-log-hero {
        position: relative;

        overflow: hidden;

        margin-bottom: 22px;

        padding: 32px;

        border-radius: 25px;

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


    .activity-log-hero::after {
        content: "";

        position: absolute;

        width: 350px;
        height: 350px;

        top: -220px;
        right: -80px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(
                    34,
                    211,
                    238,
                    0.30
                ),
                transparent 68%
            );
    }


    .activity-log-hero-content {
        position: relative;

        z-index: 2;
    }


    .activity-log-chip {
        display: inline-flex;

        align-items: center;

        gap: 7px;

        margin-bottom: 13px;

        padding:
            7px 11px;

        border:
            1px solid
            rgba(
                255,
                255,
                255,
                0.16
            );

        border-radius: 999px;

        color: #dbeafe;

        background:
            rgba(
                255,
                255,
                255,
                0.08
            );

        font-size: 9px;

        font-weight: 900;

        text-transform: uppercase;
    }


    .activity-log-hero h1 {
        margin: 0;

        color: white !important;

        font-size:
            clamp(
                2rem,
                4vw,
                3.2rem
            );

        font-weight: 900;

        letter-spacing:
            -0.05em;
    }


    .activity-log-hero p {
        max-width: 820px;

        margin:
            10px 0 0;

        color: #cbd5e1;

        font-size: 13px;

        line-height: 1.75;
    }


    /*
    |--------------------------------------------------------------------------
    | KPI
    |--------------------------------------------------------------------------
    */

    .activity-log-kpis {
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


    .activity-log-kpi {
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


    .activity-log-kpi-label {
        color: #64748b;

        font-size: 9px;

        font-weight: 900;

        text-transform: uppercase;
    }


    .activity-log-kpi-value {
        margin-top: 4px;

        color: #0f172a;

        font-size: 24px;

        font-weight: 900;
    }


    /*
    |--------------------------------------------------------------------------
    | PANEL
    |--------------------------------------------------------------------------
    */

    .activity-log-panel {
        overflow: hidden;

        border:
            1px solid
            rgba(
                255,
                255,
                255,
                0.9
            );

        border-radius: 21px;

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


    /*
    |--------------------------------------------------------------------------
    | FILTER
    |--------------------------------------------------------------------------
    */

    .activity-log-filter {
        padding: 20px;

        border-bottom:
            1px solid
            #e2e8f0;

        background: #f8fafc;
    }


    .activity-log-filter-form {
        display: grid;

        grid-template-columns:
            minmax(
                220px,
                1.3fr
            )
            160px
            minmax(
                200px,
                1fr
            )
            150px
            150px
            auto
            auto;

        gap: 10px;

        align-items: end;
    }


    .activity-log-field label {
        display: block;

        margin-bottom: 6px;

        color: #475569;

        font-size: 8px;

        font-weight: 900;

        text-transform: uppercase;
    }


    .activity-log-control {
        width: 100%;

        min-height: 42px;

        padding:
            8px 10px;

        border:
            1px solid
            #cbd5e1;

        border-radius: 10px;

        color: #0f172a;

        background: white;

        font-size: 10px;

        outline: none;
    }


    .activity-log-control:focus {
        border-color: #6366f1;

        box-shadow:
            0 0 0 3px
            rgba(
                99,
                102,
                241,
                0.10
            );
    }


    .activity-log-filter-button,
    .activity-log-reset {
        min-height: 42px;

        padding:
            8px 13px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 6px;

        border-radius: 10px;

        text-decoration: none;

        font-size: 9px;

        font-weight: 900;
    }


    .activity-log-filter-button {
        border: none;

        color: white;

        background:
            linear-gradient(
                135deg,
                #2563eb,
                #4f46e5
            );

        cursor: pointer;
    }


    .activity-log-reset {
        border:
            1px solid
            #cbd5e1;

        color: #475569;

        background: white;
    }


    /*
    |--------------------------------------------------------------------------
    | RESULT
    |--------------------------------------------------------------------------
    */

    .activity-log-result {
        padding:
            17px 20px;

        border-bottom:
            1px solid
            #edf2f7;
    }


    .activity-log-result-title {
        color: #0f172a;

        font-size: 14px;

        font-weight: 900;
    }


    .activity-log-result-meta {
        margin-top: 3px;

        color: #64748b;

        font-size: 10px;
    }


    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    .activity-log-table-wrapper {
        overflow-x: auto;
    }


    .activity-log-table {
        width: 100%;

        border-collapse: collapse;
    }


    .activity-log-table th,
    .activity-log-table td {
        padding:
            14px 15px;

        border-bottom:
            1px solid
            #edf2f7;

        text-align: left;

        vertical-align: middle;
    }


    .activity-log-table th {
        color: #64748b;

        background: #f8fafc;

        font-size: 8px;

        font-weight: 900;

        text-transform: uppercase;

        white-space: nowrap;
    }


    .activity-log-table td {
        color: #475569;

        font-size: 10px;
    }


    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */

    .activity-log-user {
        min-width: 175px;
    }


    .activity-log-user-name {
        color: #0f172a;

        font-weight: 900;
    }


    .activity-log-user-email {
        margin-top: 3px;

        color: #64748b;

        font-size: 9px;
    }


    /*
    |--------------------------------------------------------------------------
    | ROLE
    |--------------------------------------------------------------------------
    */

    .activity-log-role {
        display: inline-flex;

        align-items: center;

        gap: 5px;

        padding:
            5px 8px;

        border-radius: 999px;

        font-size: 8px;

        font-weight: 900;

        white-space: nowrap;
    }


    .activity-log-role-admin {
        color: #6d28d9;

        background: #ede9fe;
    }


    .activity-log-role-staff {
        color: #047857;

        background: #d1fae5;
    }


    .activity-log-role-technician {
        color: #b45309;

        background: #fef3c7;
    }


    .activity-log-role-customer {
        color: #0369a1;

        background: #e0f2fe;
    }


    .activity-log-role-system {
        color: #475569;

        background: #e2e8f0;
    }


    /*
    |--------------------------------------------------------------------------
    | ACTION
    |--------------------------------------------------------------------------
    */

    .activity-log-action {
        display: inline-flex;

        align-items: center;

        gap: 6px;

        min-width: 135px;

        padding:
            7px 9px;

        border-radius: 9px;

        color: #1e40af;

        background: #eff6ff;

        font-size: 9px;

        font-weight: 900;

        line-height: 1.35;
    }


    /*
    |--------------------------------------------------------------------------
    | ENTITY
    |--------------------------------------------------------------------------
    */

    .activity-log-entity {
        min-width: 120px;
    }


    .activity-log-entity-type {
        color: #0f172a;

        font-weight: 900;
    }


    .activity-log-entity-id {
        margin-top: 3px;

        color: #64748b;

        font-size: 9px;
    }


    /*
    |--------------------------------------------------------------------------
    | DESCRIPTION
    |--------------------------------------------------------------------------
    */

    .activity-log-description {
        min-width: 290px;

        color: #334155;

        line-height: 1.55;
    }


    /*
    |--------------------------------------------------------------------------
    | TIME
    |--------------------------------------------------------------------------
    */

    .activity-log-time {
        min-width: 125px;

        white-space: nowrap;
    }


    .activity-log-date {
        color: #0f172a;

        font-weight: 900;
    }


    .activity-log-clock {
        margin-top: 3px;

        color: #64748b;

        font-size: 9px;
    }


    /*
    |--------------------------------------------------------------------------
    | IP
    |--------------------------------------------------------------------------
    */

    .activity-log-ip {
        color: #64748b;

        white-space: nowrap;

        font-size: 9px;
    }


    /*
    |--------------------------------------------------------------------------
    | EMPTY
    |--------------------------------------------------------------------------
    */

    .activity-log-empty {
        padding:
            45px 20px !important;

        color:
            #64748b !important;

        text-align: center !important;
    }


    .activity-log-pagination {
        padding:
            18px 20px;
    }


    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 1250px) {
        .activity-log-filter-form {
            grid-template-columns:
                repeat(
                    3,
                    1fr
                );
        }
    }


    @media (max-width: 900px) {
        .activity-log-kpis {
            grid-template-columns:
                repeat(
                    2,
                    1fr
                );
        }


        .activity-log-filter-form {
            grid-template-columns:
                repeat(
                    2,
                    1fr
                );
        }
    }


    @media (max-width: 575px) {
        .activity-log-kpis,
        .activity-log-filter-form {
            grid-template-columns: 1fr;
        }


        .activity-log-hero {
            padding: 24px;
        }
    }
</style>

@endpush


@section('content')

<div class="container activity-log-page">

    {{-- =====================================================
        HERO
    ====================================================== --}}

    <section class="activity-log-hero">

        <div class="activity-log-hero-content">

            <div class="activity-log-chip">

                <i class="bi bi-clock-history"></i>

                Lịch sử vận hành

            </div>


            <h1>
                Nhật ký hoạt động
            </h1>


            <p>
                Theo dõi các hoạt động quan trọng trong garage:
                ai thực hiện, thực hiện việc gì, trên đối tượng nào
                và vào thời điểm nào.
            </p>

        </div>

    </section>


    {{-- =====================================================
        KPI
    ====================================================== --}}

    <section class="activity-log-kpis">

        <div class="activity-log-kpi">

            <div class="activity-log-kpi-label">
                Tổng hoạt động
            </div>

            <div class="activity-log-kpi-value">
                {{ $totalLogs }}
            </div>

        </div>


        <div class="activity-log-kpi">

            <div class="activity-log-kpi-label">
                Chủ xưởng
            </div>

            <div class="activity-log-kpi-value">
                {{ $adminLogs }}
            </div>

        </div>


        <div class="activity-log-kpi">

            <div class="activity-log-kpi-label">
                Nhân viên
            </div>

            <div class="activity-log-kpi-value">
                {{ $staffLogs }}
            </div>

        </div>


        <div class="activity-log-kpi">

            <div class="activity-log-kpi-label">
                Kỹ thuật viên
            </div>

            <div class="activity-log-kpi-value">
                {{ $technicianLogs }}
            </div>

        </div>

    </section>


    {{-- =====================================================
        PANEL
    ====================================================== --}}

    <section class="activity-log-panel">

        {{-- FILTER --}}

        <div class="activity-log-filter">

            <form
                method="GET"
                action="{{ route('admin.activity-logs.index') }}"
                class="activity-log-filter-form"
            >

                <div class="activity-log-field">

                    <label for="activity-q">
                        Tìm kiếm
                    </label>

                    <input
                        id="activity-q"
                        type="search"
                        name="q"
                        value="{{ $keyword }}"
                        class="activity-log-control"
                        placeholder="Tên nhân viên, nội dung hoạt động..."
                        maxlength="120"
                    >

                </div>


                <div class="activity-log-field">

                    <label for="activity-role">
                        Vai trò
                    </label>

                    <select
                        id="activity-role"
                        name="role"
                        class="activity-log-control"
                    >

                        <option value="">
                            Tất cả
                        </option>

                        <option
                            value="ADMIN"
                            @selected(
                                $roleFilter === 'ADMIN'
                            )
                        >
                            Chủ xưởng
                        </option>

                        <option
                            value="STAFF"
                            @selected(
                                $roleFilter === 'STAFF'
                            )
                        >
                            Nhân viên
                        </option>

                        <option
                            value="TECHNICIAN"
                            @selected(
                                $roleFilter === 'TECHNICIAN'
                            )
                        >
                            Kỹ thuật viên
                        </option>

                        <option
                            value="CUSTOMER"
                            @selected(
                                $roleFilter === 'CUSTOMER'
                            )
                        >
                            Khách hàng
                        </option>

                    </select>

                </div>


                <div class="activity-log-field">

                    <label for="activity-action">
                        Loại hoạt động
                    </label>

                    <select
                        id="activity-action"
                        name="action"
                        class="activity-log-control"
                    >

                        <option value="">
                            Tất cả hoạt động
                        </option>


                        @foreach (
                            $actions
                            as $action
                        )

                            @php
                                $actionLabel =
                                    match ($action) {
                                        'USER_CREATED' =>
                                            'Tạo tài khoản nhân sự',

                                        'USER_ROLE_CHANGED' =>
                                            'Thay đổi vai trò nhân sự',

                                        'TECHNICIAN_REASSIGNED' =>
                                            'Phân công lại kỹ thuật viên',

                                        'APPOINTMENT_STATUS_CHANGED' =>
                                            'Cập nhật lịch hẹn',

                                        'SERVICE_ORDER_CREATED' =>
                                            'Tạo phiếu bảo dưỡng',

                                        'PART_STOCK_OUT' =>
                                            'Xuất phụ tùng',

                                        'PART_STOCK_IN' =>
                                            'Nhập phụ tùng',

                                        'INVOICE_CREATED' =>
                                            'Lập hóa đơn',

                                        'INVOICE_PAID' =>
                                            'Xác nhận thanh toán',

                                        'SERVICE_ORDER_STARTED' =>
                                            'Bắt đầu bảo dưỡng',

                                        'SERVICE_ITEM_STATUS_CHANGED' =>
                                            'Cập nhật hạng mục',

                                        'SERVICE_ORDER_COMPLETED' =>
                                            'Hoàn thành bảo dưỡng',

                                        default =>
                                            $action,
                                    };
                            @endphp


                            <option
                                value="{{ $action }}"
                                @selected(
                                    $actionFilter
                                    ===
                                    $action
                                )
                            >
                                {{ $actionLabel }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="activity-log-field">

                    <label for="activity-date-from">
                        Từ ngày
                    </label>

                    <input
                        id="activity-date-from"
                        type="date"
                        name="date_from"
                        value="{{ $dateFrom }}"
                        class="activity-log-control"
                    >

                </div>


                <div class="activity-log-field">

                    <label for="activity-date-to">
                        Đến ngày
                    </label>

                    <input
                        id="activity-date-to"
                        type="date"
                        name="date_to"
                        value="{{ $dateTo }}"
                        class="activity-log-control"
                    >

                </div>


                <button
                    type="submit"
                    class="activity-log-filter-button"
                >

                    <i class="bi bi-search"></i>

                    Lọc

                </button>


                <a
                    href="{{ route('admin.activity-logs.index') }}"
                    class="activity-log-reset"
                >

                    <i class="bi bi-arrow-counterclockwise"></i>

                    Đặt lại

                </a>

            </form>

        </div>


        {{-- RESULT --}}

        <div class="activity-log-result">

            <div class="activity-log-result-title">
                Lịch sử hoạt động
            </div>


            <div class="activity-log-result-meta">

                Hiển thị

                <strong>
                    {{ $logs->count() }}
                </strong>

                /

                <strong>
                    {{ $logs->total() }}
                </strong>

                hoạt động.

            </div>

        </div>


        {{-- TABLE --}}

        <div class="activity-log-table-wrapper">

            <table class="activity-log-table">

                <thead>

                    <tr>
                        <th>Thời gian</th>
                        <th>Người thực hiện</th>
                        <th>Vai trò</th>
                        <th>Hoạt động</th>
                        <th>Đối tượng</th>
                        <th>Nội dung</th>
                        <th>Địa chỉ IP</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse (
                        $logs
                        as $log
                    )

                        @php
                            $roleClass =
                                match (
                                    $log->role_code
                                ) {
                                    'ADMIN' =>
                                        'activity-log-role-admin',

                                    'STAFF' =>
                                        'activity-log-role-staff',

                                    'TECHNICIAN' =>
                                        'activity-log-role-technician',

                                    'CUSTOMER' =>
                                        'activity-log-role-customer',

                                    default =>
                                        'activity-log-role-system',
                                };


                            $roleLabel =
                                match (
                                    $log->role_code
                                ) {
                                    'ADMIN' =>
                                        'Chủ xưởng',

                                    'STAFF' =>
                                        'Nhân viên',

                                    'TECHNICIAN' =>
                                        'Kỹ thuật viên',

                                    'CUSTOMER' =>
                                        'Khách hàng',

                                    default =>
                                        'Hệ thống',
                                };


                            $actionLabel =
                                match (
                                    $log->action
                                ) {
                                    'USER_CREATED' =>
                                        'Tạo tài khoản nhân sự',

                                    'USER_ROLE_CHANGED' =>
                                        'Thay đổi vai trò nhân sự',

                                    'TECHNICIAN_REASSIGNED' =>
                                        'Phân công lại kỹ thuật viên',

                                    'APPOINTMENT_STATUS_CHANGED' =>
                                        'Cập nhật lịch hẹn',

                                    'SERVICE_ORDER_CREATED' =>
                                        'Tạo phiếu bảo dưỡng',

                                    'PART_STOCK_OUT' =>
                                        'Xuất phụ tùng',

                                    'PART_STOCK_IN' =>
                                        'Nhập phụ tùng',

                                    'INVOICE_CREATED' =>
                                        'Lập hóa đơn',

                                    'INVOICE_PAID' =>
                                        'Xác nhận thanh toán',

                                    'SERVICE_ORDER_STARTED' =>
                                        'Bắt đầu bảo dưỡng',

                                    'SERVICE_ITEM_STATUS_CHANGED' =>
                                        'Cập nhật hạng mục',

                                    'SERVICE_ORDER_COMPLETED' =>
                                        'Hoàn thành bảo dưỡng',

                                    default =>
                                        $log->action,
                                };


                            $actionIcon =
                                match (
                                    $log->action
                                ) {
                                    'USER_CREATED' =>
                                        'bi-person-plus',

                                    'USER_ROLE_CHANGED' =>
                                        'bi-person-gear',

                                    'TECHNICIAN_REASSIGNED' =>
                                        'bi-arrow-left-right',

                                    'APPOINTMENT_STATUS_CHANGED' =>
                                        'bi-calendar-check',

                                    'SERVICE_ORDER_CREATED' =>
                                        'bi-clipboard-plus',

                                    'PART_STOCK_OUT' =>
                                        'bi-box-arrow-up-right',

                                    'PART_STOCK_IN' =>
                                        'bi-box-arrow-in-down',

                                    'INVOICE_CREATED' =>
                                        'bi-receipt',

                                    'INVOICE_PAID' =>
                                        'bi-cash-coin',

                                    'SERVICE_ORDER_STARTED' =>
                                        'bi-play-circle',

                                    'SERVICE_ITEM_STATUS_CHANGED' =>
                                        'bi-list-check',

                                    'SERVICE_ORDER_COMPLETED' =>
                                        'bi-check-circle',

                                    default =>
                                        'bi-activity',
                                };


                            $entityLabel =
                                match (
                                    $log->entity_type
                                ) {
                                    'User' =>
                                        'Tài khoản',

                                    'Appointment' =>
                                        'Lịch hẹn',

                                    'ServiceOrder' =>
                                        'Phiếu bảo dưỡng',

                                    'ServiceOrderItem' =>
                                        'Hạng mục bảo dưỡng',

                                    'InventoryTransaction' =>
                                        'Giao dịch kho',

                                    'Part' =>
                                        'Phụ tùng',

                                    'Invoice' =>
                                        'Hóa đơn',

                                    default =>
                                        $log->entity_type
                                        ?? '—',
                                };
                        @endphp


                        <tr>

                            {{-- TIME --}}

                            <td>

                                <div class="activity-log-time">

                                    <div class="activity-log-date">

                                        {{
                                            $log
                                                ->created_at
                                                ?->format(
                                                    'd/m/Y'
                                                )
                                            ?? '—'
                                        }}

                                    </div>


                                    <div class="activity-log-clock">

                                        <i class="bi bi-clock"></i>

                                        {{
                                            $log
                                                ->created_at
                                                ?->format(
                                                    'H:i:s'
                                                )
                                            ?? '—'
                                        }}

                                    </div>

                                </div>

                            </td>


                            {{-- USER --}}

                            <td>

                                <div class="activity-log-user">

                                    <div class="activity-log-user-name">

                                        {{
                                            $log
                                                ->user
                                                ?->name
                                            ?? 'Hệ thống'
                                        }}

                                    </div>


                                    @if (
                                        $log->user
                                    )

                                        <div class="activity-log-user-email">

                                            {{
                                                $log
                                                    ->user
                                                    ->email
                                            }}

                                        </div>

                                    @endif

                                </div>

                            </td>


                            {{-- ROLE --}}

                            <td>

                                <span
                                    class="
                                        activity-log-role
                                        {{ $roleClass }}
                                    "
                                >

                                    @if (
                                        $log->role_code
                                        === 'ADMIN'
                                    )

                                        <i class="bi bi-shield-fill-check"></i>

                                    @elseif (
                                        $log->role_code
                                        === 'STAFF'
                                    )

                                        <i class="bi bi-person-workspace"></i>

                                    @elseif (
                                        $log->role_code
                                        === 'TECHNICIAN'
                                    )

                                        <i class="bi bi-tools"></i>

                                    @elseif (
                                        $log->role_code
                                        === 'CUSTOMER'
                                    )

                                        <i class="bi bi-person"></i>

                                    @else

                                        <i class="bi bi-cpu"></i>

                                    @endif


                                    {{ $roleLabel }}

                                </span>

                            </td>


                            {{-- ACTION --}}

                            <td>

                                <span class="activity-log-action">

                                    <i class="bi {{ $actionIcon }}"></i>

                                    {{ $actionLabel }}

                                </span>

                            </td>


                            {{-- ENTITY --}}

                            <td>

                                <div class="activity-log-entity">

                                    <div class="activity-log-entity-type">
                                        {{ $entityLabel }}
                                    </div>


                                    @if (
                                        $log->entity_id
                                    )

                                        <div class="activity-log-entity-id">
                                            Mã #{{ $log->entity_id }}
                                        </div>

                                    @endif

                                </div>

                            </td>


                            {{-- DESCRIPTION --}}

                            <td>

                                <div class="activity-log-description">
                                    {{ $log->description }}
                                </div>

                            </td>


                            {{-- IP --}}

                            <td>

                                <span class="activity-log-ip">
                                    {{ $log->ip_address ?: '—' }}
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="activity-log-empty"
                            >

                                <i class="bi bi-clock-history fs-3"></i>

                                <br><br>

                                Chưa có hoạt động nào được ghi nhận.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if (
            $logs
                ->hasPages()
        )

            <div class="activity-log-pagination">

                {{ $logs->links() }}

            </div>

        @endif

    </section>

</div>

@endsection