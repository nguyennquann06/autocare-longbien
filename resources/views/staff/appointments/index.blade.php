@extends('layouts.app')


@section(
    'title',
    'Quản lý lịch hẹn - AutoCare Long Biên'
)


@push('styles')

<style>
    .staff-appointments-page {
        max-width: 1220px;
    }

    .staff-page-hero {
        position: relative;
        overflow: hidden;
        padding: 32px;
        margin-bottom: 26px;
        border-radius: 26px;
        color: white;
        background:
            linear-gradient(
                120deg,
                #06101e,
                #0c3474 52%,
                #1677ff
            );
        box-shadow:
            0 25px 70px
            rgba(22, 119, 255, .22);
    }

    .staff-page-hero::before {
        content: "";
        position: absolute;
        width: 340px;
        height: 340px;
        right: -100px;
        top: -200px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, .4),
                transparent 70%
            );
    }

    .staff-page-hero-content {
        position: relative;
        z-index: 2;
    }

    .staff-page-hero h1 {
        margin: 0;
        color: white;
        font-size:
            clamp(2rem, 4vw, 3.3rem);
        font-weight: 900;
        letter-spacing: -.055em;
    }

    .staff-page-hero p {
        max-width: 680px;
        margin: 11px 0 0;
        color: #cbd5e1;
        line-height: 1.75;
    }

    .staff-appointment-card {
        position: relative;
        overflow: hidden;
        margin-bottom: 18px;
        padding: 24px;
        border:
            1px solid
            rgba(255, 255, 255, .88);
        border-radius: 20px;
        background:
            rgba(255, 255, 255, .92);
        box-shadow: var(--ac-shadow);
        backdrop-filter: blur(16px);
        transition:
            transform .23s ease,
            box-shadow .23s ease;
    }

    .staff-appointment-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--ac-shadow-lg);
    }

    .staff-appointment-top {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .staff-code {
        color: #64748b;
        font-size: 11px;
        font-weight: 800;
    }

    .staff-customer {
        margin-top: 7px;
        color: #0f172a;
        font-size: 20px;
        font-weight: 900;
    }

    .staff-vehicle {
        margin-top: 5px;
        color: #64748b;
        font-size: 12px;
    }

    .staff-info-grid {
        display: grid;
        grid-template-columns:
            repeat(5, minmax(0, 1fr));
        gap: 12px;
        margin-top: 20px;
    }

    .staff-info-box {
        padding: 14px;
        border: 1px solid #e7edf4;
        border-radius: 14px;
        background:
            linear-gradient(
                180deg,
                #f8fbff,
                #f5f8fc
            );
    }

    .staff-info-icon {
        margin-bottom: 7px;
        color: #2563eb;
        font-size: 17px;
    }

    .staff-info-label {
        color: #64748b;
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .staff-info-value {
        margin-top: 4px;
        color: #0f172a;
        font-size: 12px;
        font-weight: 850;
    }

    .staff-card-footer {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
    }

    .staff-action {
        display: inline-flex;
        align-items: center;
        gap: 7px;
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
            rgba(37, 99, 235, .2);
    }

    .staff-action:hover {
        color: white;
        transform: translateY(-2px);
    }

    .staff-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 10px;
        color: #64748b;
        text-decoration: none;
        font-size: 12px;
        font-weight: 750;
    }

    @media (max-width: 1100px) {
        .staff-info-grid {
            grid-template-columns:
                repeat(3, 1fr);
        }
    }

    @media (max-width: 767px) {
        .staff-info-grid {
            grid-template-columns:
                repeat(2, 1fr);
        }
    }

    @media (max-width: 575px) {
        .staff-info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@endpush


@section('content')

<div class="container staff-appointments-page">

    <section class="staff-page-hero" data-reveal="zoom">

        <div class="staff-page-hero-content">

            <h1>
                Quản lý lịch hẹn
            </h1>

            <p>
                Theo dõi và xử lý các yêu cầu
                bảo dưỡng do khách hàng gửi
                tới AutoCare Long Biên.
            </p>

        </div>

    </section>


    @if ($appointments->isEmpty())

        <div class="empty-state" data-reveal="zoom">

            <div class="empty-state-icon">
                <i class="bi bi-calendar2-x"></i>
            </div>

            <h3>
                Chưa có lịch hẹn
            </h3>

            <p>
                Hiện chưa có yêu cầu đặt lịch
                nào trong hệ thống.
            </p>

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
                class="staff-appointment-card"
                data-reveal
            >

                <div class="staff-appointment-top">

                    <div>

                        <div class="staff-code">
                            <i class="bi bi-hash"></i>
                            {{ $appointment->appointment_code }}
                        </div>

                        <div class="staff-customer">
                            {{ $appointment->contact_name }}
                        </div>

                        <div class="staff-vehicle">

                            <i class="bi bi-car-front-fill me-1"></i>

                            {{ $appointment->vehicle->brand->name }}

                            {{ $appointment->vehicle->vehicleModel->name }}

                            -

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


                <div class="staff-info-grid">

                    <div class="staff-info-box">

                        <div class="staff-info-icon">
                            <i class="bi bi-calendar3"></i>
                        </div>

                        <div class="staff-info-label">
                            Ngày hẹn
                        </div>

                        <div class="staff-info-value">

                            {{
                                $appointment
                                    ->appointment_date
                                    ->format('d/m/Y')
                            }}

                        </div>

                    </div>


                    <div class="staff-info-box">

                        <div class="staff-info-icon">
                            <i class="bi bi-clock"></i>
                        </div>

                        <div class="staff-info-label">
                            Giờ
                        </div>

                        <div class="staff-info-value">

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


                    <div class="staff-info-box">

                        <div class="staff-info-icon">
                            <i class="bi bi-telephone"></i>
                        </div>

                        <div class="staff-info-label">
                            Điện thoại
                        </div>

                        <div class="staff-info-value">
                            {{ $appointment->contact_phone }}
                        </div>

                    </div>


                    <div class="staff-info-box">

                        <div class="staff-info-icon">
                            <i class="bi bi-tools"></i>
                        </div>

                        <div class="staff-info-label">
                            Dịch vụ
                        </div>

                        <div class="staff-info-value">

                            {{
                                $appointment
                                    ->services
                                    ->count()
                            }}

                        </div>

                    </div>


                    <div class="staff-info-box">

                        <div class="staff-info-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>

                        <div class="staff-info-label">
                            Giá tham khảo
                        </div>

                        <div class="staff-info-value">

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


                <div class="staff-card-footer">

                    <a
                        href="{{ route(
                            'staff.appointments.show',
                            $appointment->id
                        ) }}"
                        class="staff-action"
                    >
                        Xem và xử lý
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </article>

        @endforeach

    @endif


    <a
        href="{{ route('staff.dashboard') }}"
        class="staff-back"
    >
        <i class="bi bi-arrow-left"></i>
        Quay lại Dashboard
    </a>

</div>

@endsection