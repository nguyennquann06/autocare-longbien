@extends('layouts.app')


@section(
    'title',
    'Thực hiện bảo dưỡng - AutoCare Long Biên'
)


@push('styles')

<style>
    .technician-order-page {
        max-width: 1120px;
    }


    /* =====================================================
       HERO
       ===================================================== */

    .technician-order-hero {
        position: relative;

        overflow: hidden;

        padding: 34px;

        margin-bottom: 23px;

        border-radius: 28px;

        color: white;

        background:
            linear-gradient(
                120deg,
                #06101e 0%,
                #0b2f6b 52%,
                #1467df 100%
            );

        box-shadow:
            0 28px 75px
            rgba(20, 103, 223, 0.23);
    }


    .technician-order-hero::before {
        content: "";

        position: absolute;

        width: 370px;
        height: 370px;

        top: -220px;
        right: -100px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, 0.42),
                transparent 70%
            );
    }


    .technician-order-hero::after {
        content: "";

        position: absolute;

        width: 270px;
        height: 270px;

        left: 38%;
        bottom: -225px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(124, 58, 237, 0.34),
                transparent 70%
            );
    }


    .technician-order-hero-inner {
        position: relative;

        z-index: 2;

        display: flex;

        justify-content: space-between;

        align-items: flex-start;

        gap: 20px;

        flex-wrap: wrap;
    }


    .technician-order-code {
        display: inline-flex;

        align-items: center;

        gap: 6px;

        margin-bottom: 8px;

        color: #bfdbfe;

        font-size: 11px;

        font-weight: 800;
    }


    .technician-order-hero h1 {
        margin: 0;

        color: white;

        font-size:
            clamp(
                2rem,
                4vw,
                3.4rem
            );

        font-weight: 900;

        letter-spacing: -0.055em;
    }


    .technician-order-hero p {
        max-width: 650px;

        margin: 11px 0 0;

        color: #cbd5e1;

        line-height: 1.75;
    }


    /* =====================================================
       MAIN LAYOUT
       ===================================================== */

    .technician-order-layout {
        display: grid;

        grid-template-columns:
            minmax(0, 1.4fr)
            minmax(290px, 0.6fr);

        gap: 22px;

        align-items: start;
    }


    .technician-main-card {
        overflow: hidden;

        border:
            1px solid
            rgba(255, 255, 255, 0.88);

        border-radius: 21px;

        background:
            rgba(255, 255, 255, 0.92);

        box-shadow:
            var(--ac-shadow);

        backdrop-filter:
            blur(16px);
    }


    .technician-main-card-body {
        padding: 27px;
    }


    .technician-section {
        padding: 25px 0;

        border-bottom:
            1px solid #edf1f6;
    }


    .technician-section:first-child {
        padding-top: 0;
    }


    .technician-section:last-child {
        padding-bottom: 0;

        border-bottom: none;
    }


    .technician-section-title {
        display: flex;

        align-items: center;

        gap: 10px;

        margin-bottom: 18px;

        color: #0f172a;

        font-size: 17px;

        font-weight: 900;
    }


    .technician-section-icon {
        width: 38px;
        height: 38px;

        flex: 0 0 auto;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 12px;

        color: #2563eb;

        background:
            linear-gradient(
                135deg,
                #dbeafe,
                #ecfeff
            );
    }


    /* =====================================================
       INFO
       ===================================================== */

    .technician-info-grid {
        display: grid;

        grid-template-columns:
            repeat(
                2,
                minmax(0, 1fr)
            );

        gap: 12px;
    }


    .technician-info-box {
        padding: 15px;

        border:
            1px solid #e7edf4;

        border-radius: 14px;

        background:
            linear-gradient(
                180deg,
                #f8fbff,
                #f5f8fc
            );
    }


    .technician-info-icon {
        margin-bottom: 7px;

        color: #2563eb;

        font-size: 17px;
    }


    .technician-info-label {
        color: #64748b;

        font-size: 9px;

        font-weight: 800;

        text-transform: uppercase;

        letter-spacing: 0.05em;
    }


    .technician-info-value {
        margin-top: 5px;

        color: #0f172a;

        font-size: 12px;

        font-weight: 850;
    }


    /* =====================================================
       NOTE
       ===================================================== */

    .technician-note {
        padding: 16px;

        border:
            1px solid #e4ebf3;

        border-radius: 14px;

        color: #475569;

        background:
            linear-gradient(
                135deg,
                #f8fbff,
                #f7faff
            );

        font-size: 12px;

        line-height: 1.75;
    }


    /* =====================================================
       WORK ITEM
       ===================================================== */

    .technician-work-item {
        position: relative;

        overflow: hidden;

        padding: 19px;

        margin-bottom: 14px;

        border:
            1px solid #e5ecf4;

        border-radius: 16px;

        background:
            linear-gradient(
                145deg,
                #ffffff,
                #f8fbff
            );

        transition:
            transform 0.22s ease,
            border-color 0.22s ease,
            box-shadow 0.22s ease;
    }


    .technician-work-item:hover {
        transform:
            translateY(-3px);

        border-color: #bfdbfe;

        box-shadow:
            0 12px 30px
            rgba(37, 99, 235, 0.08);
    }


    .technician-work-item:last-child {
        margin-bottom: 0;
    }


    .technician-work-item-top {
        display: flex;

        justify-content: space-between;

        align-items: flex-start;

        gap: 15px;

        flex-wrap: wrap;
    }


    .technician-item-name {
        color: #0f172a;

        font-size: 15px;

        font-weight: 900;
    }


    .technician-item-price {
        margin-top: 5px;

        color: #1d4ed8;

        font-size: 12px;

        font-weight: 850;
    }


    .technician-item-status {
        display: inline-flex;

        align-items: center;

        gap: 6px;

        padding: 6px 9px;

        border-radius: 999px;

        font-size: 10px;

        font-weight: 850;
    }


    .technician-item-status.pending {
        color: #92400e;

        background: #fff7ed;
    }


    .technician-item-status.progress {
        color: #6d28d9;

        background: #f5f3ff;
    }


    .technician-item-status.completed {
        color: #047857;

        background: #ecfdf5;
    }


    .technician-item-status.cancelled {
        color: #b91c1c;

        background: #fef2f2;
    }


    .technician-item-form {
        margin-top: 16px;

        padding-top: 16px;

        border-top:
            1px solid #edf1f6;
    }


    .technician-item-note {
        margin-top: 13px;

        padding: 13px;

        border-left:
            3px solid #60a5fa;

        border-radius:
            0 11px 11px 0;

        color: #475569;

        background: #f8fbff;

        font-size: 12px;

        line-height: 1.7;
    }


    /* =====================================================
       SIDEBAR
       ===================================================== */

    .technician-sidebar {
        position: sticky;

        top: 100px;
    }


    .technician-status-card {
        position: relative;

        overflow: hidden;

        padding: 23px;

        border-radius: 21px;

        color: white;

        background:
            linear-gradient(
                145deg,
                #06101e,
                #0d2f69 56%,
                #155bd1
            );

        box-shadow:
            0 25px 60px
            rgba(13, 47, 105, 0.26);
    }


    .technician-status-card::before {
        content: "";

        position: absolute;

        width: 230px;
        height: 230px;

        right: -110px;
        top: -140px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(34, 211, 238, 0.32),
                transparent 70%
            );
    }


    .technician-status-content {
        position: relative;

        z-index: 2;
    }


    .technician-status-icon {
        width: 49px;
        height: 49px;

        display: flex;

        align-items: center;

        justify-content: center;

        margin-bottom: 18px;

        border-radius: 15px;

        color: #67e8f9;

        background:
            rgba(255, 255, 255, 0.09);

        font-size: 21px;
    }


    .technician-status-title {
        margin-bottom: 18px;

        color: white;

        font-size: 18px;

        font-weight: 900;
    }


    .technician-status-row {
        display: flex;

        justify-content: space-between;

        gap: 15px;

        padding: 12px 0;

        border-bottom:
            1px solid
            rgba(255, 255, 255, 0.10);
    }


    .technician-status-label {
        color: #bfdbfe;

        font-size: 11px;
    }


    .technician-status-value {
        color: white;

        text-align: right;

        font-size: 12px;

        font-weight: 850;
    }


    .technician-progress-box {
        margin-top: 18px;

        padding: 14px;

        border:
            1px solid
            rgba(255, 255, 255, 0.10);

        border-radius: 13px;

        background:
            rgba(255, 255, 255, 0.06);
    }


    .technician-progress-head {
        display: flex;

        justify-content: space-between;

        margin-bottom: 8px;

        color: #dbeafe;

        font-size: 10px;

        font-weight: 750;
    }


    .technician-progress-track {
        height: 7px;

        overflow: hidden;

        border-radius: 999px;

        background:
            rgba(255, 255, 255, 0.15);
    }


    .technician-progress-bar {
        height: 100%;

        border-radius: 999px;

        background:
            linear-gradient(
                90deg,
                #67e8f9,
                #93c5fd,
                #c4b5fd
            );
    }


    .technician-primary-action {
        width: 100%;

        min-height: 47px;

        display: flex;

        align-items: center;

        justify-content: center;

        gap: 8px;

        margin-top: 18px;

        border: none;

        border-radius: 13px;

        color: #07111f;

        background:
            linear-gradient(
                135deg,
                #67e8f9,
                #bfdbfe
            );

        font-size: 12px;

        font-weight: 900;

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }


    .technician-primary-action:hover {
        transform:
            translateY(-2px);

        box-shadow:
            0 12px 28px
            rgba(103, 232, 249, 0.20);
    }


    .technician-complete-action {
        color: white;

        background:
            linear-gradient(
                135deg,
                #10b981,
                #047857
            );
    }


    .technician-completed-box {
        display: flex;

        align-items: flex-start;

        gap: 10px;

        margin-top: 18px;

        padding: 14px;

        border:
            1px solid
            rgba(16, 185, 129, 0.30);

        border-radius: 13px;

        color: #d1fae5;

        background:
            rgba(16, 185, 129, 0.12);

        font-size: 11px;

        line-height: 1.6;
    }


    .technician-completed-box i {
        color: #6ee7b7;

        font-size: 18px;
    }


    .technician-back {
        display: inline-flex;

        align-items: center;

        gap: 7px;

        margin-top: 21px;

        color: #64748b;

        text-decoration: none;

        font-size: 12px;

        font-weight: 750;
    }


    .technician-back:hover {
        color: #2563eb;
    }


    @media (max-width: 991px) {
        .technician-order-layout {
            grid-template-columns: 1fr;
        }

        .technician-sidebar {
            position: static;
        }
    }


    @media (max-width: 575px) {
        .technician-info-grid {
            grid-template-columns: 1fr;
        }

        .technician-order-hero {
            padding: 25px;
        }

        .technician-main-card-body {
            padding: 20px;
        }
    }
