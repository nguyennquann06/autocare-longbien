@extends('layouts.app')


@section(
    'title',
    $service->name . ' - AutoCare Long Biên'
)


@push('styles')

<style>
    .service-detail-page {
        width: 100%;
        max-width: 1240px;
        margin: 0 auto;
    }


    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .service-detail-hero {
        position: relative;
        overflow: hidden;

        padding: 42px 38px;

        margin-bottom: 26px;

        border-radius: 28px;

        color: white;

        background:
            linear-gradient(
                120deg,
                #06101e 0%,
                #0b2f6b 50%,
                #1467df 100%
            );

        box-shadow:
            0 28px 75px
            rgba(20, 103, 223, 0.23);
    }


    .service-detail-hero::before {
        content: "";

        position: absolute;

        width: 420px;
        height: 420px;

        top: -250px;
        right: -130px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, 0.43),
                transparent 70%
            );
    }


    .service-detail-hero::after {
        content: "";

        position: absolute;

        width: 320px;
        height: 320px;

        bottom: -255px;
        left: 30%;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(124, 58, 237, 0.37),
                transparent 70%
            );
    }


    .service-detail-hero-content {
        position: relative;

        z-index: 2;

        max-width: 820px;
    }


    .service-detail-category {
        width: fit-content;

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

        font-size: 11px;

        font-weight: 850;

        letter-spacing: 0.06em;

        text-transform: uppercase;
    }


    .service-detail-category-dot {
        width: 8px;
        height: 8px;

        border-radius: 50%;

        background: #67e8f9;

        box-shadow:
            0 0 12px
            #67e8f9;
    }


    .service-detail-title {
        max-width: 800px;

        margin: 0;

        color: white;

        font-size:
            clamp(
                2.25rem,
                5vw,
                4rem
            );

        font-weight: 900;

        line-height: 1.04;

        letter-spacing: -0.06em;
    }


    .service-detail-code {
        display: inline-flex;

        align-items: center;

        gap: 7px;

        margin-top: 15px;

        color: #bfdbfe;

        font-size: 12px;

        font-weight: 750;
    }


    /*
    |--------------------------------------------------------------------------
    | LAYOUT
    |--------------------------------------------------------------------------
    */

    .service-detail-layout {
        display: grid;

        grid-template-columns:
            minmax(0, 1fr)
            350px;

        gap: 24px;

        align-items: start;
    }


    /*
    |--------------------------------------------------------------------------
    | MAIN CARD
    |--------------------------------------------------------------------------
    */

    .service-detail-card {
        overflow: hidden;

        border:
            1px solid
            rgba(255, 255, 255, 0.88);

        border-radius: 22px;

        background:
            rgba(255, 255, 255, 0.93);

        box-shadow:
            var(--ac-shadow);

        backdrop-filter:
            blur(18px);
    }


    .service-detail-card-body {
        padding: 30px;
    }


    .service-detail-heading {
        display: flex;

        align-items: center;

        gap: 13px;

        margin-bottom: 18px;
    }


    .service-detail-heading-icon {
        width: 46px;
        height: 46px;

        flex:
            0 0 auto;

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

        box-shadow:
            0 8px 20px
            rgba(37, 99, 235, 0.10);

        font-size: 19px;
    }


    .service-detail-heading h2 {
        margin: 0;

        color: #0f172a;

        font-size: 21px;

        font-weight: 900;

        letter-spacing: -0.035em;
    }


    .service-detail-heading p {
        margin:
            3px 0 0;

        color: #64748b;

        font-size: 12px;
    }


    .service-detail-description {
        margin: 0;

        color: #475569;

        font-size: 14px;

        line-height: 1.9;

        white-space: pre-line;
    }


    .service-detail-divider {
        height: 1px;

        margin:
            28px 0;

        background: #edf1f6;
    }


    /*
    |--------------------------------------------------------------------------
    | INFORMATION GRID
    |--------------------------------------------------------------------------
    */

    .service-info-grid {
        display: grid;

        grid-template-columns:
            repeat(
                2,
                minmax(0, 1fr)
            );

        gap: 14px;
    }


    .service-info-item {
        position: relative;
        overflow: hidden;

        min-height: 120px;

        padding: 19px;

        border:
            1px solid
            #e5eaf1;

        border-radius: 17px;

        background:
            linear-gradient(
                180deg,
                #ffffff,
                #f8fbff
            );

        transition:
            transform 0.22s ease,
            border-color 0.22s ease,
            box-shadow 0.22s ease;
    }


    .service-info-item:hover {
        transform:
            translateY(-3px);

        border-color: #bfdbfe;

        box-shadow:
            0 12px 28px
            rgba(37, 99, 235, 0.09);
    }


    .service-info-icon {
        width: 36px;
        height: 36px;

        display: flex;

        align-items: center;
        justify-content: center;

        margin-bottom: 12px;

        border-radius: 11px;

        color: #2563eb;

        background: #eff6ff;

        font-size: 16px;
    }


    .service-info-label {
        color: #64748b;

        font-size: 10px;

        font-weight: 850;

        letter-spacing: 0.055em;

        text-transform: uppercase;
    }


    .service-info-value {
        margin-top: 5px;

        color: #0f172a;

        font-size: 17px;

        font-weight: 900;
    }


    .service-info-value.price {
        color: #1d4ed8;

        font-size: 22px;

        letter-spacing: -0.035em;
    }


    /*
    |--------------------------------------------------------------------------
    | MAINTENANCE INFO
    |--------------------------------------------------------------------------
    */

    .service-maintenance-box {
        display: grid;

        grid-template-columns:
            repeat(
                2,
                minmax(0, 1fr)
            );

        gap: 14px;

        margin-top: 18px;
    }


    .service-maintenance-item {
        display: flex;

        align-items: center;

        gap: 13px;

        padding: 17px;

        border:
            1px solid
            #e5eaf1;

        border-radius: 16px;

        background: #f8fafc;
    }


    .service-maintenance-icon {
        width: 42px;
        height: 42px;

        flex:
            0 0 auto;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 13px;

        color: #2563eb;

        background: white;

        box-shadow:
            0 7px 18px
            rgba(15, 23, 42, 0.06);

        font-size: 18px;
    }


    .service-maintenance-label {
        color: #64748b;

        font-size: 11px;

        font-weight: 750;
    }


    .service-maintenance-value {
        margin-top: 2px;

        color: #0f172a;

        font-size: 15px;

        font-weight: 900;
    }


    /*
    |--------------------------------------------------------------------------
    | SIDE SUMMARY
    |--------------------------------------------------------------------------
    */

    .service-summary-card {
        position: sticky;

        top: 100px;

        overflow: hidden;

        padding: 25px;

        border-radius: 22px;

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
            rgba(13, 47, 105, 0.26);
    }


    .service-summary-card::before {
        content: "";

        position: absolute;

        width: 230px;
        height: 230px;

        top: -145px;
        right: -110px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(34, 211, 238, 0.34),
                transparent 70%
            );
    }


    .service-summary-content {
        position: relative;

        z-index: 2;
    }


    .service-summary-icon {
        width: 50px;
        height: 50px;

        display: flex;

        align-items: center;
        justify-content: center;

        margin-bottom: 18px;

        border-radius: 15px;

        color: #67e8f9;

        background:
            rgba(255, 255, 255, 0.09);

        font-size: 22px;
    }


    .service-summary-title {
        margin: 0;

        color: white;

        font-size: 19px;

        font-weight: 900;
    }


    .service-summary-description {
        margin:
            7px 0 19px;

        color: #bfdbfe;

        font-size: 12px;

        line-height: 1.7;
    }


    .service-summary-row {
        display: flex;

        justify-content: space-between;

        align-items: flex-start;

        gap: 18px;

        padding:
            13px 0;

        border-bottom:
            1px solid
            rgba(255, 255, 255, 0.10);
    }


    .service-summary-row:last-of-type {
        border-bottom: none;
    }


    .service-summary-label {
        color: #bfdbfe;

        font-size: 11px;
    }


    .service-summary-value {
        color: white;

        text-align: right;

        font-size: 12px;

        font-weight: 900;
    }


    .service-booking-button {
        width: 100%;

        min-height: 50px;

        display: flex;

        align-items: center;
        justify-content: center;

        gap: 9px;

        margin-top: 20px;

        border-radius: 14px;

        color: #07111f;

        text-decoration: none;

        background:
            linear-gradient(
                135deg,
                #67e8f9,
                #bfdbfe
            );

        box-shadow:
            0 12px 28px
            rgba(103, 232, 249, 0.20);

        font-size: 13px;

        font-weight: 900;

        transition:
            transform 0.22s ease,
            box-shadow 0.22s ease;
    }


    .service-booking-button:hover {
        color: #07111f;

        transform:
            translateY(-3px);

        box-shadow:
            0 18px 36px
            rgba(103, 232, 249, 0.30);
    }


    /*
    |--------------------------------------------------------------------------
    | BACK LINK
    |--------------------------------------------------------------------------
    */

    .service-detail-back {
        display: inline-flex;

        align-items: center;

        gap: 7px;

        margin-top: 21px;

        color: #64748b;

        text-decoration: none;

        font-size: 13px;

        font-weight: 750;

        transition:
            color 0.2s ease,
            transform 0.2s ease;
    }


    .service-detail-back:hover {
        color: #2563eb;

        transform:
            translateX(-3px);
    }


    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 991px) {
        .service-detail-layout {
            grid-template-columns:
                1fr;
        }


        .service-summary-card {
            position: static;
        }
    }


    @media (max-width: 767px) {
        .service-detail-hero {
            padding:
                30px 24px;
        }


        .service-detail-card-body {
            padding: 22px;
        }
    }


    @media (max-width: 575px) {
        .service-info-grid,
        .service-maintenance-box {
            grid-template-columns:
                1fr;
        }


        .service-detail-title {
            font-size: 2.2rem;
        }
    }
