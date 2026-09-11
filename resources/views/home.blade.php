@extends('layouts.app')


@section(
    'title',
    'AutoCare Long Biên - Chăm sóc và bảo dưỡng ô tô'
)


@push('styles')

<style>
    .home-page {
        overflow: hidden;
    }

    /* =====================================================
       HERO
       ===================================================== */

    .home-hero {
        position: relative;
        overflow: hidden;

        max-width: 1280px;

        min-height: 600px;

        margin: 0 auto 34px;
        padding: 60px 52px;

        display: flex;
        align-items: center;

        border-radius: 32px;

        color: white;

        background:
            linear-gradient(
                120deg,
                #040b15 0%,
                #071b38 35%,
                #0c3474 67%,
                #1677ff 100%
            );

        box-shadow:
            0 35px 95px
            rgba(15, 76, 190, 0.28);
    }

    .home-hero::before {
        content: "";

        position: absolute;

        width: 600px;
        height: 600px;

        right: -180px;
        top: -300px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, 0.34),
                transparent 68%
            );
    }

    .home-hero::after {
        content: "";

        position: absolute;

        width: 480px;
        height: 480px;

        left: 30%;
        bottom: -380px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(124, 58, 237, 0.36),
                transparent 70%
            );
    }

    .home-hero-grid {
        position: relative;
        z-index: 2;

        width: 100%;

        display: grid;

        grid-template-columns:
            minmax(0, 1.12fr)
            minmax(360px, 0.88fr);

        gap: 55px;

        align-items: center;
    }

    .home-eyebrow {
        display: inline-flex;

        align-items: center;

        gap: 9px;

        padding: 8px 13px;

        margin-bottom: 20px;

        border:
            1px solid
            rgba(255, 255, 255, 0.14);

        border-radius: 999px;

        color: #dbeafe;

        background:
            rgba(255, 255, 255, 0.08);

        backdrop-filter: blur(14px);

        font-size: 11px;
        font-weight: 850;

        text-transform: uppercase;

        letter-spacing: 0.08em;
    }

    .home-eyebrow-dot {
        width: 8px;
        height: 8px;

        border-radius: 50%;

        background: #67e8f9;

        box-shadow:
            0 0 14px #67e8f9;
    }

    .home-hero-title {
        max-width: 780px;

        margin: 0;

        color: white;

        font-size:
            clamp(
                3rem,
                6vw,
                5.7rem
            );

        line-height: 0.98;

        font-weight: 950;

        letter-spacing: -0.075em;
    }

    .home-hero-title span {
        color: #67e8f9;
    }

    .home-hero-description {
        max-width: 670px;

        margin: 23px 0 0;

        color: #cbd5e1;

        font-size: 15px;

        line-height: 1.85;
    }

    .home-hero-actions {
        display: flex;

        align-items: center;

        gap: 12px;

        flex-wrap: wrap;

        margin-top: 30px;
    }

    .home-primary-button,
    .home-secondary-button {
        min-height: 49px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 9px;

        padding: 0 19px;

        border-radius: 13px;

        text-decoration: none;

        font-size: 12px;

        font-weight: 900;

        transition:
            transform 0.22s ease,
            box-shadow 0.22s ease,
            background 0.22s ease;
    }

    .home-primary-button {
        color: #06101e;

        background:
            linear-gradient(
                135deg,
                #67e8f9,
                #bfdbfe
            );

        box-shadow:
            0 13px 30px
            rgba(103, 232, 249, 0.20);
    }

    .home-primary-button:hover {
        color: #06101e;

        transform:
            translateY(-3px);

        box-shadow:
            0 18px 42px
            rgba(103, 232, 249, 0.30);
    }

    .home-secondary-button {
        color: white;

        border:
            1px solid
            rgba(255, 255, 255, 0.14);

        background:
            rgba(255, 255, 255, 0.07);
    }

    .home-secondary-button:hover {
        color: white;

        background:
            rgba(255, 255, 255, 0.12);

        transform:
            translateY(-3px);
    }


    /* =====================================================
       HERO DASHBOARD CARD
       ===================================================== */

    .home-hero-panel {
        position: relative;

        padding: 25px;

        border:
            1px solid
            rgba(255, 255, 255, 0.13);

        border-radius: 25px;

        background:
            rgba(255, 255, 255, 0.075);

        box-shadow:
            0 30px 65px
            rgba(0, 0, 0, 0.18);

        backdrop-filter:
            blur(22px);
    }

    .home-panel-header {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 15px;

        margin-bottom: 23px;
    }

    .home-panel-brand {
        display: flex;

        align-items: center;

        gap: 12px;
    }

    .home-panel-logo {
        width: 47px;
        height: 47px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 15px;

        color: #67e8f9;

        background:
            rgba(255, 255, 255, 0.09);

        font-size: 22px;
    }

    .home-panel-title {
        color: white;

        font-size: 13px;

        font-weight: 900;
    }

    .home-panel-subtitle {
        margin-top: 3px;

        color: #93c5fd;

        font-size: 10px;
    }

    .home-online-badge {
        display: inline-flex;

        align-items: center;

        gap: 6px;

        padding: 6px 9px;

        border-radius: 999px;

        color: #d1fae5;

        background:
            rgba(16, 185, 129, 0.14);

        font-size: 9px;

        font-weight: 850;
    }

    .home-online-badge::before {
        content: "";

        width: 6px;
        height: 6px;

        border-radius: 50%;

        background: #6ee7b7;

        box-shadow:
            0 0 9px #6ee7b7;
    }

    .home-panel-grid {
        display: grid;

        grid-template-columns:
            repeat(
                2,
                minmax(0, 1fr)
            );

        gap: 12px;
    }

    .home-panel-item {
        min-height: 126px;

        padding: 16px;

        border:
            1px solid
            rgba(255, 255, 255, 0.09);

        border-radius: 16px;

        background:
            rgba(255, 255, 255, 0.055);

        transition:
            transform 0.2s ease,
            background 0.2s ease;
    }

    .home-panel-item:hover {
        transform:
            translateY(-3px);

        background:
            rgba(255, 255, 255, 0.09);
    }

    .home-panel-item i {
        color: #67e8f9;

        font-size: 20px;
    }

    .home-panel-item-title {
        margin-top: 17px;

        color: white;

        font-size: 12px;

        font-weight: 900;
    }

    .home-panel-item-text {
        margin-top: 5px;

        color: #bfdbfe;

        font-size: 9px;

        line-height: 1.6;
    }


    /* =====================================================
       GENERAL SECTION
       ===================================================== */

    .home-section {
        max-width: 1280px;

        margin:
            0 auto 35px;

        padding:
            45px 36px;
    }

    .home-section-heading {
        max-width: 760px;

        margin-bottom: 30px;
    }

    .home-section-label {
        display: inline-flex;

        align-items: center;

        gap: 7px;

        margin-bottom: 10px;

        color: #2563eb;

        font-size: 10px;

        font-weight: 900;

        text-transform: uppercase;

        letter-spacing: 0.09em;
    }

    .home-section-label::before {
        content: "";

        width: 22px;
        height: 2px;

        border-radius: 999px;

        background:
            linear-gradient(
                90deg,
                #22d3ee,
                #2563eb
            );
    }

    .home-section-heading h2 {
        margin: 0;

        color: #0f172a;

        font-size:
            clamp(
                2rem,
                4vw,
                3.2rem
            );

        font-weight: 950;

        letter-spacing: -0.055em;
    }

    .home-section-heading p {
        margin:
            12px 0 0;

        color: #64748b;

        font-size: 13px;

        line-height: 1.8;
    }


    /* =====================================================
       SERVICE CARDS
       ===================================================== */

    .home-service-grid {
        display: grid;

        grid-template-columns:
            repeat(
                4,
                minmax(0, 1fr)
            );

        gap: 16px;
    }

    .home-service-card {
        position: relative;

        overflow: hidden;

        min-height: 260px;

        padding: 23px;

        border:
            1px solid
            rgba(255, 255, 255, 0.9);

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

    .home-service-card:hover {
        transform:
            translateY(-7px);

        border-color: #bfdbfe;

        box-shadow:
            var(--ac-shadow-lg);
    }

    .home-service-card::after {
        content: "";

        position: absolute;

        width: 160px;
        height: 160px;

        top: -100px;
        right: -100px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(59, 130, 246, 0.15),
                transparent 70%
            );
    }

    .home-service-icon {
        width: 48px;
        height: 48px;

        display: flex;

        align-items: center;

        justify-content: center;

        margin-bottom: 22px;

        border-radius: 15px;

        color: #2563eb;

        background:
            linear-gradient(
                135deg,
                #dbeafe,
                #ecfeff
            );

        font-size: 21px;
    }

    .home-service-card h3 {
        position: relative;

        z-index: 2;

        margin: 0;

        color: #0f172a;

        font-size: 15px;

        font-weight: 900;
    }

    .home-service-card p {
        position: relative;

        z-index: 2;

        margin:
            10px 0 0;

        color: #64748b;

        font-size: 11px;

        line-height: 1.75;
    }

    .home-service-link {
        position: absolute;

        z-index: 3;

        left: 23px;
        bottom: 22px;

        display: inline-flex;

        align-items: center;

        gap: 6px;

        color: #2563eb;

        text-decoration: none;

        font-size: 10px;

        font-weight: 900;
    }


    /* =====================================================
       BOOKING CTA
       ===================================================== */

    .home-booking {
        position: relative;

        overflow: hidden;

        max-width: 1208px;

        margin:
            0 auto 70px;

        padding: 47px;

        border-radius: 27px;

        background:
            linear-gradient(
                135deg,
                #ffffff,
                #f4f8ff
            );

        border:
            1px solid
            rgba(255, 255, 255, 0.9);

        box-shadow:
            var(--ac-shadow-lg);
    }

    .home-booking::before {
        content: "";

        position: absolute;

        width: 380px;
        height: 380px;

        right: -180px;
        top: -210px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(37, 99, 235, 0.13),
                transparent 70%
            );
    }

    .home-booking-grid {
        position: relative;

        z-index: 2;

        display: grid;

        grid-template-columns:
            1fr auto;

        gap: 35px;

        align-items: center;
    }

    .home-booking h2 {
        margin: 0;

        color: #0f172a;

        font-size:
            clamp(
                1.8rem,
                4vw,
                3rem
            );

        font-weight: 950;

        letter-spacing: -0.05em;
    }

    .home-booking p {
        max-width: 680px;

        margin:
            12px 0 0;

        color: #64748b;

        font-size: 12px;

        line-height: 1.8;
    }


    /* =====================================================
       AI SECTION
       ===================================================== */

    .home-ai {
        position: relative;

        overflow: hidden;

        max-width: 1280px;

        margin:
            0 auto 40px;

        padding: 54px;

        border-radius: 30px;

        color: white;

        background:
            linear-gradient(
                135deg,
                #050b15,
                #071d3d 48%,
                #102e68
            );

        box-shadow:
            0 28px 70px
            rgba(7, 29, 61, 0.25);
    }

    .home-ai::before {
        content: "";

        position: absolute;

        width: 400px;
        height: 400px;

        top: -240px;
        right: -80px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(124, 58, 237, 0.35),
                transparent 70%
            );
    }

    .home-ai-grid {
        position: relative;

        z-index: 2;

        display: grid;

        grid-template-columns:
            minmax(0, 1.15fr)
            minmax(300px, 0.85fr);

        gap: 50px;

        align-items: center;
    }

    .home-ai-label {
        display: inline-flex;

        align-items: center;

        gap: 8px;

        color: #67e8f9;

        font-size: 10px;

        font-weight: 900;

        text-transform: uppercase;

        letter-spacing: 0.08em;
    }

    .home-ai h2 {
        max-width: 690px;

        margin:
            13px 0 0;

        color: white;

        font-size:
            clamp(
                2rem,
                4vw,
                3.4rem
            );

        font-weight: 950;

        letter-spacing: -0.055em;
    }

    .home-ai-description {
        max-width: 690px;

        margin:
            14px 0 0;

        color: #cbd5e1;

        font-size: 12px;

        line-height: 1.8;
    }

    .home-ai-features {
        display: grid;

        gap: 10px;

        margin-top: 24px;
    }

    .home-ai-feature {
        display: flex;

        align-items: flex-start;

        gap: 10px;

        color: #dbeafe;

        font-size: 11px;

        line-height: 1.7;
    }

    .home-ai-feature i {
        margin-top: 2px;

        color: #67e8f9;
    }

    .home-ai-card {
        padding: 26px;

        border:
            1px solid
            rgba(255, 255, 255, 0.12);

        border-radius: 23px;

        background:
            rgba(255, 255, 255, 0.07);

        backdrop-filter:
            blur(18px);
    }

    .home-ai-icon {
        width: 57px;
        height: 57px;

        display: flex;

        align-items: center;

        justify-content: center;

        margin-bottom: 20px;

        border-radius: 18px;

        color: #67e8f9;

        background:
            rgba(255, 255, 255, 0.08);

        font-size: 25px;
    }

    .home-ai-card h3 {
        color: white;

        font-size: 17px;

        font-weight: 900;
    }

    .home-ai-card p {
        color: #bfdbfe;

        font-size: 11px;

        line-height: 1.75;
    }

    .home-ai-status {
        display: inline-flex;

        align-items: center;

        gap: 7px;

        margin-top: 12px;

        padding: 7px 10px;

        border-radius: 999px;

        color: #fde68a;

        background:
            rgba(245, 158, 11, 0.12);

        font-size: 9px;

        font-weight: 850;
    }


    /* =====================================================
       CONTACT
       ===================================================== */

    .home-contact {
        max-width: 1208px;

        margin:
            0 auto 25px;

        padding: 38px;

        border:
            1px solid
            rgba(255, 255, 255, 0.9);

        border-radius: 23px;

        background:
            rgba(255, 255, 255, 0.92);

        box-shadow:
            var(--ac-shadow);
    }

    .home-contact-grid {
        display: grid;

        grid-template-columns:
            repeat(
                3,
                minmax(0, 1fr)
            );

        gap: 15px;

        margin-top: 25px;
    }

    .home-contact-item {
        padding: 17px;

        border: 1px solid #e7edf4;

        border-radius: 15px;

        background: #f8fbff;
    }

    .home-contact-icon {
        color: #2563eb;

        font-size: 20px;
    }

    .home-contact-title {
        margin-top: 10px;

        color: #0f172a;

        font-size: 12px;

        font-weight: 900;
    }

    .home-contact-text {
        margin-top: 5px;

        color: #64748b;

        font-size: 10px;

        line-height: 1.6;
    }


    /* =====================================================
       RESPONSIVE
       ===================================================== */

    @media (max-width: 1199px) {
        .home-service-grid {
            grid-template-columns:
                repeat(
                    2,
                    minmax(0, 1fr)
                );
        }
    }

    @media (max-width: 991px) {
        .home-hero {
            padding: 45px 32px;
        }

        .home-hero-grid,
        .home-ai-grid,
        .home-booking-grid {
            grid-template-columns: 1fr;
        }

        .home-hero-panel {
            max-width: 600px;
        }

        .home-booking-grid {
            align-items: start;
        }
    }

    @media (max-width: 767px) {
        .home-contact-grid {
            grid-template-columns: 1fr;
        }

        .home-section {
            padding:
                35px 15px;
        }

        .home-ai,
        .home-booking {
            padding: 30px 24px;
        }
    }

    @media (max-width: 575px) {
        .home-hero {
            min-height: auto;

            padding:
                35px 23px;

            border-radius: 23px;
        }

        .home-hero-title {
            font-size: 2.8rem;
        }

        .home-service-grid,
        .home-panel-grid {
            grid-template-columns: 1fr;
        }

        .home-primary-button,
        .home-secondary-button {
            width: 100%;
        }
    }
