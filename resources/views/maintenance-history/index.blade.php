@extends('layouts.app')


@section(
    'title',
    'Lịch sử bảo dưỡng - AutoCare Long Biên'
)


@push('styles')

<style>
    .maintenance-history-page {
        max-width: 1180px;
    }

    .maintenance-history-hero {
        position: relative;
        overflow: hidden;

        padding: 34px;
        margin-bottom: 26px;

        border-radius: 27px;

        color: white;

        background:
            linear-gradient(
                120deg,
                #06101e 0%,
                #0c3474 52%,
                #1677ff 100%
            );

        box-shadow:
            0 26px 75px
            rgba(22, 119, 255, 0.23);
    }

    .maintenance-history-hero::before {
        content: "";

        position: absolute;

        width: 360px;
        height: 360px;

        top: -210px;
        right: -110px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, 0.42),
                transparent 70%
            );
    }

    .maintenance-history-hero::after {
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

    .maintenance-history-hero-content {
        position: relative;
        z-index: 2;
    }

    .maintenance-history-chip {
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

    .maintenance-history-chip-dot {
        width: 8px;
        height: 8px;

        border-radius: 50%;

        background: #67e8f9;

        box-shadow:
            0 0 12px
            #67e8f9;
    }

    .maintenance-history-hero h1 {
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

    .maintenance-history-hero p {
        max-width: 720px;

        margin: 12px 0 0;

        color: #cbd5e1;

        line-height: 1.8;
    }

    .history-summary-grid {
        display: grid;

        grid-template-columns:
            repeat(
                3,
                minmax(0, 1fr)
            );

        gap: 15px;

        margin-bottom: 25px;
    }

    .history-summary-card {
        position: relative;
        overflow: hidden;

        padding: 19px;

        border:
            1px solid
            rgba(255, 255, 255, 0.88);

        border-radius: 18px;

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

    .history-summary-card:hover {
        transform:
            translateY(-5px);

        box-shadow:
            var(--ac-shadow-lg);
    }

    .history-summary-icon {
        width: 44px;
        height: 44px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-bottom: 13px;

        border-radius: 13px;

        color: #2563eb;

        background:
            linear-gradient(
                135deg,
                #dbeafe,
                #ecfeff
            );

        font-size: 19px;
    }

    .history-summary-card.green
    .history-summary-icon {
        color: #059669;

        background:
            linear-gradient(
                135deg,
                #d1fae5,
                #ecfdf5
            );
    }

    .history-summary-card.purple
    .history-summary-icon {
        color: #7c3aed;

        background:
            linear-gradient(
                135deg,
                #ede9fe,
                #f5f3ff
            );
    }

    .history-summary-label {
        color: #64748b;

        font-size: 10px;
        font-weight: 800;

        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .history-summary-value {
        margin-top: 5px;

        color: #0f172a;

        font-size: 24px;
        font-weight: 900;

        letter-spacing: -0.04em;
    }

    .history-timeline {
        position: relative;

        padding-left: 31px;
    }

    .history-timeline::before {
        content: "";

        position: absolute;

        top: 14px;
        bottom: 14px;
        left: 8px;

        width: 2px;

        background:
            linear-gradient(
                to bottom,
                #22d3ee,
                #2563eb,
                rgba(37, 99, 235, 0.05)
            );
    }

    .history-entry {
        position: relative;

        margin-bottom: 19px;
    }

    .history-entry:last-child {
        margin-bottom: 0;
    }

    .history-entry::before {
        content: "";

        position: absolute;

        width: 14px;
        height: 14px;

        left: -29px;
        top: 26px;

        border-radius: 50%;

        background:
            linear-gradient(
                135deg,
                #22d3ee,
                #2563eb
            );

        border:
            3px solid #eef4fb;

        box-shadow:
            0 0 0 4px
            rgba(37, 99, 235, 0.11),
            0 0 15px
            rgba(34, 211, 238, 0.45);
    }

    .history-card {
        position: relative;
        overflow: hidden;

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

    .history-card:hover {
        transform:
            translateY(-5px);

        border-color:
            rgba(147, 197, 253, 0.65);

        box-shadow:
            var(--ac-shadow-lg);
    }

    .history-card::after {
        content: "";

        position: absolute;

        width: 150px;
        height: 150px;

        right: -100px;
        top: -100px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(59, 130, 246, 0.13),
                transparent 70%
            );
    }

    .history-card-top {
        position: relative;
        z-index: 2;

        display: flex;

        justify-content: space-between;
        align-items: flex-start;

        gap: 20px;

        flex-wrap: wrap;
    }

    .history-code {
        display: inline-flex;

        align-items: center;

        gap: 6px;

        color: #64748b;

        font-size: 12px;
        font-weight: 800;
    }

    .history-vehicle {
        margin-top: 8px;

        color: #0f172a;

        font-size: 20px;
        font-weight: 900;

        letter-spacing: -0.03em;
    }

    .history-license {
        display: inline-flex;

        align-items: center;

        gap: 6px;

        margin-top: 6px;

        color: #64748b;

        font-size: 12px;
    }

    .history-info-grid {
        position: relative;
        z-index: 2;

        display: grid;

        grid-template-columns:
            repeat(
                5,
                minmax(0, 1fr)
            );

        gap: 12px;

        margin-top: 21px;
    }

    .history-info-box {
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

    .history-info-box:hover {
        transform:
            translateY(-2px);

        border-color: #bfdbfe;
    }

    .history-info-icon {
        margin-bottom: 7px;

        color: #2563eb;

        font-size: 17px;
    }

    .history-info-label {
        color: #64748b;

        font-size: 9px;
        font-weight: 800;

        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .history-info-value {
        margin-top: 5px;

        color: #0f172a;

        font-size: 12px;
        font-weight: 850;
    }

    .history-total {
        color: #1d4ed8;

        font-size: 15px;
        font-weight: 900;
    }

    .history-card-footer {
        position: relative;
        z-index: 2;

        display: flex;

        justify-content: flex-end;

        margin-top: 20px;
    }

    .history-detail-button {
        display: inline-flex;

        align-items: center;

        gap: 8px;

        padding: 10px 14px;

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

    .history-detail-button:hover {
        color: white;

        transform:
            translateY(-2px);

        box-shadow:
            0 12px 28px
            rgba(37, 99, 235, 0.28);
    }

    .history-back {
        display: inline-flex;

        align-items: center;

        gap: 7px;

        margin-top: 23px;

        color: #64748b;

        text-decoration: none;

        font-size: 13px;
        font-weight: 750;
    }

    .history-back:hover {
        color: #2563eb;
    }

    @media (max-width: 1199px) {
        .history-info-grid {
            grid-template-columns:
                repeat(
                    3,
                    minmax(0, 1fr)
                );
        }
    }

    @media (max-width: 767px) {
        .history-summary-grid {
            grid-template-columns: 1fr;
        }

        .history-info-grid {
            grid-template-columns:
                repeat(
                    2,
                    minmax(0, 1fr)
                );
        }

        .maintenance-history-hero {
            padding: 25px;
        }
    }

    @media (max-width: 575px) {
        .history-timeline {
            padding-left: 0;
        }

        .history-timeline::before,
        .history-entry::before {
            display: none;
        }

        .history-info-grid {
            grid-template-columns: 1fr;
        }

        .history-card {
            padding: 19px;
        }
    }
</style>

@endpush


@section('content')

<div class="container maintenance-history-page">

    @php
        $totalMaintenance =
            $serviceOrders->count();

        $totalAmount =
            $serviceOrders->sum(
                'total_amount'
            );

        $totalItems =
            $serviceOrders->sum(
                function ($order) {
                    return $order
                        ->items
                        ->count();
                }
            );
    @endphp


    <section
        class="maintenance-history-hero"
        data-reveal="zoom"
    >

        <div class="maintenance-history-hero-content">

            <div class="maintenance-history-chip">

                <span class="maintenance-history-chip-dot"></span>

                Maintenance Journey

            </div>


            <h1>
                Lịch sử bảo dưỡng
            </h1>


            <p>

                Toàn bộ hành trình chăm sóc xe,
                các mốc ODO, hạng mục đã thực hiện
                và kỹ thuật viên phụ trách
                được lưu lại tại đây.

            </p>

        </div>

    </section>


    <section class="history-summary-grid">

        <div
            class="history-summary-card"
            data-reveal
            data-tilt
        >

            <div class="history-summary-icon">

                <i class="bi bi-clock-history"></i>

            </div>


            <div class="history-summary-label">
                Lần bảo dưỡng
            </div>


            <div class="history-summary-value">
                {{ $totalMaintenance }}
            </div>

        </div>


        <div
            class="
                history-summary-card
                green
            "
            data-reveal
            data-tilt
        >

            <div class="history-summary-icon">

                <i class="bi bi-check2-circle"></i>

            </div>


            <div class="history-summary-label">
                Hạng mục hoàn thành
            </div>


            <div class="history-summary-value">
                {{ $totalItems }}
            </div>

        </div>


        <div
            class="
                history-summary-card
                purple
            "
            data-reveal
            data-tilt
        >

            <div class="history-summary-icon">

                <i class="bi bi-wallet2"></i>

            </div>


            <div class="history-summary-label">
                Tổng chi phí
            </div>


            <div class="history-summary-value">

                {{
                    number_format(
                        $totalAmount,
                        0,
                        ',',
                        '.'
                    )
                }} đ

            </div>

        </div>

    </section>


    @if ($serviceOrders->isEmpty())

        <div
            class="empty-state"
            data-reveal="zoom"
        >

            <div class="empty-state-icon">

                <i class="bi bi-tools"></i>

            </div>


            <h3>
                Chưa có lịch sử bảo dưỡng
            </h3>


            <p>

                Các phiếu bảo dưỡng đã hoàn thành
                sẽ tự động xuất hiện tại đây.

            </p>


            <a
                href="{{ route('appointments.create') }}"
                class="
                    btn
                    btn-primary
                    btn-shine
                    px-4
                "
            >

                <i class="bi bi-calendar-plus me-2"></i>

                Đặt lịch bảo dưỡng

            </a>

        </div>

    @else

        <div class="history-timeline">

            @foreach ($serviceOrders as $serviceOrder)

                <div class="history-entry">

                    <article
                        class="history-card"
                        data-reveal
                    >

                        <div class="history-card-top">

                            <div>

                                <div class="history-code">

                                    <i class="bi bi-hash"></i>

                                    {{ $serviceOrder->order_code }}

                                </div>


                                <div class="history-vehicle">

                                    {{ $serviceOrder->vehicle->brand->name }}

                                    {{ $serviceOrder->vehicle->vehicleModel->name }}

                                </div>


                                <div class="history-license">

                                    <i class="bi bi-car-front-fill"></i>

                                    {{ $serviceOrder->vehicle->license_plate }}

                                </div>

                            </div>


                            <span
                                class="
                                    status-badge
                                    status-completed
                                "
                            >
                                Hoàn thành
                            </span>

                        </div>


                        <div class="history-info-grid">

                            <div class="history-info-box">

                                <div class="history-info-icon">

                                    <i class="bi bi-calendar-check"></i>

                                </div>


                                <div class="history-info-label">
                                    Hoàn thành
                                </div>


                                <div class="history-info-value">

                                    {{
                                        $serviceOrder->completed_at
                                            ? $serviceOrder
                                                ->completed_at
                                                ->format('d/m/Y H:i')
                                            : 'Chưa cập nhật'
                                    }}

                                </div>

                            </div>


                            <div class="history-info-box">

                                <div class="history-info-icon">

                                    <i class="bi bi-speedometer2"></i>

                                </div>


                                <div class="history-info-label">
                                    ODO bảo dưỡng
                                </div>


                                <div class="history-info-value">

                                    {{
                                        number_format(
                                            $serviceOrder->received_mileage
                                        )
                                    }} km

                                </div>

                            </div>


                            <div class="history-info-box">

                                <div class="history-info-icon">

                                    <i class="bi bi-list-check"></i>

                                </div>


                                <div class="history-info-label">
                                    Hạng mục
                                </div>


                                <div class="history-info-value">

                                    {{
                                        $serviceOrder
                                            ->items
                                            ->count()
                                    }}
                                    dịch vụ

                                </div>

                            </div>


                            <div class="history-info-box">

                                <div class="history-info-icon">

                                    <i class="bi bi-person-gear"></i>

                                </div>


                                <div class="history-info-label">
                                    Kỹ thuật viên
                                </div>


                                <div class="history-info-value">

                                    {{
                                        $serviceOrder
                                            ->technician
                                            ->name
                                        ?? 'Không xác định'
                                    }}

                                </div>

                            </div>


                            <div class="history-info-box">

                                <div class="history-info-icon">

                                    <i class="bi bi-cash-stack"></i>

                                </div>


                                <div class="history-info-label">
                                    Tổng chi phí
                                </div>


                                <div
                                    class="
                                        history-info-value
                                        history-total
                                    "
                                >

                                    {{
                                        number_format(
                                            $serviceOrder->total_amount,
                                            0,
                                            ',',
                                            '.'
                                        )
                                    }} đ

                                </div>

                            </div>

                        </div>


                        <div class="history-card-footer">

                            <a
                                href="{{ route(
                                    'maintenance-history.show',
                                    $serviceOrder->id
                                ) }}"
                                class="history-detail-button"
                            >

                                Xem chi tiết

                                <i class="bi bi-arrow-right"></i>

                            </a>

                        </div>

                    </article>

                </div>

            @endforeach

        </div>

    @endif


    <a
        href="{{ route('customer.dashboard') }}"
        class="history-back"
    >

        <i class="bi bi-arrow-left"></i>

        Quay lại Tổng quan

    </a>

</div>

@endsection