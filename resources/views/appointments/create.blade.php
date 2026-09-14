@extends('layouts.app')


@section(
    'title',
    'Đặt lịch bảo dưỡng - AutoCare Long Biên'
)


@push('styles')

<style>
    .booking-page {
        max-width: 1250px;
    }

    .booking-hero {
        position: relative;
        overflow: hidden;
        padding: 32px;
        margin-bottom: 28px;
        border-radius: 26px;
        color: white;
        background:
            linear-gradient(
                120deg,
                #07111f 0%,
                #0b2f6b 48%,
                #1467df 100%
            );
        box-shadow:
            0 25px 70px
            rgba(20, 103, 223, 0.22);
    }

    .booking-hero::before {
        content: "";
        position: absolute;
        width: 340px;
        height: 340px;
        right: -120px;
        top: -190px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, 0.40),
                transparent 70%
            );
    }

    .booking-hero::after {
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        left: 38%;
        bottom: -210px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(124, 58, 237, 0.35),
                transparent 70%
            );
    }

    .booking-hero-content {
        position: relative;
        z-index: 2;
    }

    .booking-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        margin-bottom: 18px;
        border:
            1px solid
            rgba(255, 255, 255, 0.16);
        border-radius: 999px;
        color: #dbeafe;
        background:
            rgba(255, 255, 255, 0.08);
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .booking-badge-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #67e8f9;
        box-shadow:
            0 0 12px #67e8f9;
    }

    .booking-hero h1 {
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

    .booking-hero p {
        max-width: 650px;
        margin: 12px 0 0;
        color: #cbd5e1;
        line-height: 1.8;
    }

    .booking-layout {
        display: grid;
        grid-template-columns:
            minmax(0, 1fr)
            350px;
        gap: 24px;
        align-items: start;
    }

    .booking-card {
        position: relative;
        overflow: hidden;
        background:
            rgba(255, 255, 255, 0.92);
        border:
            1px solid
            rgba(255, 255, 255, 0.88);
        border-radius: 22px;
        box-shadow:
            var(--ac-shadow);
        backdrop-filter: blur(18px);
    }

    .booking-card-body {
        padding: 28px;
    }

    .booking-section {
        position: relative;
        padding: 24px 0;
        border-bottom:
            1px solid #edf1f6;
    }

    .booking-section:first-child {
        padding-top: 0;
    }

    .booking-section:last-child {
        padding-bottom: 0;
        border-bottom: none;
    }

    .booking-section-heading {
        display: flex;
        align-items: center;
        gap: 13px;
        margin-bottom: 20px;
    }

    .booking-step {
        width: 40px;
        height: 40px;
        flex: 0 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        color: white;
        background:
            linear-gradient(
                135deg,
                #1683ff,
                #4f46e5
            );
        box-shadow:
            0 9px 22px
            rgba(37, 99, 235, 0.24);
        font-weight: 900;
    }

    .booking-section-title {
        margin: 0;
        color: #0f172a;
        font-size: 18px;
        font-weight: 850;
    }

    .booking-section-description {
        margin: 3px 0 0;
        color: #64748b;
        font-size: 12px;
    }

    .booking-label {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 8px;
        color: #334155;
        font-size: 13px;
        font-weight: 800;
    }

    .booking-control {
        min-height: 49px;
    }

    .booking-control.is-invalid,
    .booking-note.is-invalid {
        border-color: #ef4444;
        box-shadow:
            0 0 0 0.2rem
            rgba(239, 68, 68, 0.08);
    }

    .service-category {
        margin-bottom: 26px;
    }

    .service-category:last-child {
        margin-bottom: 0;
    }

    .service-category-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 13px;
        color: #0f172a;
        font-size: 15px;
        font-weight: 850;
    }

    .service-category-title i {
        color: #2563eb;
    }

    .service-grid {
        display: grid;
        grid-template-columns:
            repeat(
                2,
                minmax(0, 1fr)
            );
        gap: 12px;
        padding: 3px;
        border-radius: 18px;
        transition:
            background 0.2s ease;
    }

    .service-grid.has-validation-error {
        background:
            rgba(239, 68, 68, 0.07);
    }

    .service-grid.has-validation-error
    .service-option-label {
        border-color: #fca5a5;
    }

    .service-option {
        position: relative;
    }

    .service-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .service-option-label {
        position: relative;
        overflow: hidden;
        height: 100%;
        display: block;
        padding: 17px;
        border:
            1px solid #e2e8f0;
        border-radius: 16px;
        background:
            linear-gradient(
                180deg,
                #ffffff,
                #f8fbff
            );
        cursor: pointer;
        transition:
            transform 0.23s ease,
            border-color 0.23s ease,
            box-shadow 0.23s ease,
            background 0.23s ease;
    }

    .service-option-label:hover {
        transform:
            translateY(-3px);
        border-color: #93c5fd;
        box-shadow:
            0 12px 30px
            rgba(37, 99, 235, 0.10);
    }

    .service-option input:checked
    + .service-option-label {
        border-color: #3b82f6;
        background:
            linear-gradient(
                135deg,
                #eff6ff,
                #ecfeff
            );
        box-shadow:
            0 0 0 3px
            rgba(59, 130, 246, 0.09),
            0 14px 32px
            rgba(37, 99, 235, 0.12);
    }

    .service-option input:focus-visible
    + .service-option-label {
        outline:
            3px solid
            rgba(59, 130, 246, 0.25);
        outline-offset: 2px;
    }

    .service-check {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 26px;
        height: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        border:
            1px solid #cbd5e1;
        border-radius: 8px;
        color: transparent;
        background: white;
        transition:
            all 0.2s ease;
    }

    .service-option input:checked
    + .service-option-label
    .service-check {
        color: white;
        border-color: #2563eb;
        background:
            linear-gradient(
                135deg,
                #1683ff,
                #4f46e5
            );
    }

    .service-name {
        padding-right: 35px;
        color: #0f172a;
        font-size: 14px;
        font-weight: 850;
    }

    .service-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .service-meta-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 8px;
        border-radius: 999px;
        color: #475569;
        background: #f1f5f9;
        font-size: 11px;
        font-weight: 750;
    }

    .summary-card {
        position: sticky;
        top: 100px;
        overflow: hidden;
        padding: 24px;
        color: white;
        border-radius: 22px;
        background:
            linear-gradient(
                145deg,
                #07111f,
                #0d2f69 55%,
                #155bd1
            );
        box-shadow:
            0 25px 60px
            rgba(13, 47, 105, 0.26);
    }

    .summary-card::before {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        top: -140px;
        right: -100px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(34, 211, 238, 0.32),
                transparent 70%
            );
    }

    .summary-content {
        position: relative;
        z-index: 2;
    }

    .summary-icon {
        width: 48px;
        height: 48px;
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

    .summary-title {
        margin-bottom: 20px;
        color: white;
        font-size: 18px;
        font-weight: 850;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 13px 0;
        border-bottom:
            1px solid
            rgba(255, 255, 255, 0.10);
    }

    .summary-row:last-of-type {
        border-bottom: none;
    }

    .summary-label {
        color: #bfdbfe;
        font-size: 12px;
    }

    .summary-value {
        color: white;
        text-align: right;
        font-weight: 850;
    }

    .booking-submit {
        width: 100%;
        min-height: 51px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 20px;
        border: none;
        border-radius: 14px;
        color: #07111f;
        background:
            linear-gradient(
                135deg,
                #67e8f9,
                #bfdbfe
            );
        font-weight: 900;
        box-shadow:
            0 12px 28px
            rgba(103, 232, 249, 0.20);
        transition:
            all 0.23s ease;
    }

    .booking-submit:hover:not(:disabled) {
        transform:
            translateY(-3px);
        box-shadow:
            0 18px 36px
            rgba(103, 232, 249, 0.30);
    }

    .booking-submit:disabled {
        cursor: wait;
        opacity: 0.76;
        transform: none;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 18px;
        color: #64748b;
        text-decoration: none;
        font-size: 13px;
        font-weight: 750;
    }

    .back-link:hover {
        color: #2563eb;
    }

    @media (max-width: 991px) {
        .booking-layout {
            grid-template-columns: 1fr;
        }

        .summary-card {
            position: static;
        }
    }

    @media (max-width: 767px) {
        .booking-card-body {
            padding: 20px;
        }

        .service-grid {
            grid-template-columns: 1fr;
        }

        .booking-hero {
            padding: 24px;
        }
    }
</style>

@endpush


@section('content')

<div class="container booking-page">

    @php
        $oldServices =
            array_map(
                'intval',
                (array) old(
                    'service_ids',
                    []
                )
            );

        $hasServiceError =
            $errors->has('service_ids')
            ||
            collect(
                $errors->keys()
            )->contains(
                fn ($key) =>
                    str_starts_with(
                        $key,
                        'service_ids.'
                    )
            );

        $todayHanoi =
            now(
                'Asia/Ho_Chi_Minh'
            )->toDateString();

        $currentTimeHanoi =
            now(
                'Asia/Ho_Chi_Minh'
            )->format('H:i');
    @endphp


    <section
        class="booking-hero"
        data-reveal="zoom"
    >

        <div class="booking-hero-content">

            <div class="booking-badge">

                <span class="booking-badge-dot"></span>

                Smart Booking

            </div>


            <h1>
                Đặt lịch bảo dưỡng
            </h1>


            <p>
                Chọn phương tiện, dịch vụ và thời gian
                phù hợp. AutoCare sẽ tổng hợp chi phí
                và thời gian dự kiến ngay khi bạn lựa chọn.
            </p>

        </div>

    </section>


    @if ($vehicles->isEmpty())

        <div
            class="empty-state"
            data-reveal="zoom"
        >

            <div class="empty-state-icon">

                <i class="bi bi-car-front"></i>

            </div>


            <h3>
                Bạn chưa có phương tiện
            </h3>


            <p>
                Thêm phương tiện trước khi
                đặt lịch bảo dưỡng.
            </p>


            <a
                href="{{ route('vehicles.create') }}"
                class="
                    btn
                    btn-primary
                    btn-shine
                    px-4
                "
            >

                <i class="bi bi-plus-lg me-2"></i>

                Thêm phương tiện

            </a>

        </div>

    @else

        <form
            method="POST"
            action="{{ route('appointments.store') }}"
            id="appointment-booking-form"
            novalidate
        >

            @csrf


            <div class="booking-layout">

                <div
                    class="booking-card"
                    data-reveal="left"
                >

                    <div class="booking-card-body">

                        {{-- VEHICLE --}}
                        <section class="booking-section">

                            <div class="booking-section-heading">

                                <div class="booking-step">
                                    1
                                </div>

                                <div>

                                    <h2 class="booking-section-title">
                                        Chọn phương tiện
                                    </h2>

                                    <p class="booking-section-description">
                                        Xe cần được kiểm tra hoặc bảo dưỡng.
                                    </p>

                                </div>

                            </div>


                            <label
                                for="vehicle_id"
                                class="booking-label"
                            >

                                <i class="bi bi-car-front"></i>

                                Phương tiện *

                            </label>


                            <select
                                name="vehicle_id"
                                id="vehicle_id"
                                class="
                                    form-select
                                    booking-control
                                    @error('vehicle_id')
                                        is-invalid
                                    @enderror
                                "
                                aria-invalid="{{
                                    $errors->has('vehicle_id')
                                        ? 'true'
                                        : 'false'
                                }}"
                            >

                                <option value="">
                                    -- Chọn phương tiện --
                                </option>


                                @foreach ($vehicles as $vehicle)

                                    <option
                                        value="{{ $vehicle->id }}"
                                        {{
                                            (string) old(
                                                'vehicle_id'
                                            )
                                            ===
                                            (string) $vehicle->id
                                                ? 'selected'
                                                : ''
                                        }}
                                    >

                                        {{ $vehicle->brand->name }}

                                        {{ $vehicle->vehicleModel->name }}

                                        -

                                        {{ $vehicle->license_plate }}

                                    </option>

                                @endforeach

                            </select>

                        </section>


                        {{-- CONTACT --}}
                        <section class="booking-section">

                            <div class="booking-section-heading">

                                <div class="booking-step">
                                    2
                                </div>

                                <div>

                                    <h2 class="booking-section-title">
                                        Thông tin liên hệ
                                    </h2>

                                    <p class="booking-section-description">
                                        AutoCare sử dụng thông tin này
                                        để xác nhận lịch hẹn.
                                    </p>

                                </div>

                            </div>


                            <div class="row g-3">

                                <div class="col-md-6">

                                    <label
                                        for="contact_name"
                                        class="booking-label"
                                    >

                                        <i class="bi bi-person"></i>

                                        Người liên hệ *

                                    </label>


                                    <input
                                        type="text"
                                        name="contact_name"
                                        id="contact_name"
                                        class="
                                            form-control
                                            booking-control
                                            @error('contact_name')
                                                is-invalid
                                            @enderror
                                        "
                                        value="{{ old(
                                            'contact_name',
                                            $user
                                                ->customer
                                                ->full_name
                                        ) }}"
                                        maxlength="100"
                                        autocomplete="name"
                                        aria-invalid="{{
                                            $errors->has(
                                                'contact_name'
                                            )
                                                ? 'true'
                                                : 'false'
                                        }}"
                                    >

                                </div>


                                <div class="col-md-6">

                                    <label
                                        for="contact_phone"
                                        class="booking-label"
                                    >

                                        <i class="bi bi-telephone"></i>

                                        Số điện thoại *

                                    </label>


                                    <input
                                        type="tel"
                                        name="contact_phone"
                                        id="contact_phone"
                                        class="
                                            form-control
                                            booking-control
                                            @error('contact_phone')
                                                is-invalid
                                            @enderror
                                        "
                                        value="{{ old(
                                            'contact_phone',
                                            $user
                                                ->customer
                                                ->phone
                                        ) }}"
                                        maxlength="20"
                                        inputmode="tel"
                                        autocomplete="tel"
                                        placeholder="Ví dụ: 0912345678"
                                        aria-invalid="{{
                                            $errors->has(
                                                'contact_phone'
                                            )
                                                ? 'true'
                                                : 'false'
                                        }}"
                                    >

                                </div>


                                <div class="col-12">

                                    <label
                                        for="contact_email"
                                        class="booking-label"
                                    >

                                        <i class="bi bi-envelope"></i>

                                        Email

                                    </label>


                                    <input
                                        type="email"
                                        name="contact_email"
                                        id="contact_email"
                                        class="
                                            form-control
                                            booking-control
                                            @error('contact_email')
                                                is-invalid
                                            @enderror
                                        "
                                        value="{{ old(
                                            'contact_email',
                                            $user
                                                ->customer
                                                ->email
                                            ?? $user->email
                                        ) }}"
                                        maxlength="254"
                                        autocomplete="email"
                                        aria-invalid="{{
                                            $errors->has(
                                                'contact_email'
                                            )
                                                ? 'true'
                                                : 'false'
                                        }}"
                                    >

                                </div>

                            </div>

                        </section>


                        {{-- TIME --}}
                        <section class="booking-section">

                            <div class="booking-section-heading">

                                <div class="booking-step">
                                    3
                                </div>

                                <div>

                                    <h2 class="booking-section-title">
                                        Thời gian mong muốn
                                    </h2>

                                    <p class="booking-section-description">
                                        Chọn ngày và giờ phù hợp với bạn.
                                    </p>

                                </div>

                            </div>


                            <div class="row g-3">

                                <div class="col-md-6">

                                    <label
                                        for="appointment_date"
                                        class="booking-label"
                                    >

                                        <i class="bi bi-calendar3"></i>

                                        Ngày đặt lịch *

                                    </label>


                                    <input
                                        type="date"
                                        name="appointment_date"
                                        id="appointment_date"
                                        class="
                                            form-control
                                            booking-control
                                            @error('appointment_date')
                                                is-invalid
                                            @enderror
                                        "
                                        value="{{ old(
                                            'appointment_date'
                                        ) }}"
                                        min="{{ $todayHanoi }}"
                                        aria-invalid="{{
                                            $errors->has(
                                                'appointment_date'
                                            )
                                                ? 'true'
                                                : 'false'
                                        }}"
                                    >

                                </div>


                                <div class="col-md-6">

                                    <label
                                        for="appointment_time"
                                        class="booking-label"
                                    >

                                        <i class="bi bi-clock"></i>

                                        Giờ đặt lịch *

                                    </label>


                                    <input
                                        type="time"
                                        name="appointment_time"
                                        id="appointment_time"
                                        class="
                                            form-control
                                            booking-control
                                            @error('appointment_time')
                                                is-invalid
                                            @enderror
                                        "
                                        value="{{ old(
                                            'appointment_time'
                                        ) }}"
                                        aria-invalid="{{
                                            $errors->has(
                                                'appointment_time'
                                            )
                                                ? 'true'
                                                : 'false'
                                        }}"
                                    >

                                </div>

                            </div>

                        </section>


                        {{-- SERVICES --}}
                        <section class="booking-section">

                            <div class="booking-section-heading">

                                <div class="booking-step">
                                    4
                                </div>

                                <div>

                                    <h2 class="booking-section-title">
                                        Chọn dịch vụ
                                    </h2>

                                    <p class="booking-section-description">
                                        Có thể chọn nhiều dịch vụ
                                        trong cùng một lịch hẹn.
                                    </p>

                                </div>

                            </div>


                            @foreach ($categories as $category)

                                @if ($category->services->isNotEmpty())

                                    <div class="service-category">

                                        <div class="service-category-title">

                                            <i class="bi bi-tools"></i>

                                            {{ $category->name }}

                                        </div>


                                        <div
                                            class="
                                                service-grid
                                                {{
                                                    $hasServiceError
                                                        ? 'has-validation-error'
                                                        : ''
                                                }}
                                            "
                                        >

                                            @foreach ($category->services as $service)

                                                <div class="service-option">

                                                    <input
                                                        type="checkbox"
                                                        class="service-checkbox"
                                                        id="service_{{ $service->id }}"
                                                        name="service_ids[]"
                                                        value="{{ $service->id }}"
                                                        data-price="{{
                                                            (float)
                                                            $service
                                                                ->base_price
                                                        }}"
                                                        data-duration="{{
                                                            (int) (
                                                                $service
                                                                    ->estimated_duration_minutes
                                                                ?? 0
                                                            )
                                                        }}"
                                                        aria-invalid="{{
                                                            $hasServiceError
                                                                ? 'true'
                                                                : 'false'
                                                        }}"
                                                        {{
                                                            in_array(
                                                                (int)
                                                                $service->id,
                                                                $oldServices,
                                                                true
                                                            )
                                                                ? 'checked'
                                                                : ''
                                                        }}
                                                    >


                                                    <label
                                                        for="service_{{ $service->id }}"
                                                        class="service-option-label"
                                                    >

                                                        <span class="service-check">

                                                            <i class="bi bi-check-lg"></i>

                                                        </span>


                                                        <div class="service-name">

                                                            {{ $service->name }}

                                                        </div>


                                                        <div class="service-meta">

                                                            <span class="service-meta-badge">

                                                                <i class="bi bi-cash-stack"></i>

                                                                {{
                                                                    number_format(
                                                                        $service
                                                                            ->base_price,
                                                                        0,
                                                                        ',',
                                                                        '.'
                                                                    )
                                                                }} đ

                                                            </span>


                                                            @if (
                                                                $service
                                                                    ->estimated_duration_minutes
                                                            )

                                                                <span class="service-meta-badge">

                                                                    <i class="bi bi-clock"></i>

                                                                    {{
                                                                        $service
                                                                            ->estimated_duration_minutes
                                                                    }} phút

                                                                </span>

                                                            @endif

                                                        </div>

                                                    </label>

                                                </div>

                                            @endforeach

                                        </div>

                                    </div>

                                @endif

                            @endforeach

                        </section>


                        {{-- NOTE --}}
                        <section class="booking-section">

                            <div class="booking-section-heading">

                                <div class="booking-step">
                                    5
                                </div>

                                <div>

                                    <h2 class="booking-section-title">
                                        Ghi chú
                                    </h2>

                                    <p class="booking-section-description">
                                        Mô tả tình trạng xe hoặc
                                        yêu cầu bổ sung nếu có.
                                    </p>

                                </div>

                            </div>


                            <label
                                for="customer_note"
                                class="booking-label"
                            >

                                <i class="bi bi-chat-left-text"></i>

                                Tình trạng xe / yêu cầu thêm

                            </label>


                            <textarea
                                name="customer_note"
                                id="customer_note"
                                class="
                                    form-control
                                    booking-note
                                    @error('customer_note')
                                        is-invalid
                                    @enderror
                                "
                                rows="5"
                                maxlength="1000"
                                placeholder="Ví dụ: Xe có tiếng kêu khi phanh..."
                                aria-invalid="{{
                                    $errors->has(
                                        'customer_note'
                                    )
                                        ? 'true'
                                        : 'false'
                                }}"
                            >{{ old('customer_note') }}</textarea>

                        </section>

                    </div>

                </div>


                {{-- SUMMARY --}}
                <aside
                    class="summary-card"
                    data-reveal="right"
                >

                    <div class="summary-content">

                        <div class="summary-icon">

                            <i class="bi bi-receipt-cutoff"></i>

                        </div>


                        <div class="summary-title">
                            Tóm tắt lịch hẹn
                        </div>


                        <div class="summary-row">

                            <span class="summary-label">
                                Dịch vụ đã chọn
                            </span>

                            <span
                                id="selected-count"
                                class="summary-value"
                            >
                                0
                            </span>

                        </div>


                        <div class="summary-row">

                            <span class="summary-label">
                                Giá tham khảo
                            </span>

                            <span
                                id="estimated-total"
                                class="summary-value"
                            >
                                0 đ
                            </span>

                        </div>


                        <div class="summary-row">

                            <span class="summary-label">
                                Thời gian dự kiến
                            </span>

                            <span
                                id="estimated-duration"
                                class="summary-value"
                            >
                                0 phút
                            </span>

                        </div>


                        <button
                            type="submit"
                            class="booking-submit"
                            id="booking-submit-button"
                        >

                            <i class="bi bi-calendar2-check"></i>

                            <span>
                                Xác nhận đặt lịch
                            </span>

                        </button>


                        <div
                            class="
                                small
                                text-center
                                mt-3
                            "
                            style="color: #93c5fd;"
                        >

                            Giá hiển thị là giá tham khảo
                            trước khi kỹ thuật viên kiểm tra xe.

                        </div>

                    </div>

                </aside>

            </div>

        </form>

    @endif


    <a
        href="{{ route('appointments.index') }}"
        class="back-link"
    >

        <i class="bi bi-arrow-left"></i>

        Quay lại Lịch hẹn của tôi

    </a>