</style>

@endpush


@section('content')

<div class="container technician-order-page">

    @php
        $statusText = match (
            $serviceOrder->status
        ) {
            'RECEIVED' =>
                'Đã tiếp nhận',

            'IN_PROGRESS' =>
                'Đang thực hiện',

            'COMPLETED' =>
                'Hoàn thành',

            'CANCELLED' =>
                'Đã hủy',

            default =>
                $serviceOrder->status,
        };


        $statusClass = match (
            $serviceOrder->status
        ) {
            'RECEIVED' =>
                'status-confirmed',

            'IN_PROGRESS' =>
                'status-progress',

            'COMPLETED' =>
                'status-completed',

            'CANCELLED' =>
                'status-cancelled',

            default =>
                'status-confirmed',
        };


        $totalItems =
            $serviceOrder
                ->items
                ->count();


        $completedItems =
            $serviceOrder
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
                    * 100
                )
                : 0;


        $allItemsCompleted =
            $serviceOrder
                ->items
                ->every(
                    fn ($item) =>
                        $item->status
                        === 'COMPLETED'
                );
    @endphp


    @if (session('success'))

        <div
            class="alert alert-success"
            data-reveal
        >

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

        </div>

    @endif


    @if (session('error'))

        <div
            class="alert alert-danger"
            data-reveal
        >

            <i class="bi bi-exclamation-circle-fill me-2"></i>

            {{ session('error') }}

        </div>

    @endif


    <section
        class="technician-order-hero"
        data-reveal="zoom"
    >

        <div class="technician-order-hero-inner">

            <div>

                <div class="technician-order-code">

                    <i class="bi bi-hash"></i>

                    {{ $serviceOrder->order_code }}

                </div>


                <h1>
                    Thực hiện bảo dưỡng
                </h1>


                <p>

                    Theo dõi và cập nhật tiến độ
                    các hạng mục kỹ thuật
                    của phiếu bảo dưỡng này.

                </p>

            </div>


            <span
                class="
                    status-badge
                    {{ $statusClass }}
                "
            >

                {{ $statusText }}

            </span>

        </div>

    </section>


    <div class="technician-order-layout">

        <article
            class="technician-main-card"
            data-reveal="left"
        >

            <div class="technician-main-card-body">

                {{-- =====================================
                    INFORMATION
                ====================================== --}}
                <section class="technician-section">

                    <div class="technician-section-title">

                        <span class="technician-section-icon">

                            <i class="bi bi-car-front-fill"></i>

                        </span>

                        Thông tin phiếu

                    </div>


                    <div class="technician-info-grid">

                        <div class="technician-info-box">

                            <div class="technician-info-icon">

                                <i class="bi bi-person"></i>

                            </div>

                            <div class="technician-info-label">
                                Khách hàng
                            </div>

                            <div class="technician-info-value">
                                {{ $serviceOrder->customer->full_name }}
                            </div>

                        </div>


                        <div class="technician-info-box">

                            <div class="technician-info-icon">

                                <i class="bi bi-car-front-fill"></i>

                            </div>

                            <div class="technician-info-label">
                                Xe
                            </div>

                            <div class="technician-info-value">

                                {{ $serviceOrder->vehicle->brand->name }}

                                {{ $serviceOrder->vehicle->vehicleModel->name }}

                            </div>

                        </div>


                        <div class="technician-info-box">

                            <div class="technician-info-icon">

                                <i class="bi bi-credit-card-2-front"></i>

                            </div>

                            <div class="technician-info-label">
                                Biển số
                            </div>

                            <div class="technician-info-value">
                                {{ $serviceOrder->vehicle->license_plate }}
                            </div>

                        </div>


                        <div class="technician-info-box">

                            <div class="technician-info-icon">

                                <i class="bi bi-speedometer2"></i>

                            </div>

                            <div class="technician-info-label">
                                ODO
                            </div>

                            <div class="technician-info-value">

                                {{
                                    number_format(
                                        $serviceOrder->received_mileage
                                    )
                                }} km

                            </div>

                        </div>

                    </div>

                </section>


                {{-- =====================================
                    VEHICLE CONDITION
                ====================================== --}}
                @if ($serviceOrder->vehicle_condition)

                    <section class="technician-section">

                        <div class="technician-section-title">

                            <span class="technician-section-icon">

                                <i class="bi bi-clipboard2-pulse"></i>

                            </span>

                            Tình trạng xe

                        </div>

                        <div class="technician-note">
                            {{ $serviceOrder->vehicle_condition }}
                        </div>

                    </section>

                @endif


                {{-- =====================================
                    DIAGNOSIS
                ====================================== --}}
                @if ($serviceOrder->diagnosis)

                    <section class="technician-section">

                        <div class="technician-section-title">

                            <span class="technician-section-icon">

                                <i class="bi bi-search"></i>

                            </span>

                            Chẩn đoán ban đầu

                        </div>

                        <div class="technician-note">
                            {{ $serviceOrder->diagnosis }}
                        </div>

                    </section>

                @endif


                {{-- =====================================
                    SERVICE ITEMS
                ====================================== --}}
                <section class="technician-section">

                    <div class="technician-section-title">

                        <span class="technician-section-icon">

                            <i class="bi bi-tools"></i>

                        </span>

                        Hạng mục công việc

                    </div>


                    @foreach ($serviceOrder->items as $item)

                        @php
                            $itemStatusText = match (
                                $item->status
                            ) {
                                'PENDING' =>
                                    'Chưa thực hiện',

                                'IN_PROGRESS' =>
                                    'Đang thực hiện',

                                'COMPLETED' =>
                                    'Hoàn thành',

                                'CANCELLED' =>
                                    'Đã hủy',

                                default =>
                                    $item->status,
                            };


                            $itemStatusClass = match (
                                $item->status
                            ) {
                                'PENDING' =>
                                    'pending',

                                'IN_PROGRESS' =>
                                    'progress',

                                'COMPLETED' =>
                                    'completed',

                                'CANCELLED' =>
                                    'cancelled',

                                default =>
                                    'pending',
                            };
                        @endphp


                        <div class="technician-work-item">

                            <div class="technician-work-item-top">

                                <div>

                                    <div class="technician-item-name">

                                        {{ $item->service_name }}

                                    </div>


                                    <div class="technician-item-price">

                                        {{
                                            number_format(
                                                $item->line_total,
                                                0,
                                                ',',
                                                '.'
                                            )
                                        }} đ

                                    </div>

                                </div>


                                <span
                                    class="
                                        technician-item-status
                                        {{ $itemStatusClass }}
                                    "
                                >

                                    @if ($item->status === 'COMPLETED')

                                        <i class="bi bi-check-circle-fill"></i>

                                    @elseif (
                                        $item->status
                                        === 'IN_PROGRESS'
                                    )

                                        <i class="bi bi-arrow-repeat"></i>

                                    @elseif (
                                        $item->status
                                        === 'CANCELLED'
                                    )

                                        <i class="bi bi-x-circle-fill"></i>

                                    @else

                                        <i class="bi bi-clock"></i>

                                    @endif


                                    {{ $itemStatusText }}

                                </span>

                            </div>


                            @if (
                                $serviceOrder->status
                                === 'IN_PROGRESS'
                                &&
                                $item->status
                                !== 'COMPLETED'
                            )

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'technician.service-orders.items.update',
                                        [
                                            $serviceOrder->id,
                                            $item->id,
                                        ]
                                    ) }}"
                                    class="technician-item-form"
                                >

                                    @csrf
                                    @method('PATCH')


                                    @if ($item->status === 'PENDING')

                                        <input
                                            type="hidden"
                                            name="status"
                                            value="IN_PROGRESS"
                                        >

                                    @elseif (
                                        $item->status
                                        === 'IN_PROGRESS'
                                    )

                                        <input
                                            type="hidden"
                                            name="status"
                                            value="COMPLETED"
                                        >

                                    @endif


                                    <label
                                        for="technician_note_{{ $item->id }}"
                                        class="form-label fw-bold"
                                    >
                                        Ghi chú kỹ thuật
                                    </label>


                                    <textarea
                                        id="technician_note_{{ $item->id }}"
                                        name="technician_note"
                                        class="form-control"
                                        rows="3"
                                        maxlength="1000"
                                        placeholder="Ghi chú kỹ thuật cho hạng mục..."
                                    >{{ $item->technician_note }}</textarea>


                                    <button
                                        type="submit"
                                        class="
                                            btn
                                            {{
                                                $item->status
                                                === 'PENDING'
                                                    ? 'btn-primary'
                                                    : 'btn-success'
                                            }}
                                            mt-3
                                        "
                                    >

                                        @if (
                                            $item->status
                                            === 'PENDING'
                                        )

                                            <i class="bi bi-play-fill me-1"></i>

                                            Bắt đầu hạng mục

                                        @else

                                            <i class="bi bi-check-lg me-1"></i>

                                            Hoàn thành hạng mục

                                        @endif

                                    </button>

                                </form>

                            @endif


                            @if (
                                $item->status
                                === 'COMPLETED'
                                &&
                                $item->technician_note
                            )

                                <div class="technician-item-note">

                                    <strong>

                                        <i class="bi bi-chat-square-check me-1"></i>

                                        Ghi chú kỹ thuật:

                                    </strong>

                                    {{ $item->technician_note }}

                                </div>

                            @endif

                        </div>

                    @endforeach

                </section>


                {{-- =====================================
                    TECHNICIAN FINAL NOTE
                ====================================== --}}
                @if (
                    $serviceOrder->status
                    === 'COMPLETED'
                    &&
                    $serviceOrder->technician_note
                )

                    <section class="technician-section">

                        <div class="technician-section-title">

                            <span class="technician-section-icon">

                                <i class="bi bi-chat-square-check"></i>

                            </span>

                            Tổng kết kỹ thuật

                        </div>


                        <div class="technician-note">

                            {{ $serviceOrder->technician_note }}

                        </div>

                    </section>

                @endif

            </div>

        </article>


        {{-- =========================================
            STATUS SIDEBAR
        ========================================== --}}
        <aside
            class="technician-sidebar"
            data-reveal="right"
        >

            <div class="technician-status-card">

                <div class="technician-status-content">

                    <div class="technician-status-icon">

                        <i class="bi bi-wrench-adjustable-circle"></i>

                    </div>


                    <div class="technician-status-title">
                        Tiến độ công việc
                    </div>


                    <div class="technician-status-row">

                        <span class="technician-status-label">
                            Trạng thái
                        </span>

                        <span class="technician-status-value">
                            {{ $statusText }}
                        </span>

                    </div>


                    <div class="technician-status-row">

                        <span class="technician-status-label">
                            Tổng hạng mục
                        </span>

                        <span class="technician-status-value">
                            {{ $totalItems }}
                        </span>

                    </div>


                    <div class="technician-status-row">

                        <span class="technician-status-label">
                            Đã hoàn thành
                        </span>

                        <span class="technician-status-value">
                            {{ $completedItems }}
                        </span>

                    </div>


                    <div class="technician-status-row">

                        <span class="technician-status-label">
                            ODO
                        </span>

                        <span class="technician-status-value">

                            {{
                                number_format(
                                    $serviceOrder
                                        ->received_mileage
                                )
                            }} km

                        </span>

                    </div>


                    <div class="technician-progress-box">

                        <div class="technician-progress-head">

                            <span>
                                Tiến độ
                            </span>

                            <strong>
                                {{ $progressPercent }}%
                            </strong>

                        </div>


                        <div class="technician-progress-track">

                            <div
                                class="technician-progress-bar"
                                style="
                                    width:
                                    {{ $progressPercent }}%;
                                "
                            ></div>

                        </div>

                    </div>


                    {{-- START --}}
                    @if (
                        $serviceOrder->status
                        === 'RECEIVED'
                    )

                        <form
                            id="startServiceOrderForm"
                            method="POST"
                            action="{{ route(
                                'technician.service-orders.start',
                                $serviceOrder->id
                            ) }}"
                        >

                            @csrf
                            @method('PATCH')


                            <button
                                type="button"
                                class="technician-primary-action"
                                data-bs-toggle="modal"
                                data-bs-target="#startServiceOrderModal"
                            >

                                <i class="bi bi-play-fill"></i>

                                Bắt đầu bảo dưỡng

                            </button>

                        </form>

                    @endif


                    {{-- COMPLETE --}}
                    @if (
                        $serviceOrder->status
                        === 'IN_PROGRESS'
                    )

                        @if ($allItemsCompleted)

                            <button
                                type="button"
                                class="
                                    technician-primary-action
                                    technician-complete-action
                                "
                                data-bs-toggle="modal"
                                data-bs-target="#completeServiceOrderModal"
                            >

                                <i class="bi bi-check2-circle"></i>

                                Hoàn thành phiếu

                            </button>

                        @else

                            <div class="technician-completed-box">

                                <i class="bi bi-info-circle"></i>

                                <span>

                                    Hoàn thành tất cả
                                    hạng mục trước khi
                                    đóng phiếu bảo dưỡng.

                                </span>

                            </div>

                        @endif

                    @endif


                    {{-- COMPLETED --}}
                    @if (
                        $serviceOrder->status
                        === 'COMPLETED'
                    )

                        <div class="technician-completed-box">

                            <i class="bi bi-patch-check-fill"></i>

                            <span>

                                Phiếu bảo dưỡng đã hoàn thành.

                                @if ($serviceOrder->completed_at)

                                    <br>

                                    {{
                                        $serviceOrder
                                            ->completed_at
                                            ->format(
                                                'd/m/Y H:i'
                                            )
                                    }}

                                @endif

                            </span>

                        </div>

                    @endif


                    {{-- CANCELLED --}}
                    @if (
                        $serviceOrder->status
                        === 'CANCELLED'
                    )

                        <div
                            class="technician-completed-box"
                            style="
                                border-color:
                                    rgba(
                                        248,
                                        113,
                                        113,
                                        0.35
                                    );

                                color:
                                    #fee2e2;

                                background:
                                    rgba(
                                        239,
                                        68,
                                        68,
                                        0.13
                                    );
                            "
                        >

                            <i
                                class="bi bi-x-circle-fill"
                                style="color: #fca5a5;"
                            ></i>

                            <span>
                                Phiếu bảo dưỡng đã bị hủy.
                            </span>

                        </div>

                    @endif

                </div>

            </div>

        </aside>

    </div>


    <a
        href="{{ route(
            'technician.service-orders.index'
        ) }}"
        class="technician-back"
    >

        <i class="bi bi-arrow-left"></i>

        Quay lại công việc của tôi

    </a>

