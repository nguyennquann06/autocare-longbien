@extends('layouts.app')


@section(
    'title',
    'Chi tiết lịch hẹn - AutoCare Long Biên'
)


@push('styles')

<style>
    .appointment-detail-page {
        max-width: 1100px;
    }

    .detail-hero {
        position: relative;
        overflow: hidden;
        padding: 30px;
        margin-bottom: 24px;
        border-radius: 25px;
        color: white;
        background:
            linear-gradient(
                120deg,
                #07111f,
                #0d3476 55%,
                #1677ff
            );
        box-shadow:
            0 25px 70px
            rgba(22, 119, 255, 0.22);
    }

    .detail-hero::before {
        content: "";
        position: absolute;
        width: 330px;
        height: 330px;
        right: -100px;
        top: -200px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, 0.40),
                transparent 70%
            );
    }

    .detail-hero-inner {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 24px;
        flex-wrap: wrap;
    }

    .detail-code {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 9px;
        color: #bfdbfe;
        font-size: 12px;
        font-weight: 750;
    }

    .detail-hero h1 {
        margin: 0;
        color: white;
        font-size:
            clamp(
                2rem,
                4vw,
                3.1rem
            );
        font-weight: 900;
        letter-spacing: -0.05em;
    }

    .detail-content-grid {
        display: grid;
        grid-template-columns:
            minmax(0, 1.35fr)
            minmax(280px, 0.65fr);
        gap: 22px;
        align-items: start;
    }

    .detail-card {
        overflow: hidden;
        background:
            rgba(255, 255, 255, 0.92);
        border:
            1px solid
            rgba(255, 255, 255, 0.88);
        border-radius: 20px;
        box-shadow:
            var(--ac-shadow);
        backdrop-filter:
            blur(16px);
    }

    .detail-card-body {
        padding: 25px;
    }

    .detail-section {
        padding: 24px 0;
        border-bottom:
            1px solid #edf1f6;
    }

    .detail-section:first-child {
        padding-top: 0;
    }

    .detail-section:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .detail-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
        color: #0f172a;
        font-size: 17px;
        font-weight: 850;
    }

    .detail-section-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        color: #2563eb;
        background:
            linear-gradient(
                135deg,
                #dbeafe,
                #ecfeff
            );
    }

    .detail-info-grid {
        display: grid;
        grid-template-columns:
            repeat(
                2,
                minmax(0, 1fr)
            );
        gap: 12px;
    }

    .detail-info {
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

    .detail-info-label {
        color: #64748b;
        font-size: 10px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .detail-info-value {
        margin-top: 5px;
        color: #0f172a;
        font-size: 13px;
        font-weight: 850;
    }

    .service-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        padding: 15px 0;
        border-bottom:
            1px solid #edf1f6;
    }

    .service-line:last-child {
        border-bottom: none;
    }

    .service-line-name {
        color: #0f172a;
        font-size: 13px;
        font-weight: 850;
    }

    .service-line-duration {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-top: 5px;
        color: #64748b;
        font-size: 11px;
    }

    .service-line-price {
        color: #1d4ed8;
        white-space: nowrap;
        font-weight: 900;
    }

    .detail-note {
        padding: 16px;
        border:
            1px solid #e4ebf3;
        border-radius: 14px;
        background:
            linear-gradient(
                135deg,
                #f8fbff,
                #f7faff
            );
        color: #475569;
        line-height: 1.7;
    }

    .detail-summary {
        position: sticky;
        top: 100px;
        overflow: hidden;
        padding: 23px;
        border-radius: 20px;
        color: white;
        background:
            linear-gradient(
                145deg,
                #07111f,
                #0d2f69 55%,
                #155bd1
            );
        box-shadow:
            0 25px 60px
            rgba(13, 47, 105, 0.25);
    }

    .detail-summary::before {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        right: -110px;
        top: -130px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(34, 211, 238, 0.30),
                transparent 70%
            );
    }

    .detail-summary-content {
        position: relative;
        z-index: 2;
    }

    .detail-summary-title {
        margin-bottom: 18px;
        color: white;
        font-size: 17px;
        font-weight: 850;
    }

    .detail-summary-row {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        padding: 12px 0;
        border-bottom:
            1px solid
            rgba(255, 255, 255, 0.10);
    }

    .detail-summary-row:last-child {
        border-bottom: none;
    }

    .detail-summary-label {
        color: #bfdbfe;
        font-size: 11px;
    }

    .detail-summary-value {
        color: white;
        text-align: right;
        font-size: 13px;
        font-weight: 850;
    }

    .cancel-trigger {
        width: 100%;
        min-height: 48px;
        margin-top: 18px;
        border:
            1px solid
            rgba(248, 113, 113, 0.40);
        border-radius: 13px;
        color: #fee2e2;
        background:
            rgba(239, 68, 68, 0.13);
        font-size: 13px;
        font-weight: 850;
        transition:
            all 0.2s ease;
    }

    .cancel-trigger:hover {
        color: white;
        border-color:
            rgba(248, 113, 113, 0.70);
        background:
            rgba(239, 68, 68, 0.30);
    }

    .detail-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 20px;
        color: #64748b;
        text-decoration: none;
        font-size: 13px;
        font-weight: 750;
    }

    .detail-back:hover {
        color: #2563eb;
    }

    .cancel-modal-icon {
        width: 58px;
        height: 58px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 17px;
        border-radius: 18px;
        color: white;
        background:
            linear-gradient(
                135deg,
                #ef4444,
                #b91c1c
            );
        box-shadow:
            0 12px 28px
            rgba(239, 68, 68, 0.26);
        font-size: 24px;
    }

    @media (max-width: 991px) {
        .detail-content-grid {
            grid-template-columns: 1fr;
        }

        .detail-summary {
            position: static;
        }
    }

    @media (max-width: 575px) {
        .detail-info-grid {
            grid-template-columns: 1fr;
        }

        .detail-hero {
            padding: 23px;
        }

        .detail-card-body {
            padding: 20px;
        }
    }
