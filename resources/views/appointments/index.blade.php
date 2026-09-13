@extends('layouts.app')


@section(
    'title',
    'Lịch hẹn của tôi - AutoCare Long Biên'
)


@push('styles')

<style>
    .appointments-page {
        max-width: 1200px;
    }

    .appointments-hero {
        position: relative;
        overflow: hidden;
        padding: 30px;
        margin-bottom: 28px;
        border-radius: 26px;
        color: white;
        background:
            linear-gradient(
                120deg,
                #07111f,
                #0d3476 55%,
                #1677ff
            );
        box-shadow:
            0 24px 65px
            rgba(22, 119, 255, 0.22);
    }

    .appointments-hero::before {
        content: "";
        position: absolute;
        width: 320px;
        height: 320px;
        top: -190px;
        right: -100px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, 0.40),
                transparent 70%
            );
    }

    .appointments-hero-inner {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 24px;
        flex-wrap: wrap;
    }

    .appointments-hero h1 {
        margin: 0;
        color: white;
        font-size:
            clamp(
                2rem,
                4vw,
                3.2rem
            );
        font-weight: 900;
        letter-spacing: -0.055em;
    }

    .appointments-hero p {
        margin: 10px 0 0;
        color: #cbd5e1;
        line-height: 1.7;
    }

    .appointment-create-button {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 13px 18px;
        border-radius: 14px;
        color: #07111f;
        text-decoration: none;
        background:
            linear-gradient(
                135deg,
                #67e8f9,
                #bfdbfe
            );
        font-size: 13px;
        font-weight: 900;
        box-shadow:
            0 12px 30px
            rgba(103, 232, 249, 0.20);
        transition:
            all 0.23s ease;
    }

    .appointment-create-button:hover {
        color: #07111f;
        transform:
            translateY(-3px);
        box-shadow:
            0 18px 38px
            rgba(103, 232, 249, 0.30);
    }

    .appointment-card {
        position: relative;
        overflow: hidden;
        margin-bottom: 18px;
        padding: 24px;
        border:
            1px solid
            rgba(255, 255, 255, 0.88);
        border-radius: 20px;
        background:
            rgba(255, 255, 255, 0.91);
        box-shadow:
            var(--ac-shadow);
        backdrop-filter:
            blur(16px);
        transition:
            transform 0.23s ease,
            box-shadow 0.23s ease;
    }

    .appointment-card:hover {
        transform:
            translateY(-5px);
        box-shadow:
            var(--ac-shadow-lg);
    }

    .appointment-card::after {
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
                rgba(59, 130, 246, 0.12),
                transparent 72%
            );
    }

    .appointment-card-top {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        flex-wrap: wrap;
    }

    .appointment-code {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 8px;
        color: #64748b;
        font-size: 12px;
        font-weight: 750;
    }

    .appointment-vehicle {
        color: #0f172a;
        font-size: 21px;
        font-weight: 900;
        letter-spacing: -0.03em;
    }

    .appointment-license {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 7px;
        color: #64748b;
        font-size: 13px;
    }

    .appointment-info-grid {
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

    .appointment-info {
        padding: 14px;
        border:
            1px solid #e8eef5;
        border-radius: 14px;
        background:
            linear-gradient(
                180deg,
                #f8fbff,
                #f5f8fc
            );
    }

    .appointment-info-icon {
        margin-bottom: 8px;
        color: #2563eb;
        font-size: 17px;
    }

    .appointment-info-label {
        color: #64748b;
        font-size: 10px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .appointment-info-value {
        margin-top: 4px;
        color: #0f172a;
        font-size: 13px;
        font-weight: 850;
    }

    .appointment-card-footer {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
    }

    .appointment-detail-button {
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
        font-size: 12px;
        font-weight: 850;
        box-shadow:
            0 8px 22px
            rgba(37, 99, 235, 0.20);
        transition:
            all 0.2s ease;
    }

    .appointment-detail-button:hover {
        color: white;
        transform:
            translateY(-2px);
    }

    .appointments-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 12px;
        color: #64748b;
        text-decoration: none;
        font-size: 13px;
        font-weight: 750;
    }

    .appointments-back:hover {
        color: #2563eb;
    }

    @media (max-width: 991px) {
        .appointment-info-grid {
            grid-template-columns:
                repeat(
                    2,
                    minmax(0, 1fr)
                );
        }
    }

    @media (max-width: 575px) {
        .appointment-info-grid {
            grid-template-columns: 1fr;
        }

        .appointment-card {
            padding: 19px;
        }

        .appointments-hero {
            padding: 24px;
        }
    }
</style>

@endpush


@section('content')

<div class="container appointments-page">

    <section
        class="appointments-hero"
        data-reveal="zoom"
    >

        <div class="appointments-hero-inner">

            <div>

                <h1>
                    Lịch hẹn của tôi
                </h1>

                <p>
                    Theo dõi toàn bộ hành trình
                    đặt lịch và bảo dưỡng phương tiện.
                </p>

            </div>


            <a
                href="{{ route('appointments.create') }}"
                class="appointment-create-button"
            >

                <i class="bi bi-calendar-plus"></i>

                Đặt lịch mới

            </a>

        </div>

    </section>


    @if ($appointments->isEmpty())

        <div
            class="empty-state"
            data-reveal="zoom"
        >

            <div class="empty-state-icon">

                <i class="bi bi-calendar2-x"></i>

            </div>

            <h3>
                Bạn chưa có lịch hẹn
            </h3>

            <p>
                Hãy tạo lịch bảo dưỡng đầu tiên
                cho phương tiện của bạn.
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

                Đặt lịch ngay

            </a>

        </div>

    @else

        @foreach ($appointments as $appointment)

            @php
                $statusText = match (
                    $appointment->status
                ) {
                    'PENDING' =>
                        'Chờ xác nhận',

                    'CONFIRMED' =>
                        'Đã xác nhận',

                    'IN_PROGRESS' =>
                        'Đang thực hiện',

                    'COMPLETED' =>
                        'Hoàn thành',

                    'CANCELLED' =>
                        'Đã hủy',

                    default =>
                        $appointment->status,
                };


                $statusClass = match (
                    $appointment->status
                ) {
                    'PENDING' =>
                        'status-pending',

                    'CONFIRMED' =>
                        'status-confirmed',

                    'IN_PROGRESS' =>
                        'status-progress',

                    'COMPLETED' =>
                        'status-completed',

                    'CANCELLED' =>
                        'status-cancelled',

                    default =>
                        'status-pending',
                };
            @endphp


            <article
                class="appointment-card"
                data-reveal
                data-tilt
            >

                <div class="appointment-card-top">

                    <div>

                        <div class="appointment-code">

                            <i class="bi bi-hash"></i>

                            {{ $appointment->appointment_code }}

                        </div>


                        <div class="appointment-vehicle">

                            {{ $appointment->vehicle->brand->name }}

                            {{ $appointment->vehicle->vehicleModel->name }}

                        </div>


                        <div class="appointment-license">

                            <i class="bi bi-car-front-fill"></i>

                            {{ $appointment->vehicle->license_plate }}

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


                <div class="appointment-info-grid">

                    <div class="appointment-info">

                        <div class="appointment-info-icon">
                            <i class="bi bi-calendar3"></i>
                        </div>

                        <div class="appointment-info-label">
                            Ngày hẹn
                        </div>

                        <div class="appointment-info-value">

                            {{
                                $appointment
                                    ->appointment_date
                                    ->format('d/m/Y')
                            }}

                        </div>

                    </div>


                    <div class="appointment-info">

                        <div class="appointment-info-icon">
                            <i class="bi bi-clock"></i>
                        </div>

                        <div class="appointment-info-label">
                            Giờ hẹn
                        </div>

                        <div class="appointment-info-value">

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


                    <div class="appointment-info">

                        <div class="appointment-info-icon">
                            <i class="bi bi-tools"></i>
                        </div>

                        <div class="appointment-info-label">
                            Dịch vụ
                        </div>

                        <div class="appointment-info-value">

                            {{
                                $appointment
                                    ->services
                                    ->count()
                            }}
                            dịch vụ

                        </div>

                    </div>


                    <div class="appointment-info">

                        <div class="appointment-info-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>

                        <div class="appointment-info-label">
                            Giá tham khảo
                        </div>

                        <div class="appointment-info-value">

                            {{
                                number_format(
                                    $appointment
                                        ->estimated_total,
                                    0,
                                    ',',
                                    '.'
                                )
                            }} đ

                        </div>

                    </div>

                </div>


                <div class="appointment-card-footer">

                    <a
                        href="{{ route(
                            'appointments.show',
                            $appointment->id
                        ) }}"
                        class="appointment-detail-button"
                    >

                        Xem chi tiết

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </article>

        @endforeach

    @endif


    <a
        href="{{ route('customer.dashboard') }}"
        class="appointments-back"
    >

        <i class="bi bi-arrow-left"></i>

        Quay lại Tổng quan

    </a>

</div>

@endsection