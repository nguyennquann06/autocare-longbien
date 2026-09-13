@extends('layouts.app')


@section(
    'title',
    'Tổng quan của tôi - AutoCare Long Biên'
)


@push('styles')

<style>
    .customer-dashboard {
        max-width: 1280px;
    }

    .customer-welcome {
        position: relative;
        overflow: hidden;
        padding: 36px;
        margin-bottom: 26px;
        border-radius: 28px;
        color: white;
        background:
            linear-gradient(
                120deg,
                #06101e 0%,
                #0b2c66 50%,
                #1467df 100%
            );
        box-shadow:
            0 28px 75px
            rgba(20, 103, 223, 0.24);
    }

    .customer-welcome::before {
        content: "";
        position: absolute;
        width: 380px;
        height: 380px;
        top: -220px;
        right: -110px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, 0.42),
                transparent 70%
            );
    }

    .customer-welcome::after {
        content: "";
        position: absolute;
        width: 280px;
        height: 280px;
        left: 40%;
        bottom: -220px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(124, 58, 237, 0.34),
                transparent 70%
            );
    }

    .customer-welcome-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 28px;
        flex-wrap: wrap;
    }

    .welcome-chip {
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

    .welcome-chip-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #67e8f9;
        box-shadow:
            0 0 12px #67e8f9;
    }

    .customer-welcome h1 {
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

    .customer-welcome p {
        max-width: 680px;
        margin: 12px 0 0;
        color: #cbd5e1;
        line-height: 1.8;
    }

    .welcome-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .welcome-primary,
    .welcome-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 16px;
        border-radius: 13px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 850;
        transition:
            transform 0.22s ease,
            box-shadow 0.22s ease;
    }

    .welcome-primary {
        color: #07111f;
        background:
            linear-gradient(
                135deg,
                #67e8f9,
                #bfdbfe
            );
        box-shadow:
            0 12px 30px
            rgba(103, 232, 249, 0.20);
    }

    .welcome-secondary {
        color: white;
        border:
            1px solid
            rgba(255, 255, 255, 0.16);
        background:
            rgba(255, 255, 255, 0.08);
    }

    .welcome-primary:hover,
    .welcome-secondary:hover {
        transform: translateY(-3px);
    }

    .dashboard-stats {
        display: grid;
        grid-template-columns:
            repeat(
                5,
                minmax(0, 1fr)
            );
        gap: 16px;
        margin-bottom: 28px;
    }

    .dashboard-stat {
        position: relative;
        overflow: hidden;
        min-height: 155px;
        padding: 20px;
        border:
            1px solid
            rgba(255, 255, 255, 0.88);
        border-radius: 19px;
        background:
            rgba(255, 255, 255, 0.91);
        box-shadow:
            var(--ac-shadow);
        backdrop-filter: blur(16px);
        transition:
            transform 0.23s ease,
            box-shadow 0.23s ease;
    }

    .dashboard-stat:hover {
        transform: translateY(-6px);
        box-shadow:
            var(--ac-shadow-lg);
    }

    .dashboard-stat::after {
        content: "";
        position: absolute;
        width: 110px;
        height: 110px;
        right: -45px;
        bottom: -50px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(59, 130, 246, 0.14),
                transparent 70%
            );
    }

    .dashboard-stat-icon {
        width: 46px;
        height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
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

    .dashboard-stat.green
    .dashboard-stat-icon {
        color: #059669;
        background:
            linear-gradient(
                135deg,
                #d1fae5,
                #ecfdf5
            );
    }

    .dashboard-stat.orange
    .dashboard-stat-icon {
        color: #d97706;
        background:
            linear-gradient(
                135deg,
                #ffedd5,
                #fff7ed
            );
    }

    .dashboard-stat.purple
    .dashboard-stat-icon {
        color: #7c3aed;
        background:
            linear-gradient(
                135deg,
                #ede9fe,
                #f5f3ff
            );
    }

    .dashboard-stat.red
    .dashboard-stat-icon {
        color: #dc2626;
        background:
            linear-gradient(
                135deg,
                #fee2e2,
                #fef2f2
            );
    }

    .dashboard-stat-label {
        margin-top: 15px;
        color: #64748b;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .dashboard-stat-value {
        position: relative;
        z-index: 2;
        margin-top: 6px;
        color: #0f172a;
        font-size: 25px;
        font-weight: 900;
        letter-spacing: -0.04em;
    }

    .dashboard-panel {
        position: relative;
        overflow: hidden;
        margin-bottom: 22px;
        border:
            1px solid
            rgba(255, 255, 255, 0.88);
        border-radius: 21px;
        background:
            rgba(255, 255, 255, 0.92);
        box-shadow:
            var(--ac-shadow);
        backdrop-filter: blur(16px);
    }

    .dashboard-panel-body {
        padding: 25px;
    }

    .dashboard-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .dashboard-panel-title {
        display: flex;
        align-items: center;
        gap: 11px;
        margin: 0;
        color: #0f172a;
        font-size: 18px;
        font-weight: 900;
    }

    .dashboard-panel-icon {
        width: 38px;
        height: 38px;
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

    .dashboard-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #2563eb;
        text-decoration: none;
        font-size: 12px;
        font-weight: 850;
    }

    .dashboard-link:hover {
        color: #1d4ed8;
    }

    .vehicle-grid {
        display: grid;
        grid-template-columns:
            repeat(
                3,
                minmax(0, 1fr)
            );
        gap: 14px;
    }

    .vehicle-card {
        position: relative;
        overflow: hidden;
        padding: 18px;
        border:
            1px solid #e7edf4;
        border-radius: 16px;
        background:
            linear-gradient(
                145deg,
                #f8fbff,
                #f3f7fb
            );
        transition:
            transform 0.22s ease,
            border-color 0.22s ease,
            box-shadow 0.22s ease;
    }

    .vehicle-card:hover {
        transform: translateY(-4px);
        border-color: #93c5fd;
        box-shadow:
            0 13px 30px
            rgba(37, 99, 235, 0.10);
    }

    .vehicle-card-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 13px;
        border-radius: 13px;
        color: #2563eb;
        background: white;
        box-shadow:
            0 7px 18px
            rgba(15, 23, 42, 0.08);
        font-size: 19px;
    }

    .vehicle-name {
        color: #0f172a;
        font-size: 15px;
        font-weight: 900;
    }

    .vehicle-plate {
        margin-top: 5px;
        color: #64748b;
        font-size: 12px;
    }

    .vehicle-odo {
        margin-top: 13px;
        color: #475569;
        font-size: 12px;
    }

    .dashboard-record {
        padding: 16px 0;
        border-bottom:
            1px solid #edf1f6;
    }

    .dashboard-record:last-child {
        padding-bottom: 0;
        border-bottom: none;
    }

    .dashboard-record-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 15px;
        flex-wrap: wrap;
    }

    .dashboard-record-title {
        color: #0f172a;
        font-size: 14px;
        font-weight: 900;
    }

    .dashboard-record-meta {
        margin-top: 5px;
        color: #64748b;
        font-size: 12px;
        line-height: 1.7;
    }

    .recommendation-card {
        position: relative;
        overflow: hidden;
        padding: 18px;
        margin-bottom: 13px;
        border:
            1px solid #e5ecf4;
        border-radius: 16px;
        background:
            linear-gradient(
                135deg,
                #f8fbff,
                #f7faff
            );
        transition:
            transform 0.22s ease,
            border-color 0.22s ease;
    }

    .recommendation-card:hover {
        transform: translateY(-3px);
        border-color: #bfdbfe;
    }

    .recommendation-card:last-child {
        margin-bottom: 0;
    }

    .recommendation-top {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
    }

    .recommendation-name {
        color: #0f172a;
        font-size: 15px;
        font-weight: 900;
    }

    .recommendation-info {
        margin-top: 10px;
        color: #475569;
        font-size: 12px;
        line-height: 1.8;
    }

    .dashboard-empty {
        padding: 24px;
        border:
            1px dashed #cbd5e1;
        border-radius: 15px;
        color: #64748b;
        background: #f8fbff;
        text-align: center;
        line-height: 1.7;
    }

    .quick-action-grid {
        display: grid;
        grid-template-columns:
            repeat(
                4,
                minmax(0, 1fr)
            );
        gap: 14px;
    }

    .quick-action-card {
        position: relative;
        overflow: hidden;
        min-height: 150px;
        padding: 20px;
        color: white;
        text-decoration: none;
        border-radius: 17px;
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
            transform 0.23s ease,
            box-shadow 0.23s ease;
    }

    .quick-action-card:hover {
        color: white;
        transform: translateY(-6px);
        box-shadow:
            0 25px 55px
            rgba(13, 52, 118, 0.27);
    }

    .quick-action-icon {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 18px;
        border-radius: 14px;
        color: #67e8f9;
        background:
            rgba(255, 255, 255, 0.09);
        font-size: 20px;
    }

    .quick-action-title {
        font-size: 14px;
        font-weight: 900;
    }

    .quick-action-description {
        margin-top: 7px;
        color: #bfdbfe;
        font-size: 11px;
        line-height: 1.6;
    }

    @media (max-width: 1199px) {
        .dashboard-stats {
            grid-template-columns:
                repeat(
                    3,
                    minmax(0, 1fr)
                );
        }

        .quick-action-grid {
            grid-template-columns:
                repeat(
                    2,
                    minmax(0, 1fr)
                );
        }
    }

    @media (max-width: 767px) {
        .dashboard-stats {
            grid-template-columns:
                repeat(
                    2,
                    minmax(0, 1fr)
                );
        }

        .vehicle-grid {
            grid-template-columns: 1fr;
        }

        .customer-welcome {
            padding: 25px;
        }
    }

    @media (max-width: 575px) {
        .dashboard-stats,
        .quick-action-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@endpush


@section('content')

<div class="container customer-dashboard">

    <section
        class="customer-welcome"
        data-reveal="zoom"
    >

        <div class="customer-welcome-content">

            <div>

                <div class="welcome-chip">

                    <span class="welcome-chip-dot"></span>

                    Customer Dashboard

                </div>


                <h1>
                    Xin chào,
                    {{ $customer->full_name }}
                </h1>


                <p>

                    Mọi thông tin về phương tiện,
                    lịch bảo dưỡng, gợi ý chăm sóc
                    và hóa đơn của bạn đều được
                    tổng hợp tại đây.

                </p>

            </div>


            <div class="welcome-actions">

                <a
                    href="{{ route('appointments.create') }}"
                    class="welcome-primary"
                >

                    <i class="bi bi-calendar-plus"></i>

                    Đặt lịch ngay

                </a>


                <a
                    href="{{ route('vehicles.index') }}"
                    class="welcome-secondary"
                >

                    <i class="bi bi-car-front"></i>

                    Xe của tôi

                </a>

            </div>

        </div>

    </section>


    <section class="dashboard-stats">

        <div
            class="dashboard-stat"
            data-reveal
            data-tilt
        >

            <div class="dashboard-stat-icon">
                <i class="bi bi-car-front-fill"></i>
            </div>

            <div class="dashboard-stat-label">
                Xe đang quản lý
            </div>

            <div class="dashboard-stat-value">
                {{ $vehicles->count() }}
            </div>

        </div>


        <div
            class="dashboard-stat green"
            data-reveal
            data-tilt
        >

            <div class="dashboard-stat-icon">
                <i class="bi bi-calendar2-check"></i>
            </div>

            <div class="dashboard-stat-label">
                Lịch hẹn sắp tới
            </div>

            <div class="dashboard-stat-value">
                {{ $upcomingAppointments->count() }}
            </div>

        </div>


        <div
            class="dashboard-stat purple"
            data-reveal
            data-tilt
        >

            <div class="dashboard-stat-icon">
                <i class="bi bi-tools"></i>
            </div>

            <div class="dashboard-stat-label">
                Bảo dưỡng gần đây
            </div>

            <div class="dashboard-stat-value">
                {{ $recentMaintenance->count() }}
            </div>

        </div>


        <div
            class="
                dashboard-stat
                orange
            "
            data-reveal
            data-tilt
        >

            <div class="dashboard-stat-icon">
                <i class="bi bi-receipt"></i>
            </div>

            <div class="dashboard-stat-label">
                Hóa đơn chưa thanh toán
            </div>

            <div class="dashboard-stat-value">
                {{ $unpaidInvoiceCount }}
            </div>

        </div>


        <div
            class="
                dashboard-stat
                red
            "
            data-reveal
            data-tilt
        >

            <div class="dashboard-stat-icon">
                <i class="bi bi-wallet2"></i>
            </div>

            <div class="dashboard-stat-label">
                Đang chờ thanh toán
            </div>

            <div class="dashboard-stat-value">

                {{
                    number_format(
                        $unpaidInvoiceAmount,
                        0,
                        ',',
                        '.'
                    )
                }} đ

            </div>

        </div>

    </section>


    <div class="row g-4">

        <div class="col-lg-6">

            <section
                class="dashboard-panel h-100"
                data-reveal="left"
            >

                <div class="dashboard-panel-body">

                    <div class="dashboard-panel-header">

                        <h2 class="dashboard-panel-title">

                            <span class="dashboard-panel-icon">
                                <i class="bi bi-car-front-fill"></i>
                            </span>

                            Xe của tôi

                        </h2>


                        <a
                            href="{{ route('vehicles.index') }}"
                            class="dashboard-link"
                        >
                            Xem tất cả
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </div>


                    @if ($vehicles->isEmpty())

                        <div class="dashboard-empty">

                            <i
                                class="
                                    bi
                                    bi-car-front
                                    fs-2
                                    d-block
                                    mb-2
                                "
                            ></i>

                            Bạn chưa thêm phương tiện nào.

                        </div>

                    @else

                        <div class="vehicle-grid">

                            @foreach ($vehicles as $vehicle)

                                <div class="vehicle-card">

                                    <div class="vehicle-card-icon">
                                        <i class="bi bi-car-front-fill"></i>
                                    </div>

                                    <div class="vehicle-name">

                                        {{ $vehicle->brand->name }}

                                        {{ $vehicle->vehicleModel->name }}

                                    </div>

                                    <div class="vehicle-plate">

                                        <i class="bi bi-credit-card-2-front me-1"></i>

                                        {{ $vehicle->license_plate }}

                                    </div>

                                    <div class="vehicle-odo">

                                        ODO:

                                        <strong>

                                            {{
                                                number_format(
                                                    $vehicle->current_mileage
                                                )
                                            }} km

                                        </strong>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @endif

                </div>

            </section>

        </div>


        <div class="col-lg-6">

            <section
                class="dashboard-panel h-100"
                data-reveal="right"
            >

                <div class="dashboard-panel-body">

                    <div class="dashboard-panel-header">

                        <h2 class="dashboard-panel-title">

                            <span class="dashboard-panel-icon">
                                <i class="bi bi-calendar-event"></i>
                            </span>

                            Lịch hẹn sắp tới

                        </h2>


                        <a
                            href="{{ route('appointments.index') }}"
                            class="dashboard-link"
                        >
                            Xem lịch hẹn
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </div>


                    @forelse ($upcomingAppointments as $appointment)

                        <div class="dashboard-record">

                            <div class="dashboard-record-top">

                                <div>

                                    <div class="dashboard-record-title">
                                        {{ $appointment->appointment_code }}
                                    </div>

                                    <div class="dashboard-record-meta">

                                        <i class="bi bi-car-front me-1"></i>

                                        {{ $appointment->vehicle->brand->name }}

                                        {{ $appointment->vehicle->vehicleModel->name }}

                                        -

                                        {{ $appointment->vehicle->license_plate }}

                                        <br>

                                        <i class="bi bi-calendar3 me-1"></i>

                                        {{
                                            $appointment
                                                ->appointment_date
                                                ->format('d/m/Y')
                                        }}

                                        lúc

                                        {{
                                            substr(
                                                $appointment
                                                    ->appointment_time,
                                                0,
                                                5
                                            )
                                        }}

                                    </div>

                                </div>


                                <span
                                    class="
                                        status-badge
                                        {{
                                            $appointment->status
                                            === 'CONFIRMED'
                                                ? 'status-confirmed'
                                                : 'status-pending'
                                        }}
                                    "
                                >

                                    {{
                                        $appointment->status
                                        === 'CONFIRMED'
                                            ? 'Đã xác nhận'
                                            : 'Chờ xác nhận'
                                    }}

                                </span>

                            </div>

                        </div>

                    @empty

                        <div class="dashboard-empty">

                            <i
                                class="
                                    bi
                                    bi-calendar2-x
                                    fs-2
                                    d-block
                                    mb-2
                                "
                            ></i>

                            Bạn chưa có lịch hẹn sắp tới.

                            <br><br>

                            <a
                                href="{{ route('appointments.create') }}"
                                class="dashboard-link"
                            >
                                Đặt lịch bảo dưỡng
                                <i class="bi bi-arrow-right"></i>
                            </a>

                        </div>

                    @endforelse

                </div>

            </section>

        </div>

    </div>


    <section
        class="dashboard-panel mt-4"
        data-reveal
    >

        <div class="dashboard-panel-body">

            <div class="dashboard-panel-header">

                <h2 class="dashboard-panel-title">

                    <span class="dashboard-panel-icon">
                        <i class="bi bi-stars"></i>
                    </span>

                    Gợi ý bảo dưỡng tiếp theo

                </h2>


                <a
                    href="{{ route('services.index') }}"
                    class="dashboard-link"
                >
                    Xem dịch vụ
                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>


            @forelse (
                $maintenanceRecommendations
                as $recommendation
            )

                <div class="recommendation-card">

                    <div class="recommendation-top">

                        <div>

                            <div class="recommendation-name">

                                {{
                                    $recommendation[
                                        'service'
                                    ]->name
                                }}

                            </div>


                            <div class="dashboard-record-meta">

                                <i class="bi bi-car-front me-1"></i>

                                {{
                                    $recommendation[
                                        'vehicle'
                                    ]->brand->name
                                }}

                                {{
                                    $recommendation[
                                        'vehicle'
                                    ]->vehicleModel->name
                                }}

                                -

                                {{
                                    $recommendation[
                                        'vehicle'
                                    ]->license_plate
                                }}

                            </div>

                        </div>


                        <span
                            class="
                                status-badge
                                {{
                                    $recommendation['is_due']
                                        ? 'status-cancelled'
                                        : 'status-confirmed'
                                }}
                            "
                        >

                            {{
                                $recommendation['is_due']
                                    ? 'Đến hạn kiểm tra'
                                    : 'Sắp tới'
                            }}

                        </span>

                    </div>


                    <div class="recommendation-info">

                        Lần gần nhất:

                        <strong>

                            {{
                                $recommendation[
                                    'last_order'
                                ]->completed_at
                                    ? $recommendation[
                                        'last_order'
                                    ]->completed_at
                                        ->format('d/m/Y')
                                    : 'Không xác định'
                            }}

                        </strong>

                        tại

                        <strong>

                            {{
                                number_format(
                                    $recommendation[
                                        'last_order'
                                    ]->received_mileage
                                )
                            }} km

                        </strong>


                        @if (
                            $recommendation[
                                'next_mileage'
                            ] !== null
                        )

                            <br>

                            Mốc ODO tiếp theo:

                            <strong>

                                {{
                                    number_format(
                                        $recommendation[
                                            'next_mileage'
                                        ]
                                    )
                                }} km

                            </strong>


                            @if (
                                !$recommendation[
                                    'due_by_mileage'
                                ]
                                &&
                                $recommendation[
                                    'remaining_mileage'
                                ] !== null
                            )

                                — còn khoảng

                                <strong>

                                    {{
                                        number_format(
                                            $recommendation[
                                                'remaining_mileage'
                                            ]
                                        )
                                    }} km

                                </strong>

                            @endif

                        @endif


                        @if (
                            $recommendation[
                                'next_date'
                            ]
                        )

                            <br>

                            Mốc thời gian tiếp theo:

                            <strong>

                                {{
                                    $recommendation[
                                        'next_date'
                                    ]->format('d/m/Y')
                                }}

                            </strong>

                        @endif

                    </div>

                </div>

            @empty

                <div class="dashboard-empty">

                    <i
                        class="
                            bi
                            bi-stars
                            fs-2
                            d-block
                            mb-2
                        "
                    ></i>

                    Chưa có đủ lịch sử bảo dưỡng
                    để tính gợi ý tiếp theo.

                    <br>

                    Khi có các lần bảo dưỡng hoàn thành,
                    hệ thống sẽ dựa trên chu kỳ km/tháng
                    của từng dịch vụ.

                </div>

            @endforelse

        </div>

    </section>


    <div class="row g-4">

        <div class="col-lg-6">

            <section
                class="dashboard-panel h-100"
                data-reveal="left"
            >

                <div class="dashboard-panel-body">

                    <div class="dashboard-panel-header">

                        <h2 class="dashboard-panel-title">

                            <span class="dashboard-panel-icon">
                                <i class="bi bi-clock-history"></i>
                            </span>

                            Bảo dưỡng gần đây

                        </h2>


                        <a
                            href="{{ route('maintenance-history.index') }}"
                            class="dashboard-link"
                        >
                            Xem lịch sử
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </div>


                    @forelse ($recentMaintenance as $order)

                        <div class="dashboard-record">

                            <div class="dashboard-record-title">
                                {{ $order->order_code }}
                            </div>

                            <div class="dashboard-record-meta">

                                {{ $order->vehicle->brand->name }}

                                {{ $order->vehicle->vehicleModel->name }}

                                -

                                {{ $order->vehicle->license_plate }}

                                <br>

                                Hoàn thành:

                                {{
                                    $order->completed_at
                                        ? $order
                                            ->completed_at
                                            ->format('d/m/Y H:i')
                                        : 'Chưa cập nhật'
                                }}

                                · ODO:

                                {{
                                    number_format(
                                        $order->received_mileage
                                    )
                                }} km

                                · {{ $order->items->count() }} hạng mục

                            </div>

                        </div>

                    @empty

                        <div class="dashboard-empty">
                            Chưa có lịch sử bảo dưỡng.
                        </div>

                    @endforelse

                </div>

            </section>

        </div>


        <div class="col-lg-6">

            <section
                class="dashboard-panel h-100"
                data-reveal="right"
            >

                <div class="dashboard-panel-body">

                    <div class="dashboard-panel-header">

                        <h2 class="dashboard-panel-title">

                            <span class="dashboard-panel-icon">
                                <i class="bi bi-receipt-cutoff"></i>
                            </span>

                            Hóa đơn gần đây

                        </h2>


                        <a
                            href="{{ route('customer.invoices.index') }}"
                            class="dashboard-link"
                        >
                            Xem hóa đơn
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </div>


                    @forelse ($recentInvoices as $invoice)

                        <div class="dashboard-record">

                            <div class="dashboard-record-top">

                                <div>

                                    <div class="dashboard-record-title">
                                        {{ $invoice->invoice_code }}
                                    </div>

                                    <div class="dashboard-record-meta">

                                        {{
                                            $invoice
                                                ->serviceOrder
                                                ->vehicle
                                                ->license_plate
                                        }}

                                        ·

                                        {{
                                            $invoice
                                                ->issued_at
                                                ->format('d/m/Y')
                                        }}

                                        <br>

                                        Tổng tiền:

                                        <strong>

                                            {{
                                                number_format(
                                                    $invoice->total_amount,
                                                    0,
                                                    ',',
                                                    '.'
                                                )
                                            }} đ

                                        </strong>

                                    </div>

                                </div>


                                <span
                                    class="
                                        status-badge
                                        {{
                                            $invoice->payment_status
                                            === 'PAID'
                                                ? 'status-paid'
                                                : 'status-unpaid'
                                        }}
                                    "
                                >

                                    {{
                                        $invoice->payment_status
                                        === 'PAID'
                                            ? 'Đã thanh toán'
                                            : 'Chưa thanh toán'
                                    }}

                                </span>

                            </div>

                        </div>

                    @empty

                        <div class="dashboard-empty">
                            Chưa có hóa đơn.
                        </div>

                    @endforelse

                </div>

            </section>

        </div>

    </div>


    <section
        class="dashboard-panel mt-4"
        data-reveal
    >

        <div class="dashboard-panel-body">

            <div class="dashboard-panel-header">

                <h2 class="dashboard-panel-title">

                    <span class="dashboard-panel-icon">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </span>

                    Truy cập nhanh

                </h2>

            </div>


            <div class="quick-action-grid">

                <a
                    href="{{ route('appointments.create') }}"
                    class="quick-action-card"
                >

                    <div class="quick-action-icon">
                        <i class="bi bi-calendar-plus"></i>
                    </div>

                    <div class="quick-action-title">
                        Đặt lịch
                    </div>

                    <div class="quick-action-description">
                        Chọn xe, dịch vụ và
                        thời gian bảo dưỡng.
                    </div>

                </a>


                <a
                    href="{{ route('vehicles.index') }}"
                    class="quick-action-card"
                >

                    <div class="quick-action-icon">
                        <i class="bi bi-car-front-fill"></i>
                    </div>

                    <div class="quick-action-title">
                        Quản lý xe
                    </div>

                    <div class="quick-action-description">
                        Cập nhật phương tiện
                        và số km hiện tại.
                    </div>

                </a>


                <a
                    href="{{ route('maintenance-history.index') }}"
                    class="quick-action-card"
                >

                    <div class="quick-action-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <div class="quick-action-title">
                        Lịch sử bảo dưỡng
                    </div>

                    <div class="quick-action-description">
                        Xem lại toàn bộ
                        quá trình chăm sóc xe.
                    </div>

                </a>


                <a
                    href="{{ route('customer.invoices.index') }}"
                    class="quick-action-card"
                >

                    <div class="quick-action-icon">
                        <i class="bi bi-receipt"></i>
                    </div>

                    <div class="quick-action-title">
                        Hóa đơn
                    </div>

                    <div class="quick-action-description">
                        Theo dõi chi phí và
                        trạng thái thanh toán.
                    </div>

                </a>

            </div>

        </div>

    </section>

</div>

@endsection