</style>

@endpush


@section('content')

<div class="container appointment-detail-page">

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


    <section
        class="detail-hero"
        data-reveal="zoom"
    >

        <div class="detail-hero-inner">

            <div>

                <div class="detail-code">

                    <i class="bi bi-hash"></i>

                    {{ $appointment->appointment_code }}

                </div>

                <h1>
                    Chi tiết lịch hẹn
                </h1>

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


    <div class="detail-content-grid">

        <article
            class="detail-card"
            data-reveal="left"
        >

            <div class="detail-card-body">

                <section class="detail-section">

                    <div class="detail-section-title">

                        <span class="detail-section-icon">

                            <i class="bi bi-car-front-fill"></i>

                        </span>

                        Thông tin lịch hẹn

                    </div>


                    <div class="detail-info-grid">

                        <div class="detail-info">

                            <div class="detail-info-label">
                                Phương tiện
                            </div>

                            <div class="detail-info-value">

                                {{ $appointment->vehicle->brand->name }}

                                {{ $appointment->vehicle->vehicleModel->name }}

                            </div>

                        </div>


                        <div class="detail-info">

                            <div class="detail-info-label">
                                Biển số
                            </div>

                            <div class="detail-info-value">
                                {{ $appointment->vehicle->license_plate }}
                            </div>

                        </div>


                        <div class="detail-info">

                            <div class="detail-info-label">
                                Ngày hẹn
                            </div>

                            <div class="detail-info-value">

                                {{
                                    $appointment
                                        ->appointment_date
                                        ->format('d/m/Y')
                                }}

                            </div>

                        </div>


                        <div class="detail-info">

                            <div class="detail-info-label">
                                Giờ hẹn
                            </div>

                            <div class="detail-info-value">

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

                    </div>

                </section>


                <section class="detail-section">

                    <div class="detail-section-title">

                        <span class="detail-section-icon">

                            <i class="bi bi-person-lines-fill"></i>

                        </span>

                        Thông tin liên hệ

                    </div>


                    <div class="detail-info-grid">

                        <div class="detail-info">

                            <div class="detail-info-label">
                                Họ tên
                            </div>

                            <div class="detail-info-value">
                                {{ $appointment->contact_name }}
                            </div>

                        </div>


                        <div class="detail-info">

                            <div class="detail-info-label">
                                Số điện thoại
                            </div>

                            <div class="detail-info-value">
                                {{ $appointment->contact_phone }}
                            </div>

                        </div>


                        <div class="detail-info">

                            <div class="detail-info-label">
                                Email
                            </div>

                            <div class="detail-info-value">

                                {{
                                    $appointment->contact_email
                                    ?? 'Không có'
                                }}

                            </div>

                        </div>

                    </div>

                </section>


                <section class="detail-section">

                    <div class="detail-section-title">

                        <span class="detail-section-icon">

                            <i class="bi bi-tools"></i>

                        </span>

                        Dịch vụ đã chọn

                    </div>


                    @foreach ($appointment->services as $service)

                        <div class="service-line">

                            <div>

                                <div class="service-line-name">
                                    {{ $service->name }}
                                </div>


                                @if ($service->pivot->duration)

                                    <div class="service-line-duration">

                                        <i class="bi bi-clock"></i>

                                        {{
                                            $service
                                                ->pivot
                                                ->duration
                                        }} phút

                                    </div>

                                @endif

                            </div>


                            <div class="service-line-price">

                                {{
                                    number_format(
                                        $service->pivot->price,
                                        0,
                                        ',',
                                        '.'
                                    )
                                }} đ

                            </div>

                        </div>

                    @endforeach

                </section>


                @if ($appointment->customer_note)

                    <section class="detail-section">

                        <div class="detail-section-title">

                            <span class="detail-section-icon">

                                <i class="bi bi-chat-left-text"></i>

                            </span>

                            Ghi chú của khách hàng

                        </div>


                        <div class="detail-note">
                            {{ $appointment->customer_note }}
                        </div>

                    </section>

                @endif


                @if ($appointment->staff_note)

                    <section class="detail-section">

                        <div class="detail-section-title">

                            <span class="detail-section-icon">

                                <i class="bi bi-chat-square-dots"></i>

                            </span>

                            Phản hồi của nhân viên

                        </div>


                        <div class="detail-note">
                            {{ $appointment->staff_note }}
                        </div>

                    </section>

                @endif

            </div>

        </article>


        <aside
            class="detail-summary"
            data-reveal="right"
        >

            <div class="detail-summary-content">

                <div class="detail-summary-title">

                    <i class="bi bi-receipt me-2"></i>

                    Tóm tắt

                </div>


                <div class="detail-summary-row">

                    <span class="detail-summary-label">
                        Trạng thái
                    </span>

                    <span class="detail-summary-value">
                        {{ $statusText }}
                    </span>

                </div>


                <div class="detail-summary-row">

                    <span class="detail-summary-label">
                        Số dịch vụ
                    </span>

                    <span class="detail-summary-value">

                        {{
                            $appointment
                                ->services
                                ->count()
                        }}

                    </span>

                </div>


                <div class="detail-summary-row">

                    <span class="detail-summary-label">
                        Giá tham khảo
                    </span>

                    <span class="detail-summary-value">

                        {{
                            number_format(
                                $appointment
                                    ->estimated_total,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </span>

                </div>


                <div class="detail-summary-row">

                    <span class="detail-summary-label">
                        Thời gian dự kiến
                    </span>

                    <span class="detail-summary-value">

                        {{
                            $appointment
                                ->estimated_duration_minutes
                        }} phút

                    </span>

                </div>


                @if ($appointment->status === 'PENDING')

                    <button
                        type="button"
                        class="cancel-trigger"
                        data-bs-toggle="modal"
                        data-bs-target="#cancelAppointmentModal"
                    >

                        <i class="bi bi-x-circle me-2"></i>

                        Hủy lịch hẹn

                    </button>

                @endif

            </div>

        </aside>

    </div>


    <a
        href="{{ route('appointments.index') }}"
        class="detail-back"
    >

        <i class="bi bi-arrow-left"></i>

        Quay lại Lịch hẹn của tôi

    </a>

</div>


@if ($appointment->status === 'PENDING')

    <div
        class="modal fade"
        id="cancelAppointmentModal"
        tabindex="-1"
        aria-labelledby="cancelAppointmentModalLabel"
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

                    <div class="cancel-modal-icon">

                        <i class="bi bi-calendar-x"></i>

                    </div>


                    <h3
                        id="cancelAppointmentModalLabel"
                        class="mb-3"
                    >
                        Hủy lịch hẹn?
                    </h3>


                    <p
                        class="
                            text-secondary
                            mb-4
                        "
                    >

                        Bạn có chắc chắn muốn hủy
                        lịch

                        <strong>
                            {{ $appointment->appointment_code }}
                        </strong>

                        không?

                        Thao tác này sẽ chuyển
                        trạng thái lịch sang
                        “Đã hủy”.

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
                            Giữ lịch hẹn
                        </button>


                        <form
                            method="POST"
                            action="{{ route(
                                'appointments.cancel',
                                $appointment->id
                            ) }}"
                        >

                            @csrf
                            @method('PATCH')


                            <button
                                type="submit"
                                class="
                                    btn
                                    btn-danger
                                    px-4
                                "
                            >

                                <i class="bi bi-x-circle me-2"></i>

                                Xác nhận hủy

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endif

@endsection