</div>

@endsection


@push('scripts')

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function () {
            const serviceCheckboxes =
                document.querySelectorAll(
                    '.service-checkbox'
                );

            const selectedCount =
                document.getElementById(
                    'selected-count'
                );

            const estimatedTotal =
                document.getElementById(
                    'estimated-total'
                );

            const estimatedDuration =
                document.getElementById(
                    'estimated-duration'
                );

            const appointmentDate =
                document.getElementById(
                    'appointment_date'
                );

            const appointmentTime =
                document.getElementById(
                    'appointment_time'
                );

            const bookingForm =
                document.getElementById(
                    'appointment-booking-form'
                );

            const bookingSubmitButton =
                document.getElementById(
                    'booking-submit-button'
                );

            const serverToday =
                @json($todayHanoi);

            const serverCurrentTime =
                @json($currentTimeHanoi);


            function updateSummary() {
                let count = 0;
                let total = 0;
                let duration = 0;

                serviceCheckboxes.forEach(
                    function (checkbox) {
                        if (!checkbox.checked) {
                            return;
                        }

                        count++;

                        total += Number(
                            checkbox.dataset.price
                            ?? 0
                        );

                        duration += Number(
                            checkbox.dataset.duration
                            ?? 0
                        );
                    }
                );

                if (selectedCount) {
                    selectedCount.textContent =
                        count;
                }

                if (estimatedTotal) {
                    estimatedTotal.textContent =
                        new Intl.NumberFormat(
                            'vi-VN'
                        ).format(total)
                        + ' đ';
                }

                if (estimatedDuration) {
                    estimatedDuration.textContent =
                        duration
                        + ' phút';
                }
            }


            function updateTimeMinimum() {
                if (
                    !appointmentDate
                    ||
                    !appointmentTime
                ) {
                    return;
                }

                if (
                    appointmentDate.value
                    === serverToday
                ) {
                    appointmentTime.min =
                        serverCurrentTime;

                    return;
                }

                appointmentTime.removeAttribute(
                    'min'
                );
            }


            serviceCheckboxes.forEach(
                function (checkbox) {
                    checkbox.addEventListener(
                        'change',
                        updateSummary
                    );
                }
            );


            if (appointmentDate) {
                appointmentDate.addEventListener(
                    'change',
                    updateTimeMinimum
                );
            }


            if (
                bookingForm
                &&
                bookingSubmitButton
            ) {
                bookingForm.addEventListener(
                    'submit',
                    function () {
                        bookingSubmitButton.disabled =
                            true;

                        bookingSubmitButton.innerHTML =
                            `
                                <span
                                    class="spinner-border spinner-border-sm"
                                    aria-hidden="true"
                                ></span>

                                <span>
                                    Đang gửi lịch hẹn...
                                </span>
                            `;
                    }
                );
            }


            updateSummary();
            updateTimeMinimum();
        }
    );
</script>

@endpush