</style>

@endpush


@section('content')

<div class="container service-detail-page">

    <section
        class="service-detail-hero"
        data-reveal="zoom"
    >

        <div class="service-detail-hero-content">

            <div class="service-detail-category">

                <span class="service-detail-category-dot"></span>

                {{ $service->category->name }}

            </div>


            <h1 class="service-detail-title">

                {{ $service->name }}

            </h1>


            @if ($service->code)

                <div class="service-detail-code">

                    <i class="bi bi-upc-scan"></i>

                    Mã dịch vụ:

                    {{ $service->code }}

                </div>

            @endif

        </div>

    </section>


    <div class="service-detail-layout">

        <main
            class="service-detail-card"
            data-reveal="left"
        >

            <div class="service-detail-card-body">

                <section>

                    <div class="service-detail-heading">

                        <div class="service-detail-heading-icon">

                            <i class="bi bi-card-text"></i>

                        </div>


                        <div>

                            <h2>
                                Thông tin dịch vụ
                            </h2>

                            <p>
                                Mô tả chi tiết dịch vụ bảo dưỡng.
                            </p>

                        </div>

                    </div>


                    <p class="service-detail-description">

                        {{
                            $service->description
                            ?: 'Thông tin mô tả dịch vụ đang được cập nhật.'
                        }}

                    </p>

                </section>


                <div class="service-detail-divider"></div>


                <section>

                    <div class="service-detail-heading">

                        <div class="service-detail-heading-icon">

                            <i class="bi bi-info-circle"></i>

                        </div>


                        <div>

                            <h2>
                                Thông tin tham khảo
                            </h2>

                            <p>
                                Chi phí và thời gian dự kiến của dịch vụ.
                            </p>

                        </div>

                    </div>


                    <div class="service-info-grid">

                        <div class="service-info-item">

                            <div class="service-info-icon">

                                <i class="bi bi-cash-stack"></i>

                            </div>


                            <div class="service-info-label">
                                Giá tham khảo
                            </div>


                            <div
                                class="
                                    service-info-value
                                    price
                                "
                            >

                                {{
                                    number_format(
                                        (float)
                                        $service->base_price,
                                        0,
                                        ',',
                                        '.'
                                    )
                                }} đ

                            </div>

                        </div>


                        <div class="service-info-item">

                            <div class="service-info-icon">

                                <i class="bi bi-clock"></i>

                            </div>


                            <div class="service-info-label">
                                Thời gian dự kiến
                            </div>


                            <div class="service-info-value">

                                @if (
                                    $service
                                        ->estimated_duration_minutes
                                )

                                    {{
                                        $service
                                            ->estimated_duration_minutes
                                    }} phút

                                @else

                                    Đang cập nhật

                                @endif

                            </div>

                        </div>

                    </div>

                </section>


                @if (
                    $service->mileage_interval
                    ||
                    $service->month_interval
                )

                    <div class="service-detail-divider"></div>


                    <section>

                        <div class="service-detail-heading">

                            <div class="service-detail-heading-icon">

                                <i class="bi bi-arrow-repeat"></i>

                            </div>


                            <div>

                                <h2>
                                    Chu kỳ bảo dưỡng tham khảo
                                </h2>

                                <p>
                                    Thông tin chu kỳ được cấu hình
                                    cho dịch vụ này.
                                </p>

                            </div>

                        </div>


                        <div class="service-maintenance-box">

                            @if ($service->mileage_interval)

                                <div class="service-maintenance-item">

                                    <div class="service-maintenance-icon">

                                        <i class="bi bi-speedometer2"></i>

                                    </div>


                                    <div>

                                        <div class="service-maintenance-label">
                                            Theo quãng đường
                                        </div>

                                        <div class="service-maintenance-value">

                                            {{
                                                number_format(
                                                    $service
                                                        ->mileage_interval,
                                                    0,
                                                    ',',
                                                    '.'
                                                )
                                            }} km

                                        </div>

                                    </div>

                                </div>

                            @endif


                            @if ($service->month_interval)

                                <div class="service-maintenance-item">

                                    <div class="service-maintenance-icon">

                                        <i class="bi bi-calendar3"></i>

                                    </div>


                                    <div>

                                        <div class="service-maintenance-label">
                                            Theo thời gian
                                        </div>

                                        <div class="service-maintenance-value">

                                            {{
                                                $service
                                                    ->month_interval
                                            }} tháng

                                        </div>

                                    </div>

                                </div>

                            @endif

                        </div>

                    </section>

                @endif

            </div>

        </main>


        <aside
            class="service-summary-card"
            data-reveal="right"
        >

            <div class="service-summary-content">

                <div class="service-summary-icon">

                    <i class="bi bi-wrench-adjustable-circle"></i>

                </div>


                <h2 class="service-summary-title">

                    {{ $service->name }}

                </h2>


                <p class="service-summary-description">

                    Thông tin tóm tắt để bạn
                    tham khảo trước khi đặt lịch.

                </p>


                <div class="service-summary-row">

                    <span class="service-summary-label">
                        Nhóm dịch vụ
                    </span>


                    <span class="service-summary-value">

                        {{ $service->category->name }}

                    </span>

                </div>


                <div class="service-summary-row">

                    <span class="service-summary-label">
                        Giá tham khảo
                    </span>


                    <span class="service-summary-value">

                        {{
                            number_format(
                                (float)
                                $service->base_price,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </span>

                </div>


                <div class="service-summary-row">

                    <span class="service-summary-label">
                        Thời gian
                    </span>


                    <span class="service-summary-value">

                        @if (
                            $service
                                ->estimated_duration_minutes
                        )

                            {{
                                $service
                                    ->estimated_duration_minutes
                            }} phút

                        @else

                            Đang cập nhật

                        @endif

                    </span>

                </div>


                @if ($service->mileage_interval)

                    <div class="service-summary-row">

                        <span class="service-summary-label">
                            Chu kỳ km
                        </span>


                        <span class="service-summary-value">

                            {{
                                number_format(
                                    $service
                                        ->mileage_interval,
                                    0,
                                    ',',
                                    '.'
                                )
                            }} km

                        </span>

                    </div>

                @endif


                @if ($service->month_interval)

                    <div class="service-summary-row">

                        <span class="service-summary-label">
                            Chu kỳ thời gian
                        </span>


                        <span class="service-summary-value">

                            {{
                                $service
                                    ->month_interval
                            }} tháng

                        </span>

                    </div>

                @endif


                <a
                    href="{{ route('appointments.create') }}"
                    class="service-booking-button"
                >

                    <i class="bi bi-calendar2-check"></i>

                    Đặt lịch bảo dưỡng

                </a>

            </div>

        </aside>

    </div>


    <a
        href="{{ route('services.index') }}"
        class="service-detail-back"
    >

        <i class="bi bi-arrow-left"></i>

        Quay lại danh sách dịch vụ

    </a>

</div>

@endsection