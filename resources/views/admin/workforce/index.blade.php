@extends('layouts.app')


@section(
    'title',
    'Giám sát nhân sự - AutoCare Long Biên'
)


@push('styles')

<style>
    .owner-workforce-page {
        width: 100%;
        max-width: 1420px;

        margin: 0 auto;

        padding-bottom: 50px;
    }


    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .owner-workforce-hero {
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


    .owner-workforce-hero::after {
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


    .owner-workforce-hero-content {
        position: relative;

        z-index: 2;
    }


    .owner-workforce-chip {
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


    .owner-workforce-hero h1 {
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


    .owner-workforce-hero p {
        max-width: 760px;

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

    .owner-workforce-kpis {
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


    .owner-workforce-kpi {
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
                0.95
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


    .owner-workforce-kpi-icon {
        width: 36px;
        height: 36px;

        display: flex;

        align-items: center;

        justify-content: center;

        margin-bottom: 10px;

        border-radius: 11px;

        color: #2563eb;

        background: #eff6ff;
    }


    .owner-workforce-kpi-label {
        color: #64748b;

        font-size: 9px;

        font-weight: 900;

        text-transform: uppercase;
    }


    .owner-workforce-kpi-value {
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

    .owner-workforce-panel {
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

    .owner-workforce-filter {
        padding: 20px;

        border-bottom:
            1px solid
            #e2e8f0;

        background: #f8fafc;
    }


    .owner-workforce-filter-form {
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


    .owner-filter-group label {
        display: block;

        margin-bottom: 6px;

        color: #475569;

        font-size: 9px;

        font-weight: 900;

        text-transform: uppercase;
    }


    .owner-filter-control {
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

        font-size: 12px;

        outline: none;
    }


    .owner-filter-control:focus {
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


    .owner-filter-submit,
    .owner-filter-reset {
        min-height: 43px;

        padding:
            9px 15px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 6px;

        border-radius: 11px;

        text-decoration: none;

        font-size: 11px;

        font-weight: 900;
    }


    .owner-filter-submit {
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


    .owner-filter-reset {
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

    .owner-workforce-result {
        padding:
            17px 20px;

        border-bottom:
            1px solid
            #edf2f7;
    }


    .owner-workforce-result-title {
        color: #0f172a;

        font-size: 14px;

        font-weight: 900;
    }


    .owner-workforce-result-meta {
        margin-top: 3px;

        color: #64748b;

        font-size: 10px;
    }


    .owner-workforce-table-wrapper {
        overflow-x: auto;
    }


    .owner-workforce-table {
        width: 100%;

        border-collapse: collapse;
    }


    .owner-workforce-table th,
    .owner-workforce-table td {
        padding:
            14px 16px;

        border-bottom:
            1px solid
            #edf2f7;

        text-align: left;

        vertical-align: middle;
    }


    .owner-workforce-table th {
        color: #64748b;

        background: #f8fafc;

        font-size: 9px;

        font-weight: 900;

        text-transform: uppercase;
    }


    .owner-workforce-table td {
        color: #475569;

        font-size: 11px;
    }


    .owner-person {
        min-width: 220px;

        display: flex;

        align-items: center;

        gap: 10px;
    }


    .owner-person-avatar {
        width: 40px;
        height: 40px;

        flex: 0 0 auto;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 50%;

        color: #4338ca;

        background:
            linear-gradient(
                135deg,
                #eef2ff,
                #dbeafe
            );

        font-size: 12px;

        font-weight: 900;
    }


    .owner-person-name {
        color: #0f172a;

        font-size: 11px;

        font-weight: 900;
    }


    .owner-person-email {
        margin-top: 3px;

        color: #64748b;

        font-size: 10px;
    }


    .owner-role-badge {
        display: inline-flex;

        align-items: center;

        gap: 5px;

        padding:
            5px 9px;

        border-radius: 999px;

        font-size: 8px;

        font-weight: 900;
    }


    .owner-role-staff {
        color: #047857;

        background: #d1fae5;
    }


    .owner-role-technician {
        color: #b45309;

        background: #fef3c7;
    }


    .owner-active-number {
        color: #dc2626;

        font-weight: 900;
    }


    .owner-zero-number {
        color: #94a3b8;
    }


    .owner-view-button {
        min-height: 34px;

        padding:
            7px 10px;

        display: inline-flex;

        align-items: center;

        gap: 6px;

        border:
            1px solid
            #bfdbfe;

        border-radius: 9px;

        color: #1d4ed8;

        background: #eff6ff;

        text-decoration: none;

        font-size: 9px;

        font-weight: 900;
    }


    .owner-workforce-empty {
        padding:
            42px 20px !important;

        color:
            #64748b !important;

        text-align: center !important;
    }


    .owner-workforce-pagination {
        padding:
            18px 20px;
    }


    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 1199px) {
        .owner-workforce-kpis {
            grid-template-columns:
                repeat(
                    3,
                    1fr
                );
        }


        .owner-workforce-filter-form {
            grid-template-columns:
                1fr
                220px;
        }
    }


    @media (max-width: 767px) {
        .owner-workforce-kpis {
            grid-template-columns:
                repeat(
                    2,
                    1fr
                );
        }


        .owner-workforce-filter-form {
            grid-template-columns: 1fr;
        }
    }


    @media (max-width: 575px) {
        .owner-workforce-kpis {
            grid-template-columns: 1fr;
        }


        .owner-workforce-hero {
            padding: 24px;
        }
    }
</style>

@endpush


@section('content')

<div class="container owner-workforce-page">

    <section class="owner-workforce-hero">

        <div class="owner-workforce-hero-content">

            <div class="owner-workforce-chip">

                <i class="bi bi-person-badge-fill"></i>

                Workforce Monitoring

            </div>


            <h1>
                Giám sát nhân sự
            </h1>

        </div>

    </section>


    <section class="owner-workforce-kpis">

        <div class="owner-workforce-kpi">

            <div class="owner-workforce-kpi-icon">
                <i class="bi bi-person-workspace"></i>
            </div>

            <div class="owner-workforce-kpi-label">
                Staff
            </div>

            <div class="owner-workforce-kpi-value">
                {{ $staffCount }}
            </div>

        </div>


        <div class="owner-workforce-kpi">

            <div class="owner-workforce-kpi-icon">
                <i class="bi bi-tools"></i>
            </div>

            <div class="owner-workforce-kpi-label">
                Technician
            </div>

            <div class="owner-workforce-kpi-value">
                {{ $technicianCount }}
            </div>

        </div>


        <div class="owner-workforce-kpi">

            <div class="owner-workforce-kpi-icon">
                <i class="bi bi-inbox-fill"></i>
            </div>

            <div class="owner-workforce-kpi-label">
                Đã tiếp nhận
            </div>

            <div class="owner-workforce-kpi-value">
                {{ $receivedOrders }}
            </div>

        </div>


        <div class="owner-workforce-kpi">

            <div class="owner-workforce-kpi-icon">
                <i class="bi bi-gear-wide-connected"></i>
            </div>

            <div class="owner-workforce-kpi-label">
                Đang thực hiện
            </div>

            <div class="owner-workforce-kpi-value">
                {{ $inProgressOrders }}
            </div>

        </div>


        <div class="owner-workforce-kpi">

            <div class="owner-workforce-kpi-icon">
                <i class="bi bi-activity"></i>
            </div>

            <div class="owner-workforce-kpi-label">
                Tổng phiếu hoạt động
            </div>

            <div class="owner-workforce-kpi-value">
                {{ $activeOrders }}
            </div>

        </div>

    </section>


    <section class="owner-workforce-panel">

        <div class="owner-workforce-filter">

            <form
                method="GET"
                action="{{ route('admin.workforce.index') }}"
                class="owner-workforce-filter-form"
            >

                <div class="owner-filter-group">

                    <label for="q">
                        Tìm nhân sự
                    </label>

                    <input
                        id="q"
                        type="search"
                        name="q"
                        value="{{ $keyword }}"
                        class="owner-filter-control"
                        placeholder="Tên, email hoặc ID..."
                        maxlength="120"
                    >

                </div>


                <div class="owner-filter-group">

                    <label for="role">
                        Nhóm nhân sự
                    </label>

                    <select
                        id="role"
                        name="role"
                        class="owner-filter-control"
                    >

                        <option value="">
                            Tất cả
                        </option>

                        <option
                            value="STAFF"
                            @selected(
                                $roleFilter === 'STAFF'
                            )
                        >
                            STAFF
                        </option>

                        <option
                            value="TECHNICIAN"
                            @selected(
                                $roleFilter === 'TECHNICIAN'
                            )
                        >
                            TECHNICIAN
                        </option>

                    </select>

                </div>


                <button
                    type="submit"
                    class="owner-filter-submit"
                >

                    <i class="bi bi-search"></i>

                    Lọc

                </button>


                <a
                    href="{{ route('admin.workforce.index') }}"
                    class="owner-filter-reset"
                >

                    <i class="bi bi-arrow-counterclockwise"></i>

                    Đặt lại

                </a>

            </form>

        </div>


        <div class="owner-workforce-result">

            <div class="owner-workforce-result-title">
                Danh sách nhân sự
            </div>


            <div class="owner-workforce-result-meta">

                Hiển thị

                <strong>
                    {{ $workforceUsers->count() }}
                </strong>

                /

                <strong>
                    {{ $workforceUsers->total() }}
                </strong>

                nhân sự.

            </div>

        </div>


        <div class="owner-workforce-table-wrapper">

            <table class="owner-workforce-table">

                <thead>

                    <tr>
                        <th>Nhân sự</th>
                        <th>Vai trò</th>
                        <th>Phiếu đã tạo</th>
                        <th>Được phân công</th>
                        <th>Đang xử lý</th>
                        <th>Hóa đơn</th>
                        <th>Giao dịch kho</th>
                        <th>Thao tác</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse (
                        $workforceUsers
                        as $person
                    )

                        @php
                            $personRole =
                                $person
                                    ->role
                                    ?->code;
                        @endphp


                        <tr>

                            <td>

                                <div class="owner-person">

                                    <div class="owner-person-avatar">

                                        {{
                                            mb_strtoupper(
                                                mb_substr(
                                                    $person->name,
                                                    0,
                                                    1
                                                )
                                            )
                                        }}

                                    </div>


                                    <div>

                                        <div class="owner-person-name">
                                            {{ $person->name }}
                                        </div>

                                        <div class="owner-person-email">
                                            {{ $person->email }}
                                        </div>

                                    </div>

                                </div>

                            </td>


                            <td>

                                <span
                                    class="
                                        owner-role-badge
                                        {{
                                            $personRole === 'STAFF'
                                                ? 'owner-role-staff'
                                                : 'owner-role-technician'
                                        }}
                                    "
                                >

                                    @if (
                                        $personRole === 'STAFF'
                                    )

                                        <i class="bi bi-person-workspace"></i>

                                    @else

                                        <i class="bi bi-tools"></i>

                                    @endif


                                    {{ $personRole }}

                                </span>

                            </td>


                            <td>
                                {{
                                    $person
                                        ->created_service_orders_count
                                }}
                            </td>


                            <td>
                                {{
                                    $person
                                        ->technician_service_orders_count
                                }}
                            </td>


                            <td>

                                @if (
                                    $person
                                        ->active_technician_orders_count
                                    > 0
                                )

                                    <span class="owner-active-number">

                                        {{
                                            $person
                                                ->active_technician_orders_count
                                        }}

                                    </span>

                                @else

                                    <span class="owner-zero-number">
                                        0
                                    </span>

                                @endif

                            </td>


                            <td>
                                {{
                                    $person
                                        ->created_invoices_count
                                }}
                            </td>


                            <td>
                                {{
                                    $person
                                        ->inventory_transactions_count
                                }}
                            </td>


                            <td>

                                <a
                                    href="{{ route(
                                        'admin.workforce.show',
                                        $person->id
                                    ) }}"
                                    class="owner-view-button"
                                >

                                    <i class="bi bi-eye"></i>

                                    Theo dõi

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="owner-workforce-empty"
                            >
                                Không tìm thấy nhân sự phù hợp.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if (
            $workforceUsers
                ->hasPages()
        )

            <div class="owner-workforce-pagination">

                {{
                    $workforceUsers->links()
                }}

            </div>

        @endif

    </section>

</div>

@endsection