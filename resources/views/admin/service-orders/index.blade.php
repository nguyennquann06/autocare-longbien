@extends('layouts.app')


@section(
    'title',
    'Phân công kỹ thuật viên - AutoCare Long Biên'
)


@push('styles')

<style>
    .admin-assignment-page {
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

    .admin-assignment-hero {
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


    .admin-assignment-hero::after {
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


    .admin-assignment-hero-content {
        position: relative;

        z-index: 2;
    }


    .admin-assignment-chip {
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


    .admin-assignment-hero h1 {
        margin: 0;

        color: white !important;

        font-size:
            clamp(
                2rem,
                4vw,
                3.2rem
            );

        font-weight: 900;

        letter-spacing: -0.05em;
    }


    .admin-assignment-hero p {
        max-width: 800px;

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

    .admin-assignment-kpis {
        display: grid;

        grid-template-columns:
            repeat(
                5,
                minmax(
                    0,
                    1fr
                )
            );

        gap: 12px;

        margin-bottom: 20px;
    }


    .admin-assignment-kpi {
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


    .admin-assignment-kpi-label {
        color: #64748b;

        font-size: 9px;

        font-weight: 900;

        text-transform: uppercase;
    }


    .admin-assignment-kpi-value {
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

    .admin-assignment-panel {
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

    .admin-assignment-filter {
        padding: 20px;

        border-bottom:
            1px solid
            #e2e8f0;

        background: #f8fafc;
    }


    .admin-assignment-filter-form {
        display: grid;

        grid-template-columns:
            minmax(
                220px,
                1fr
            )
            230px
            auto
            auto;

        gap: 10px;

        align-items: end;
    }


    .admin-assignment-field label {
        display: block;

        margin-bottom: 6px;

        color: #475569;

        font-size: 9px;

        font-weight: 900;

        text-transform: uppercase;
    }


    .admin-assignment-control {
        width: 100%;

        min-height: 43px;

        padding:
            9px 12px;

        border:
            1px solid
            #cbd5e1;

        border-radius: 11px;

        color: #0f172a;

        background: white;

        font-size: 11px;

        outline: none;
    }


    .admin-assignment-control:focus {
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


    .admin-assignment-filter-button,
    .admin-assignment-reset {
        min-height: 43px;

        padding:
            9px 15px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 6px;

        border-radius: 11px;

        text-decoration: none;

        font-size: 10px;

        font-weight: 900;
    }


    .admin-assignment-filter-button {
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


    .admin-assignment-reset {
        border:
            1px solid
            #cbd5e1;

        color: #475569;

        background: white;
    }


    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    .admin-assignment-result {
        padding:
            17px 20px;

        border-bottom:
            1px solid
            #edf2f7;
    }


    .admin-assignment-result-title {
        color: #0f172a;

        font-size: 14px;

        font-weight: 900;
    }


    .admin-assignment-result-meta {
        margin-top: 3px;

        color: #64748b;

        font-size: 10px;
    }


    .admin-assignment-table-wrapper {
        overflow-x: auto;
    }


    .admin-assignment-table {
        width: 100%;

        border-collapse: collapse;
    }


    .admin-assignment-table th,
    .admin-assignment-table td {
        padding:
            14px 15px;

        border-bottom:
            1px solid
            #edf2f7;

        text-align: left;

        vertical-align: middle;
    }


    .admin-assignment-table th {
        color: #64748b;

        background: #f8fafc;

        font-size: 8px;

        font-weight: 900;

        text-transform: uppercase;

        white-space: nowrap;
    }


    .admin-assignment-table td {
        color: #475569;

        font-size: 10px;
    }


    .admin-order-code {
        color: #0f172a;

        font-weight: 900;
    }


    .admin-order-secondary {
        margin-top: 3px;

        color: #64748b;

        font-size: 9px;
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    .admin-order-status {
        display: inline-flex;

        padding:
            5px 8px;

        border-radius: 999px;

        font-size: 8px;

        font-weight: 900;

        white-space: nowrap;
    }


    .admin-order-status-received {
        color: #1d4ed8;

        background: #dbeafe;
    }


    .admin-order-status-progress {
        color: #b45309;

        background: #fef3c7;
    }


    .admin-order-status-completed {
        color: #047857;

        background: #d1fae5;
    }


    .admin-order-status-cancelled {
        color: #b91c1c;

        background: #fee2e2;
    }


    .admin-order-status-other {
        color: #475569;

        background: #e2e8f0;
    }


    /*
    |--------------------------------------------------------------------------
    | ASSIGNMENT
    |--------------------------------------------------------------------------
    */

    .admin-current-technician {
        min-width: 160px;
    }


    .admin-current-technician-name {
        color: #0f172a;

        font-weight: 900;
    }


    .admin-current-technician-email {
        margin-top: 3px;

        color: #64748b;

        font-size: 9px;
    }


    .admin-reassign-form {
        min-width: 310px;

        display: grid;

        grid-template-columns:
            minmax(
                190px,
                1fr
            )
            auto;

        gap: 7px;
    }


    .admin-reassign-select {
        min-height: 36px;

        padding:
            6px 8px;

        border:
            1px solid
            #cbd5e1;

        border-radius: 9px;

        color: #0f172a;

        background: white;

        font-size: 9px;
    }


    .admin-reassign-button {
        min-height: 36px;

        padding:
            7px 10px;

        border: none;

        border-radius: 9px;

        color: white;

        background:
            linear-gradient(
                135deg,
                #2563eb,
                #4f46e5
            );

        cursor: pointer;

        font-size: 9px;

        font-weight: 900;

        white-space: nowrap;
    }


    .admin-reassign-button:disabled {
        opacity: 0.6;

        cursor: not-allowed;
    }


    .admin-assignment-locked {
        display: inline-flex;

        align-items: center;

        gap: 5px;

        padding:
            6px 9px;

        border-radius: 9px;

        color: #64748b;

        background: #f1f5f9;

        font-size: 8px;

        font-weight: 850;

        white-space: nowrap;
    }


    .admin-progress-warning {
        margin-top: 5px;

        color: #b45309;

        font-size: 8px;

        line-height: 1.45;
    }


    /*
    |--------------------------------------------------------------------------
    | PROGRESS
    |--------------------------------------------------------------------------
    */

    .admin-order-progress {
        min-width: 115px;
    }


    .admin-order-progress-label {
        display: flex;

        justify-content: space-between;

        gap: 6px;

        margin-bottom: 5px;

        font-size: 8px;

        font-weight: 800;
    }


    .admin-order-progress-track {
        width: 100%;
        height: 6px;

        overflow: hidden;

        border-radius: 999px;

        background: #e2e8f0;
    }


    .admin-order-progress-bar {
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
    | DETAIL
    |--------------------------------------------------------------------------
    */

    .admin-order-detail-button {
        display: inline-flex;

        align-items: center;

        gap: 5px;

        padding:
            7px 9px;

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


    /*
    |--------------------------------------------------------------------------
    | EMPTY / PAGINATION
    |--------------------------------------------------------------------------
    */

    .admin-assignment-empty {
        padding:
            40px 20px !important;

        color:
            #64748b !important;

        text-align: center !important;
    }


    .admin-assignment-pagination {
        padding:
            18px 20px;
    }


    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 1199px) {
        .admin-assignment-kpis {
            grid-template-columns:
                repeat(
                    3,
                    1fr
                );
        }


        .admin-assignment-filter-form {
            grid-template-columns:
                1fr
                220px;
        }
    }


    @media (max-width: 767px) {
        .admin-assignment-kpis {
            grid-template-columns:
                repeat(
                    2,
                    1fr
                );
        }


        .admin-assignment-filter-form {
            grid-template-columns: 1fr;
        }
    }


    @media (max-width: 575px) {
        .admin-assignment-kpis {
            grid-template-columns: 1fr;
        }


        .admin-assignment-hero {
            padding: 24px;
        }
    }
</style>

@endpush


@section('content')

<div class="container admin-assignment-page">

    {{-- =====================================================
        HERO
    ====================================================== --}}

    <section class="admin-assignment-hero">

        <div class="admin-assignment-hero-content">

            <div class="admin-assignment-chip">

                <i class="bi bi-diagram-3-fill"></i>

                Technician Assignment

            </div>


            <h1>
                Phân công kỹ thuật viên
            </h1>


            <p>
                Chủ xưởng theo dõi toàn bộ phiếu bảo dưỡng
                và có thể điều chuyển kỹ thuật viên đối với
                các phiếu RECEIVED hoặc IN_PROGRESS.
                Việc điều chuyển không làm mất tiến độ
                kỹ thuật đã được ghi nhận.
            </p>

        </div>

    </section>


    {{-- =====================================================
        KPI
    ====================================================== --}}

    <section class="admin-assignment-kpis">

        <div class="admin-assignment-kpi">

            <div class="admin-assignment-kpi-label">
                Tổng phiếu
            </div>

            <div class="admin-assignment-kpi-value">
                {{ $totalOrders }}
            </div>

        </div>


        <div class="admin-assignment-kpi">

            <div class="admin-assignment-kpi-label">
                Phiếu hoạt động
            </div>

            <div class="admin-assignment-kpi-value">
                {{ $activeOrders }}
            </div>

        </div>


        <div class="admin-assignment-kpi">

            <div class="admin-assignment-kpi-label">
                Received
            </div>

            <div class="admin-assignment-kpi-value">

                {{
                    $statusCounts[
                        'RECEIVED'
                    ]
                    ?? 0
                }}

            </div>

        </div>


        <div class="admin-assignment-kpi">

            <div class="admin-assignment-kpi-label">
                In Progress
            </div>

            <div class="admin-assignment-kpi-value">

                {{
                    $statusCounts[
                        'IN_PROGRESS'
                    ]
                    ?? 0
                }}

            </div>

        </div>


        <div class="admin-assignment-kpi">

            <div class="admin-assignment-kpi-label">
                Kỹ thuật viên
            </div>

            <div class="admin-assignment-kpi-value">
                {{ $technicians->count() }}
            </div>

        </div>

    </section>


    {{-- =====================================================
        PANEL
    ====================================================== --}}

    <section class="admin-assignment-panel">

        {{-- FILTER --}}

        <div class="admin-assignment-filter">

            <form
                method="GET"
                action="{{ route('admin.service-orders.index') }}"
                class="admin-assignment-filter-form"
            >

                <div class="admin-assignment-field">

                    <label for="assignment-search">
                        Tìm phiếu
                    </label>


                    <input
                        id="assignment-search"
                        type="search"
                        name="q"
                        value="{{ $keyword }}"
                        class="admin-assignment-control"
                        placeholder="Mã phiếu, khách hàng, biển số, kỹ thuật viên..."
                        maxlength="120"
                    >

                </div>


                <div class="admin-assignment-field">

                    <label for="assignment-status">
                        Trạng thái
                    </label>


                    <select
                        id="assignment-status"
                        name="status"
                        class="admin-assignment-control"
                    >

                        <option
                            value="ALL"
                            @selected(
                                $statusFilter === 'ALL'
                            )
                        >
                            Tất cả
                        </option>

                        <option
                            value="RECEIVED"
                            @selected(
                                $statusFilter === 'RECEIVED'
                            )
                        >
                            RECEIVED
                        </option>

                        <option
                            value="IN_PROGRESS"
                            @selected(
                                $statusFilter === 'IN_PROGRESS'
                            )
                        >
                            IN_PROGRESS
                        </option>

                        <option
                            value="COMPLETED"
                            @selected(
                                $statusFilter === 'COMPLETED'
                            )
                        >
                            COMPLETED
                        </option>

                        <option
                            value="CANCELLED"
                            @selected(
                                $statusFilter === 'CANCELLED'
                            )
                        >
                            CANCELLED
                        </option>

                    </select>

                </div>


                <button
                    type="submit"
                    class="admin-assignment-filter-button"
                >

                    <i class="bi bi-search"></i>

                    Lọc

                </button>


                <a
                    href="{{ route('admin.service-orders.index') }}"
                    class="admin-assignment-reset"
                >

                    <i class="bi bi-arrow-counterclockwise"></i>

                    Đặt lại

                </a>

            </form>

        </div>


        <div class="admin-assignment-result">

            <div class="admin-assignment-result-title">
                Danh sách phiếu bảo dưỡng
            </div>


            <div class="admin-assignment-result-meta">

                Hiển thị

                <strong>
                    {{ $serviceOrders->count() }}
                </strong>

                /

                <strong>
                    {{ $serviceOrders->total() }}
                </strong>

                phiếu.

            </div>

        </div>


        {{-- TABLE --}}

        <div class="admin-assignment-table-wrapper">

            <table class="admin-assignment-table">

                <thead>

                    <tr>
                        <th>Phiếu</th>
                        <th>Khách hàng / Xe</th>
                        <th>Trạng thái</th>
                        <th>Tiến độ</th>
                        <th>Kỹ thuật viên hiện tại</th>
                        <th>Phân công</th>
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
                                        'admin-order-status-received',

                                    'IN_PROGRESS' =>
                                        'admin-order-status-progress',

                                    'COMPLETED' =>
                                        'admin-order-status-completed',

                                    'CANCELLED' =>
                                        'admin-order-status-cancelled',

                                    default =>
                                        'admin-order-status-other',
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


                            $canReassign =
                                in_array(
                                    $order->status,
                                    [
                                        'RECEIVED',
                                        'IN_PROGRESS',
                                    ],
                                    true
                                );
                        @endphp


                        <tr>

                            {{-- ORDER --}}

                            <td>

                                <div class="admin-order-code">
                                    {{ $order->order_code }}
                                </div>


                                <div class="admin-order-secondary">

                                    Người tạo:

                                    {{
                                        $order
                                            ->creator
                                            ?->name
                                        ?? '—'
                                    }}

                                </div>

                            </td>


                            {{-- CUSTOMER / VEHICLE --}}

                            <td>

                                <div class="admin-order-code">

                                    {{
                                        $order
                                            ->customer
                                            ?->full_name
                                        ?? '—'
                                    }}

                                </div>


                                <div class="admin-order-secondary">

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

                                    ·

                                    {{
                                        $order
                                            ->vehicle
                                            ?->license_plate
                                        ?? '—'
                                    }}

                                </div>

                            </td>


                            {{-- STATUS --}}

                            <td>

                                <span
                                    class="
                                        admin-order-status
                                        {{ $statusClass }}
                                    "
                                >
                                    {{ $order->status }}
                                </span>

                            </td>


                            {{-- PROGRESS --}}

                            <td>

                                <div class="admin-order-progress">

                                    <div class="admin-order-progress-label">

                                        <span>
                                            {{ $completedItems }}
                                            /
                                            {{ $totalItems }}
                                        </span>

                                        <span>
                                            {{ $progressPercent }}%
                                        </span>

                                    </div>


                                    <div class="admin-order-progress-track">

                                        <div
                                            class="admin-order-progress-bar"
                                            style="width: {{ $progressPercent }}%;"
                                        ></div>

                                    </div>

                                </div>

                            </td>


                            {{-- CURRENT TECHNICIAN --}}

                            <td>

                                <div class="admin-current-technician">

                                    <div class="admin-current-technician-name">

                                        {{
                                            $order
                                                ->technician
                                                ?->name
                                            ?? 'Chưa phân công'
                                        }}

                                    </div>


                                    @if (
                                        $order
                                            ->technician
                                    )

                                        <div class="admin-current-technician-email">

                                            {{
                                                $order
                                                    ->technician
                                                    ->email
                                            }}

                                        </div>

                                    @endif

                                </div>

                            </td>


                            {{-- REASSIGN --}}

                            <td>

                                @if ($canReassign)

                                    @if (
                                        $technicians
                                            ->isNotEmpty()
                                    )

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.service-orders.technician.update',
                                                $order->id
                                            ) }}"
                                            class="admin-reassign-form"
                                            data-reassign-form
                                        >

                                            @csrf
                                            @method('PATCH')


                                            <select
                                                name="technician_id"
                                                class="admin-reassign-select"
                                                required
                                            >

                                                @foreach (
                                                    $technicians
                                                    as $technician
                                                )

                                                    <option
                                                        value="{{ $technician->id }}"
                                                        @selected(
                                                            (int)
                                                            $order->technician_id
                                                            ===
                                                            (int)
                                                            $technician->id
                                                        )
                                                    >

                                                        {{ $technician->name }}

                                                        · {{ $technician->active_orders_count }} phiếu hoạt động

                                                    </option>

                                                @endforeach

                                            </select>


                                            <button
                                                type="submit"
                                                class="admin-reassign-button"
                                                data-reassign-submit
                                            >

                                                <i class="bi bi-arrow-left-right"></i>

                                                Cập nhật

                                            </button>

                                        </form>


                                        @if (
                                            $order->status
                                            === 'IN_PROGRESS'
                                        )

                                            <div class="admin-progress-warning">

                                                Phiếu đang thực hiện:
                                                đổi người phụ trách nhưng
                                                giữ nguyên toàn bộ tiến độ.

                                            </div>

                                        @endif

                                    @else

                                        <span class="admin-assignment-locked">

                                            <i class="bi bi-exclamation-triangle"></i>

                                            Chưa có TECHNICIAN

                                        </span>

                                    @endif

                                @else

                                    <span class="admin-assignment-locked">

                                        <i class="bi bi-lock-fill"></i>

                                        Đã khóa phân công

                                    </span>

                                @endif

                            </td>


                            {{-- DETAIL --}}

                            <td>

                                <a
                                    href="{{ route(
                                        'staff.service-orders.show',
                                        $order->id
                                    ) }}"
                                    class="admin-order-detail-button"
                                >

                                    <i class="bi bi-eye"></i>

                                    Xem phiếu

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="admin-assignment-empty"
                            >
                                Không tìm thấy phiếu bảo dưỡng phù hợp.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if (
            $serviceOrders
                ->hasPages()
        )

            <div class="admin-assignment-pagination">

                {{ $serviceOrders->links() }}

            </div>

        @endif

    </section>

</div>

@endsection


@push('scripts')

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function () {
            const forms =
                document.querySelectorAll(
                    '[data-reassign-form]'
                );


            forms.forEach(
                function (form) {
                    form.addEventListener(
                        'submit',
                        function () {
                            const button =
                                form.querySelector(
                                    '[data-reassign-submit]'
                                );


                            if (!button) {
                                return;
                            }


                            button.disabled =
                                true;


                            button.innerHTML =
                                '<span class="spinner-border spinner-border-sm me-1"></span>Đang cập nhật';
                        }
                    );
                }
            );
        }
    );
</script>

@endpush