</style>

@endpush


@section('content')

<div class="home-page">

    {{-- =====================================================
        HERO
    ====================================================== --}}
    <section
        class="home-hero"
        data-reveal="zoom"
    >

        <div class="home-hero-grid">

            <div>

                <div class="home-eyebrow">

                    <span class="home-eyebrow-dot"></span>

                    Automotive Care Platform

                </div>


                <h1 class="home-hero-title">

                    Chăm sóc xe
                    <span>thông minh</span>,
                    hành trình an tâm.

                </h1>


                <p class="home-hero-description">

                    AutoCare Long Biên kết nối
                    quản lý phương tiện,
                    đặt lịch bảo dưỡng,
                    theo dõi lịch sử sửa chữa
                    và hỗ trợ tư vấn ô tô
                    trên một nền tảng duy nhất.

                </p>


                <div class="home-hero-actions">

                    <a
                        href="{{ route('appointments.create') }}"
                        class="home-primary-button"
                    >

                        <i class="bi bi-calendar2-check"></i>

                        Đặt lịch bảo dưỡng

                    </a>


                    <a
                        href="{{ route('services.index') }}"
                        class="home-secondary-button"
                    >

                        <i class="bi bi-tools"></i>

                        Khám phá dịch vụ

                    </a>

                </div>

            </div>


            <div
                class="home-hero-panel"
                data-tilt
            >

                <div class="home-panel-header">

                    <div class="home-panel-brand">

                        <div class="home-panel-logo">

                            <i class="bi bi-car-front-fill"></i>

                        </div>


                        <div>

                            <div class="home-panel-title">
                                AutoCare Control
                            </div>

                            <div class="home-panel-subtitle">
                                Long Biên · Hà Nội
                            </div>

                        </div>

                    </div>


                    <span class="home-online-badge">
                        Online
                    </span>

                </div>


                <div class="home-panel-grid">

                    <div class="home-panel-item">

                        <i class="bi bi-car-front"></i>

                        <div class="home-panel-item-title">
                            Quản lý xe
                        </div>

                        <div class="home-panel-item-text">
                            Lưu hồ sơ phương tiện
                            và ODO hiện tại.
                        </div>

                    </div>


                    <div class="home-panel-item">

                        <i class="bi bi-calendar2-check"></i>

                        <div class="home-panel-item-title">
                            Đặt lịch
                        </div>

                        <div class="home-panel-item-text">
                            Chọn dịch vụ và
                            thời gian bảo dưỡng.
                        </div>

                    </div>


                    <div class="home-panel-item">

                        <i class="bi bi-clock-history"></i>

                        <div class="home-panel-item-title">
                            Lịch sử
                        </div>

                        <div class="home-panel-item-text">
                            Theo dõi các lần
                            bảo dưỡng đã thực hiện.
                        </div>

                    </div>


                    <div class="home-panel-item">

                        <i class="bi bi-stars"></i>

                        <div class="home-panel-item-title">
                            AutoCare AI
                        </div>

                        <div class="home-panel-item-text">
                            Trợ lý thông minh
                            hỗ trợ chăm sóc xe.
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
        SERVICES
    ====================================================== --}}
    <section
        class="home-section"
        id="services"
    >

        <div
            class="home-section-heading"
            data-reveal
        >

            <div class="home-section-label">
                Dịch vụ AutoCare
            </div>


            <h2>
                Chăm sóc toàn diện cho phương tiện
            </h2>


            <p>

                Các nhóm dịch vụ phổ biến
                giúp xe vận hành ổn định,
                an toàn và duy trì tình trạng tốt
                trong suốt quá trình sử dụng.

            </p>

        </div>


        <div class="home-service-grid">

            <article
                class="home-service-card"
                data-reveal
                data-tilt
            >

                <div class="home-service-icon">
                    <i class="bi bi-wrench-adjustable"></i>
                </div>

                <h3>
                    Bảo dưỡng định kỳ
                </h3>

                <p>
                    Kiểm tra và bảo dưỡng xe
                    theo ODO hoặc thời gian sử dụng.
                </p>

                <a
                    href="{{ route('services.index') }}"
                    class="home-service-link"
                >
                    Khám phá
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article
                class="home-service-card"
                data-reveal
                data-tilt
            >

                <div class="home-service-icon">
                    <i class="bi bi-droplet-half"></i>
                </div>

                <h3>
                    Dầu nhớt & bộ lọc
                </h3>

                <p>
                    Thay dầu động cơ,
                    lọc dầu, lọc gió và
                    các vật tư định kỳ.
                </p>

                <a
                    href="{{ route('services.index') }}"
                    class="home-service-link"
                >
                    Khám phá
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article
                class="home-service-card"
                data-reveal
                data-tilt
            >

                <div class="home-service-icon">
                    <i class="bi bi-disc"></i>
                </div>

                <h3>
                    Lốp & hệ thống phanh
                </h3>

                <p>
                    Kiểm tra lốp,
                    hệ thống phanh và
                    các bộ phận liên quan.
                </p>

                <a
                    href="{{ route('services.index') }}"
                    class="home-service-link"
                >
                    Khám phá
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article
                class="home-service-card"
                data-reveal
                data-tilt
            >

                <div class="home-service-icon">
                    <i class="bi bi-battery-charging"></i>
                </div>

                <h3>
                    Điện & ắc quy
                </h3>

                <p>
                    Kiểm tra hệ thống điện,
                    ắc quy và thiết bị điện
                    trên phương tiện.
                </p>

                <a
                    href="{{ route('services.index') }}"
                    class="home-service-link"
                >
                    Khám phá
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>

        </div>

    </section>


    {{-- =====================================================
        BOOKING
    ====================================================== --}}
    <section
        class="home-booking"
        id="booking"
        data-reveal="zoom"
    >

        <div class="home-booking-grid">

            <div>

                <div class="home-section-label">
                    Smart Booking
                </div>


                <h2>
                    Chủ động đặt lịch bảo dưỡng
                </h2>


                <p>

                    Chọn phương tiện,
                    hạng mục dịch vụ
                    và thời gian phù hợp.
                    AutoCare sẽ lưu yêu cầu
                    và hỗ trợ bạn theo dõi
                    toàn bộ quá trình xử lý.

                </p>

            </div>


            <div>

                <a
                    href="{{ route('appointments.create') }}"
                    class="btn btn-primary btn-lg px-4"
                >

                    <i class="bi bi-calendar-plus me-2"></i>

                    Đặt lịch ngay

                </a>

            </div>

        </div>

    </section>


    {{-- =====================================================
        AI CHATBOT
    ====================================================== --}}
    <section
        class="home-ai"
        id="ai-chatbot"
        data-reveal="zoom"
    >

        <div class="home-ai-grid">

            <div>

                <div class="home-ai-label">

                    <i class="bi bi-stars"></i>

                    AutoCare Intelligence

                </div>


                <h2>
                    Trợ lý AI hỗ trợ bảo dưỡng ô tô
                </h2>


                <p class="home-ai-description">

                    AutoCare AI được định hướng
                    trở thành trợ lý hỗ trợ người dùng
                    tra cứu thông tin dịch vụ,
                    lịch sử bảo dưỡng
                    và các gợi ý chăm sóc xe
                    dựa trên dữ liệu hệ thống.

                </p>


                <div class="home-ai-features">

                    <div class="home-ai-feature">

                        <i class="bi bi-check-circle-fill"></i>

                        <span>
                            Hỗ trợ tìm hiểu
                            dịch vụ bảo dưỡng.
                        </span>

                    </div>


                    <div class="home-ai-feature">

                        <i class="bi bi-check-circle-fill"></i>

                        <span>
                            Tra cứu dữ liệu bảo dưỡng
                            theo quyền người dùng.
                        </span>

                    </div>


                    <div class="home-ai-feature">

                        <i class="bi bi-check-circle-fill"></i>

                        <span>
                            Hướng tới tích hợp
                            LLM, RAG và dữ liệu
                            nghiệp vụ AutoCare.
                        </span>

                    </div>

                </div>

            </div>


            <div
                class="home-ai-card"
                data-tilt
            >

                <div class="home-ai-icon">

                    <i class="bi bi-robot"></i>

                </div>


                <h3>
                    AutoCare AI
                </h3>


                <p>

                    Một trợ lý số được thiết kế
                    dành riêng cho hệ thống
                    quản lý và hỗ trợ bảo dưỡng
                    ô tô AutoCare Long Biên.

                </p>


                <div class="home-ai-status">

                    <i class="bi bi-hourglass-split"></i>

                    Sắp tích hợp

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
        CONTACT
    ====================================================== --}}
    <section
        class="home-contact"
        id="contact"
        data-reveal
    >

        <div class="home-section-heading mb-0">

            <div class="home-section-label">
                Liên hệ
            </div>

            <h2>
                AutoCare Long Biên
            </h2>

            <p>
                Hệ thống thông tin quản lý
                và hỗ trợ bảo dưỡng ô tô
                tại khu vực Long Biên, Hà Nội.
            </p>

        </div>


        <div class="home-contact-grid">

            <div class="home-contact-item">

                <i class="bi bi-geo-alt home-contact-icon"></i>

                <div class="home-contact-title">
                    Khu vực phục vụ
                </div>

                <div class="home-contact-text">
                    Long Biên, Hà Nội
                </div>

            </div>


            <div class="home-contact-item">

                <i class="bi bi-calendar-check home-contact-icon"></i>

                <div class="home-contact-title">
                    Đặt lịch trực tuyến
                </div>

                <div class="home-contact-text">
                    Chủ động chọn xe,
                    dịch vụ và thời gian.
                </div>

            </div>


            <div class="home-contact-item">

                <i class="bi bi-shield-check home-contact-icon"></i>

                <div class="home-contact-title">
                    Quản lý tập trung
                </div>

                <div class="home-contact-text">
                    Theo dõi xe,
                    lịch sử và hóa đơn
                    trên cùng hệ thống.
                </div>

            </div>

        </div>

    </section>

</div>

@endsection