</div>


{{-- =====================================================
    START SERVICE ORDER MODAL
===================================================== --}}
@if ($serviceOrder->status === 'RECEIVED')

    <div
        class="modal fade"
        id="startServiceOrderModal"
        tabindex="-1"
        aria-labelledby="startServiceOrderModalLabel"
        aria-hidden="true"
    >

        <div
            class="
                modal-dialog
                modal-dialog-centered
            "
        >

            <div
                class="
                    modal-content
                    border-0
                    rounded-4
                    shadow-lg
                "
            >

                <div
                    class="
                        modal-body
                        p-4
                        p-md-5
                        text-center
                    "
                >

                    <div
                        class="
                            fs-1
                            text-primary
                            mb-3
                        "
                    >

                        <i class="bi bi-play-circle-fill"></i>

                    </div>


                    <h3
                        id="startServiceOrderModalLabel"
                        class="mb-3"
                    >
                        Bắt đầu bảo dưỡng?
                    </h3>


                    <p class="text-secondary mb-4">

                        Bạn sẽ bắt đầu thực hiện
                        phiếu

                        <strong>
                            {{ $serviceOrder->order_code }}
                        </strong>.

                        Trạng thái phiếu sẽ chuyển
                        sang “Đang thực hiện”.

                    </p>


                    <div
                        class="
                            d-grid
                            d-sm-flex
                            justify-content-center
                            gap-2
                        "
                    >

                        <button
                            type="button"
                            class="
                                btn
                                btn-light
                                px-4
                            "
                            data-bs-dismiss="modal"
                        >
                            Quay lại
                        </button>


                        <button
                            type="button"
                            class="
                                btn
                                btn-primary
                                px-4
                            "
                            onclick="
                                document
                                    .getElementById(
                                        'startServiceOrderForm'
                                    )
                                    .requestSubmit();
                            "
                        >

                            <i class="bi bi-play-fill me-1"></i>

                            Bắt đầu

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endif


