@extends('layouts.app')


@section(
    'title',
    'Dịch vụ bảo dưỡng - AutoCare Long Biên'
)


@push('styles')

<style>
    .services-page {
        max-width: 1240px;
    }


    .services-hero {
        position: relative;

        overflow: hidden;

        margin-bottom: 32px;

        padding: 38px 34px;

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


    .services-hero::before {
        content: "";

        position: absolute;

        width: 380px;
        height: 380px;

        top: -230px;
        right: -110px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, 0.42),
                transparent 70%
            );
    }


    .services-hero::after {
        content: "";

        position: absolute;

        width: 300px;
        height: 300px;

        left: 36%;
        bottom: -245px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(124, 58, 237, 0.34),
                transparent 70%
            );
    }


    .services-hero-content {
        position: relative;

        z-index: 2;

        max-width: 760px;
    }


    .services-hero-chip {
        width: fit-content;

        display: inline-flex;

        align-items: center;

        gap: 8px;

        padding: 7px 12px;

        margin-bottom: 17px;

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


    .services-hero-dot {
        width: 8px;
        height: 8px;

        border-radius: 50%;

        background: #67e8f9;

        box-shadow:
            0 0 12px #67e8f9;
    }


    .services-hero h1 {
        margin: 0;

        color: white;

        font-size:
            clamp(
                2.2rem,
                4vw,
                3.7rem
            );

        font-weight: 900;

        letter-spacing: -0.06em;
    }


    .services-hero p {
        margin:
            14px 0 0;

        color: #cbd5e1;

        line-height: 1.8;

        font-size: 15px;
    }


    .service-category-section {
        margin-bottom: 38px;
    }


    .service-category-header {
        display: flex;

        align-items: flex-start;

        justify-content: space-between;

        gap: 20px;

        margin-bottom: 18px;

        flex-wrap: wrap;
    }


    .service-category-heading {
        display: flex;

        align-items: flex-start;

        gap: 13px;
    }


    .service-category-icon {
        width: 43px;
        height: 43px;

        flex: 0 0 auto;

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


    .service-category-title {
        margin: 0;

        color: #0f172a;

        font-size: 21px;

        font-weight: 900;

        letter-spacing: -0.035em;
    }


    .service-category-description {
        max-width: 720px;

        margin:
            5px 0 0;

        color: #64748b;

        font-size: 13px;

        line-height: 1.7;
    }


    .service-count-badge {
        display: inline-flex;

        align-items: center;

        gap: 6px;

        padding: 7px 11px;

        border-radius: 999px;

        color: #1d4ed8;

        background: #eff6ff;

        font-size: 11px;

        font-weight: 850;
    }


    .service-grid {
        display: grid;

        grid-template-columns:
            repeat(
                3,
                minmax(0, 1fr)
            );

        gap: 17px;
    }


    .service-card {
        position: relative;

        overflow: hidden;

        min-height: 100%;

        display: flex;

        flex-direction: column;

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
            transform 0.24s ease,
            box-shadow 0.24s ease,
            border-color 0.24s ease;
    }


    .service-card:hover {
        transform:
            translateY(-7px);

        border-color:
            rgba(147, 197, 253, 0.65);

        box-shadow:
            var(--ac-shadow-lg);
    }


    .service-card::before {
        content: "";

        position: absolute;

        width: 150px;
        height: 150px;

        top: -95px;
        right: -85px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(59, 130, 246, 0.14),
                transparent 70%
            );
    }


    .service-card-body {
        position: relative;

        z-index: 2;

        flex: 1;

        display: flex;

        flex-direction: column;

        padding: 22px;
    }


    .service-card-icon {
        width: 46px;
        height: 46px;

        display: flex;

        align-items: center;

        justify-content: center;

        margin-bottom: 17px;

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


    .service-name {
        margin: 0;

        color: #0f172a;

        font-size: 17px;

        font-weight: 900;

        letter-spacing: -0.03em;
    }


    .service-description {
        flex: 1;

        margin:
            10px 0 18px;

        color: #64748b;

        font-size: 12px;

        line-height: 1.75;
    }


    .service-divider {
        height: 1px;

        margin-bottom: 17px;

        background: #edf1f6;
    }


    .service-price-label {
        color: #64748b;

        font-size: 10px;

        font-weight: 800;

        text-transform: uppercase;

        letter-spacing: 0.05em;
    }


    .service-price {
        margin-top: 4px;

        color: #1d4ed8;

        font-size: 22px;

        font-weight: 900;

        letter-spacing: -0.04em;
    }


    .service-meta {
        display: flex;

        flex-wrap: wrap;

        gap: 7px;

        margin-top: 15px;
    }


    .service-meta-item {
        display: inline-flex;

        align-items: center;

        gap: 5px;

        padding: 6px 9px;

        border-radius: 999px;

        color: #475569;

        background: #f1f5f9;

        font-size: 10px;

        font-weight: 750;
    }


    .service-card-footer {
        margin-top: 20px;
    }


    .service-detail-button {
        width: 100%;

        min-height: 44px;

        display: flex;

        align-items: center;

        justify-content: center;

        gap: 8px;

        border-radius: 12px;

        color: white;

        text-decoration: none;

        background:
            linear-gradient(
                135deg,
                #1683ff,
                #4f46e5
            );

        box-shadow:
            0 9px 23px
            rgba(37, 99, 235, 0.20);

        font-size: 12px;

        font-weight: 850;

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }


    .service-detail-button:hover {
        color: white;

        transform:
            translateY(-2px);

        box-shadow:
            0 13px 30px
            rgba(37, 99, 235, 0.28);
    }


    .services-back {
        display: inline-flex;

        align-items: center;

        gap: 7px;

        margin-top: 5px;

        color: #64748b;

        text-decoration: none;

        font-size: 13px;

        font-weight: 750;
    }


    .services-back:hover {
        color: #2563eb;
    }


    @media (max-width: 991px) {
        .service-grid {
            grid-template-columns:
                repeat(
                    2,
                    minmax(0, 1fr)
                );
        }
    }


    @media (max-width: 767px) {
        .services-hero {
            padding: 27px 24px;
        }
    }


    @media (max-width: 575px) {
        .service-grid {
            grid-template-columns: 1fr;
        }

        .service-category-title {
            font-size: 18px;
        }
    }
</style>

@endpush


@section('content')

<div class="container services-page">

    <section
        class="services-hero"
        data-reveal="zoom"
    >

        <div class="services-hero-content">

            <div class="services-hero-chip">

                <span class="services-hero-dot"></span>

                AutoCare Service Center

            </div>


            <h1>
                Dịch vụ bảo dưỡng ô tô
            </h1>


            <p>

                Khám phá các dịch vụ kiểm tra,
                chăm sóc và bảo dưỡng phương tiện
                tại AutoCare Long Biên với thông tin
                chi phí, thời gian và chu kỳ tham khảo rõ ràng.

            </p>

        </div>

    </section>


    @if ($categories->isEmpty())

        <div
            class="empty-state"
            data-reveal="zoom"
        >

            <div class="empty-state-icon">

                <i class="bi bi-tools"></i>

            </div>


            <h3>
                Chưa có dịch vụ
            </h3>


            <p>
                Danh mục dịch vụ đang được cập nhật.
            </p>

        </div>

    @else

        @foreach ($categories as $category)

            @if ($category->services->isNotEmpty())

                <section
                    class="service-category-section"
                    data-reveal
                >

                    <div class="service-category-header">

                        <div class="service-category-heading">

                            <div class="service-category-icon">

                                <i class="bi bi-tools"></i>

                            </div>


                            <div>

                                <h2 class="service-category-title">
                                    {{ $category->name }}
                                </h2>


                                @if ($category->description)

                                    <p class="service-category-description">
                                        {{ $category->description }}
                                    </p>

                                @endif

                            </div>

                        </div>


                        <div class="service-count-badge">

                            <i class="bi bi-grid"></i>

                            {{ $category->services->count() }}
                            dịch vụ

                        </div>

                    </div>


                    <div class="service-grid">

                        @foreach ($category->services as $service)

                            <article
                                class="service-card"
                                data-tilt
                            >

                                <div class="service-card-body">

                                    <div class="service-card-icon">

                                        <i class="bi bi-wrench-adjustable"></i>

                                    </div>


                                    <h3 class="service-name">
                                        {{ $service->name }}
                                    </h3>


                                    <p class="service-description">

                                        {{
                                            $service->description
                                            ?? 'Chưa có mô tả.'
                                        }}

                                    </p>


                                    <div class="service-divider"></div>


                                    <div class="service-price-label">
                                        Giá tham khảo
                                    </div>


                                    <div class="service-price">

                                        {{
                                            number_format(
                                                $service->base_price,
                                                0,
                                                ',',
                                                '.'
                                            )
                                        }} đ

                                    </div>


                                    <div class="service-meta">

                                        @if (
                                            $service
                                                ->estimated_duration_minutes
                                        )

                                            <span class="service-meta-item">

                                                <i class="bi bi-clock"></i>

                                                {{
                                                    $service
                                                        ->estimated_duration_minutes
                                                }} phút

                                            </span>

                                        @endif


                                        @if ($service->mileage_interval)

                                            <span class="service-meta-item">

                                                <i class="bi bi-speedometer2"></i>

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

                                        @endif


                                        @if ($service->month_interval)

                                            <span class="service-meta-item">

                                                <i class="bi bi-calendar3"></i>

                                                {{
                                                    $service
                                                        ->month_interval
                                                }} tháng

                                            </span>

                                        @endif

                                    </div>


                                    <div class="service-card-footer">

                                        <a
                                            href="{{ route(
                                                'services.show',
                                                $service->id
                                            ) }}"
                                            class="service-detail-button"
                                        >

                                            Xem chi tiết

                                            <i class="bi bi-arrow-right"></i>

                                        </a>

                                    </div>

                                </div>

                            </article>

                        @endforeach

                    </div>

                </section>

            @endif

        @endforeach

    @endif


    <a
        href="{{ route('home') }}"
        class="services-back"
    >

        <i class="bi bi-arrow-left"></i>

        Quay lại trang chủ

    </a>

</div>

@endsection