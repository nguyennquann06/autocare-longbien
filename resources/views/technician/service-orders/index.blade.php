@extends('layouts.app')


@section(
    'title',
    'Công việc kỹ thuật viên - AutoCare Long Biên'
)


@push('styles')

<style>
    .technician-work-page {
        max-width: 1200px;
    }


    /* =====================================================
       HERO
       ===================================================== */

    .technician-work-hero {
        position: relative;

        overflow: hidden;

        padding: 35px;

        margin-bottom: 27px;

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


    .technician-work-hero::before {
        content: "";

        position: absolute;

        width: 380px;
        height: 380px;

        top: -225px;
        right: -110px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, 0.42),
                transparent 70%
            );
    }


    .technician-work-hero::after {
        content: "";

        position: absolute;

        width: 280px;
        height: 280px;

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


    .technician-work-hero-content {
        position: relative;

        z-index: 2;
    }


    .technician-work-chip {
        display: inline-flex;

        align-items: center;

        gap: 8px;

        padding: 7px 12px;

        margin-bottom: 16px;

        border:
            1px solid
            rgba(255, 255, 255, 0.16);

        border-radius: 999px;

        color: #dbeafe;

        background:
            rgba(255, 255, 255, 0.08);

        font-size: 11px;

        font-weight: 800;

        text-transform: uppercase;

        letter-spacing: 0.07em;
    }


    .technician-work-chip-dot {
        width: 8px;
        height: 8px;

        border-radius: 50%;

        background: #67e8f9;

        box-shadow:
            0 0 12px
            #67e8f9;
    }


    .technician-work-hero h1 {
        margin: 0;

        color: white;

        font-size:
            clamp(
                2rem,
                4vw,
                3.5rem
            );

        font-weight: 900;

        letter-spacing: -0.055em;
    }


    .technician-work-hero p {
        max-width: 700px;

        margin: 12px 0 0;

        color: #cbd5e1;

        line-height: 1.8;
    }


    /* =====================================================
       SUMMARY
       ===================================================== */

    .technician-summary-grid {
        display: grid;

        grid-template-columns:
            repeat(
                4,
                minmax(0, 1fr)
            );

        gap: 15px;

        margin-bottom: 25px;
    }


    .technician-summary-card {
        position: relative;

        overflow: hidden;

        min-height: 145px;

        padding: 20px;

        border:
            1px solid
            rgba(255, 255, 255, 0.88);

        border-radius: 19px;

        background:
            rgba(255, 255, 255, 0.92);

        box-shadow:
            var(--ac-shadow);

        backdrop-filter:
            blur(16px);

        transition:
            transform 0.23s ease,
            box-shadow 0.23s ease;
    }


    .technician-summary-card:hover {
        transform:
            translateY(-5px);

        box-shadow:
            var(--ac-shadow-lg);
    }


    .technician-summary-card::after {
        content: "";

        position: absolute;

        width: 120px;
        height: 120px;

        right: -55px;
        bottom: -60px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(59, 130, 246, 0.14),
                transparent 70%
            );
    }


    .technician-summary-icon {
        width: 45px;
        height: 45px;

        display: flex;

        align-items: center;

        justify-content: center;

        margin-bottom: 14px;

        border-radius: 14px;

        color: #2563eb;

        background:
            linear-gradient(
                135deg,
                #dbeafe,
                #ecfeff
            );

        font-size: 20px;
    }


    .technician-summary-card.purple
    .technician-summary-icon {
        color: #7c3aed;

        background:
            linear-gradient(
                135deg,
                #ede9fe,
                #f5f3ff
            );
    }


    .technician-summary-card.green
    .technician-summary-icon {
        color: #059669;

        background:
            linear-gradient(
                135deg,
                #d1fae5,
                #ecfdf5
            );
    }


    .technician-summary-card.red
    .technician-summary-icon {
        color: #dc2626;

        background:
            linear-gradient(
                135deg,
                #fee2e2,
                #fef2f2
            );
    }


    .technician-summary-label {
        color: #64748b;

        font-size: 10px;

        font-weight: 800;

        text-transform: uppercase;

        letter-spacing: 0.05em;
    }


    .technician-summary-value {
        position: relative;

        z-index: 2;

        margin-top: 6px;

        color: #0f172a;

        font-size: 25px;

        font-weight: 900;

        letter-spacing: -0.04em;
    }


    /* =====================================================
       ORDER CARD
       ===================================================== */

    .technician-order-card {
        position: relative;

        overflow: hidden;

        margin-bottom: 18px;

        padding: 24px;

        border:
            1px solid
            rgba(255, 255, 255, 0.88);

        border-radius: 20px;

        background:
            rgba(255, 255, 255, 0.92);

        box-shadow:
            var(--ac-shadow);

        backdrop-filter:
            blur(16px);

        transition:
            transform 0.23s ease,
            box-shadow 0.23s ease,
            border-color 0.23s ease;
    }


    .technician-order-card:hover {
        transform:
            translateY(-5px);

        border-color:
            rgba(147, 197, 253, 0.65);

        box-shadow:
            var(--ac-shadow-lg);
    }


    .technician-order-card::after {
        content: "";

        position: absolute;

        width: 160px;
        height: 160px;

        top: -105px;
        right: -105px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(59, 130, 246, 0.13),
                transparent 70%
            );
    }


    .technician-order-top {
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

        color: #64748b;

        font-size: 11px;

        font-weight: 800;
    }


    .technician-order-vehicle {
        margin-top: 8px;

        color: #0f172a;

        font-size: 21px;

        font-weight: 900;

        letter-spacing: -0.03em;
    }


    .technician-order-license {
        display: inline-flex;

        align-items: center;

        gap: 6px;

        margin-top: 6px;

        color: #64748b;

        font-size: 12px;
    }


    /* =====================================================
       INFO GRID
       ===================================================== */

    .technician-info-grid {
        position: relative;

        z-index: 2;

        display: grid;

        grid-template-columns:
            repeat(
                4,
                minmax(0, 1fr)
            );

        gap: 12px;

        margin-top: 20px;
    }


    .technician-info-box {
        padding: 14px;

        border:
            1px solid #e7edf4;

        border-radius: 14px;

        background:
            linear-gradient(
                180deg,
                #f8fbff,
                #f5f8fc
            );

        transition:
            transform 0.2s ease,
            border-color 0.2s ease;
    }


    .technician-info-box:hover {
        transform:
            translateY(-2px);

        border-color: #bfdbfe;
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
       PROGRESS
       ===================================================== */

    .work-progress {
        position: relative;

        z-index: 2;

        margin-top: 19px;

        padding: 15px;

        border:
            1px solid #e4ebf3;

        border-radius: 14px;

        background: #f8fbff;
    }


    .work-progress-header {
        display: flex;

        justify-content: space-between;

        gap: 15px;

        margin-bottom: 9px;

        color: #475569;

        font-size: 11px;

        font-weight: 750;
    }


    .work-progress-track {
        height: 7px;

        overflow: hidden;

        border-radius: 999px;

        background: #e5e7eb;
    }


    .work-progress-bar {
        height: 100%;

        border-radius: 999px;

        background:
            linear-gradient(
                90deg,
                #22d3ee,
                #2563eb,
                #7c3aed
            );

        transition:
            width 0.3s ease;
    }


    /* =====================================================
       BUTTON
       ===================================================== */

    .technician-card-footer {
        position: relative;

        z-index: 2;

        display: flex;

        justify-content: flex-end;

        margin-top: 20px;
    }


    .technician-work-button {
        display: inline-flex;

        align-items: center;

        gap: 8px;

        padding: 10px 15px;

        border-radius: 11px;

        color: white;

        text-decoration: none;

        background:
            linear-gradient(
                135deg,
                #1683ff,
                #4f46e5
            );

        box-shadow:
            0 8px 22px
            rgba(37, 99, 235, 0.20);

        font-size: 12px;

        font-weight: 850;

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }


    .technician-work-button:hover {
        color: white;

        transform:
            translateY(-2px);

        box-shadow:
            0 12px 28px
            rgba(37, 99, 235, 0.28);
    }


    @media (max-width: 991px) {
        .technician-summary-grid {
            grid-template-columns:
                repeat(
                    2,
                    minmax(0, 1fr)
                );
        }

        .technician-info-grid {
            grid-template-columns:
                repeat(
                    2,
                    minmax(0, 1fr)
                );
        }
    }


    @media (max-width: 575px) {
        .technician-summary-grid,
        .technician-info-grid {
            grid-template-columns: 1fr;
        }

        .technician-work-hero {
            padding: 25px;
        }

        .technician-order-card {
            padding: 19px;
        }
    }
</style>

@endpush


@section('content')

<div class="container technician-work-page">

    @php
        $receivedCount =
            $serviceOrders
                ->where(
                    'status',
                    'RECEIVED'
                )
                ->count();

        $progressCount =
            $serviceOrders
                ->where(
                    'status',
                    'IN_PROGRESS'
                )
                ->count();

        $completedCount =
            $serviceOrders
                ->where(
                    'status',
                    'COMPLETED'
                )
                ->count();

        $cancelledCount =
            $serviceOrders
                ->where(
                    'status',
                    'CANCELLED'
                )
                ->count();
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


    <section
        class="technician-work-hero"
        data-reveal="zoom"
    >

        <div class="technician-work-hero-content">

            <div class="technician-work-chip">

                <span class="technician-work-chip-dot"></span>

                Technician Workspace

            </div>


            <h1>
                Công việc được phân công
            </h1>


            <p>

                Theo dõi các phiếu bảo dưỡng
                được giao cho bạn và cập nhật
                tiến độ từng hạng mục kỹ thuật
                trong quá trình chăm sóc xe.

            </p>

        </div>

    </section>


    <section class="technician-summary-grid">

        <div
            class="technician-summary-card"
            data-reveal
            data-tilt
        >

            <div class="technician-summary-icon">

                <i class="bi bi-inbox"></i>

            </div>


            <div class="technician-summary-label">
                Chờ bắt đầu
            </div>


            <div class="technician-summary-value">
                {{ $receivedCount }}
            </div>

        </div>


        <div
            class="
                technician-summary-card
                purple
            "
            data-reveal
            data-tilt
        >

            <div class="technician-summary-icon">

                <i class="bi bi-wrench-adjustable-circle"></i>

            </div>


            <div class="technician-summary-label">
                Đang thực hiện
            </div>


            <div class="technician-summary-value">
                {{ $progressCount }}
            </div>

        </div>


        <div
            class="
                technician-summary-card
                green
            "
            data-reveal
            data-tilt
        >

            <div class="technician-summary-icon">

                <i class="bi bi-patch-check"></i>

            </div>


            <div class="technician-summary-label">
                Đã hoàn thành
            </div>


            <div class="technician-summary-value">
                {{ $completedCount }}
            </div>

        </div>


        <div
            class="
                technician-summary-card
                red
            "
            data-reveal
            data-tilt
        >

            <div class="technician-summary-icon">

                <i class="bi bi-x-circle"></i>

            </div>


            <div class="technician-summary-label">
                Đã hủy
            </div>


            <div class="technician-summary-value">
                {{ $cancelledCount }}
            </div>

        </div>

    </section>


    @if ($serviceOrders->isEmpty())

        <div
            class="empty-state"
            data-reveal="zoom"
        >

            <div class="empty-state-icon">

                <i class="bi bi-clipboard2-check"></i>

            </div>


            <h3>
                Chưa có công việc được phân công
            </h3>


            <p>

                Khi nhân viên tiếp nhận xe
                và phân công cho bạn,
                phiếu bảo dưỡng sẽ xuất hiện tại đây.

            </p>

        </div>

    @else

        @foreach ($serviceOrders as $serviceOrder)

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
            @endphp


            <article
                class="technician-order-card"
                data-reveal
            >

                <div class="technician-order-top">

                    <div>

                        <div class="technician-order-code">

                            <i class="bi bi-hash"></i>

                            {{ $serviceOrder->order_code }}

                        </div>


                        <div class="technician-order-vehicle">

                            {{ $serviceOrder->vehicle->brand->name }}

                            {{ $serviceOrder->vehicle->vehicleModel->name }}

                        </div>


                        <div class="technician-order-license">

                            <i class="bi bi-car-front-fill"></i>

                            {{ $serviceOrder->vehicle->license_plate }}

                        </div>

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

                            <i class="bi bi-speedometer2"></i>

                        </div>


                        <div class="technician-info-label">
                            ODO tiếp nhận
                        </div>


                        <div class="technician-info-value">

                            {{
                                number_format(
                                    $serviceOrder
                                        ->received_mileage
                                )
                            }} km

                        </div>

                    </div>


                    <div class="technician-info-box">

                        <div class="technician-info-icon">

                            <i class="bi bi-list-check"></i>

                        </div>


                        <div class="technician-info-label">
                            Hạng mục
                        </div>


                        <div class="technician-info-value">

                            {{ $totalItems }}

                        </div>

                    </div>


                    <div class="technician-info-box">

                        <div class="technician-info-icon">

                            <i class="bi bi-clock-history"></i>

                        </div>


                        <div class="technician-info-label">
                            Tiếp nhận lúc
                        </div>


                        <div class="technician-info-value">

                            {{
                                $serviceOrder->received_at
                                    ? $serviceOrder
                                        ->received_at
                                        ->format(
                                            'd/m/Y H:i'
                                        )
                                    : 'Chưa cập nhật'
                            }}

                        </div>

                    </div>

                </div>


                @if (
                    $serviceOrder->status
                    === 'IN_PROGRESS'
                )

                    <div class="work-progress">

                        <div class="work-progress-header">

                            <span>
                                Tiến độ hạng mục
                            </span>

                            <strong>

                                {{ $completedItems }}
                                /
                                {{ $totalItems }}

                                ·

                                {{ $progressPercent }}%

                            </strong>

                        </div>


                        <div class="work-progress-track">

                            <div
                                class="work-progress-bar"
                                style="
                                    width:
                                    {{ $progressPercent }}%;
                                "
                            ></div>

                        </div>

                    </div>

                @endif


                <div class="technician-card-footer">

                    <a
                        href="{{ route(
                            'technician.service-orders.show',
                            $serviceOrder->id
                        ) }}"
                        class="technician-work-button"
                    >

                        @if (
                            $serviceOrder->status
                            === 'COMPLETED'
                        )

                            <i class="bi bi-eye"></i>

                            Xem kết quả

                        @elseif (
                            $serviceOrder->status
                            === 'CANCELLED'
                        )

                            <i class="bi bi-eye"></i>

                            Xem chi tiết

                        @else

                            <i class="bi bi-wrench-adjustable"></i>

                            Xử lý công việc

                        @endif

                    </a>

                </div>

            </article>

        @endforeach

    @endif

</div>

@endsection