{{-- =====================================================
    COMPLETE SERVICE ORDER MODAL
===================================================== --}}
@if (
    $serviceOrder->status === 'IN_PROGRESS'
    &&
    $allItemsCompleted
)

    <div
        class="modal fade"
        id="completeServiceOrderModal"
        tabindex="-1"
        aria-labelledby="completeServiceOrderModalLabel"
        aria-hidden="true"
    >

        <div
            class="
                modal-dialog
                modal-dialog-centered
                modal-lg
            "
        >

            <div
                class="
                    modal-content
                    border-0
                    rounded-4
                    shadow-lg
                "
            >

                <div
                    class="
                        modal-body
                        p-4
                        p-md-5
                    "
                >

                    <div class="text-center">

                        <div
                            class="
                                fs-1
                                text-success
                                mb-3
                            "
                        >

                            <i class="bi bi-patch-check-fill"></i>

                        </div>


                        <h3
                            id="completeServiceOrderModalLabel"
                            class="mb-2"
                        >
                            Hoàn thành phiếu bảo dưỡng?
                        </h3>


                        <p class="text-secondary">

                            Tất cả hạng mục đã hoàn thành.

                            Hãy nhập ghi chú tổng kết
                            trước khi đóng phiếu.

                        </p>

                    </div>


                    <form
                        id="completeServiceOrderForm"
                        method="POST"
                        action="{{ route(
                            'technician.service-orders.complete',
                            $serviceOrder->id
                        ) }}"
                    >

                        @csrf
                        @method('PATCH')


                        <div class="mt-4">

                            <label
                                for="final_technician_note"
                                class="form-label fw-bold"
                            >
                                Ghi chú tổng kết kỹ thuật
                            </label>


                            <textarea
                                id="final_technician_note"
                                name="technician_note"
                                class="form-control"
                                rows="5"
                                maxlength="2000"
                                placeholder="Ví dụ: Đã hoàn thành toàn bộ hạng mục, xe vận hành ổn định..."
                            >{{ $serviceOrder->technician_note }}</textarea>

                        </div>


                        <div
                            class="
                                d-grid
                                d-sm-flex
                                justify-content-end
                                gap-2
                                mt-4
                            "
                        >

                            <button
                                type="button"
                                class="
                                    btn
                                    btn-light
                                    px-4
                                "
                                data-bs-dismiss="modal"
                            >
                                Quay lại
                            </button>


                            <button
                                type="submit"
                                class="
                                    btn
                                    btn-success
                                    px-4
                                "
                            >

                                <i class="bi bi-check2-circle me-1"></i>

                                Hoàn thành phiếu

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

@endif

@endsection