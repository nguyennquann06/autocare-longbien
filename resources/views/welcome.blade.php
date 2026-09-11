@extends('layouts.app')


@section(
    'title',
    'AutoCare Long Biên'
)


@push('styles')

<style>
    .welcome-autocare {
        max-width: 1100px;
    }

    .welcome-card {
        position: relative;

        overflow: hidden;

        min-height: 520px;

        display: flex;

        align-items: center;

        justify-content: center;

        padding: 50px;

        border-radius: 30px;

        color: white;

        background:
            linear-gradient(
                120deg,
                #040b15 0%,
                #071c3a 45%,
                #0c3474 72%,
                #1677ff 100%
            );

        box-shadow:
            0 32px 90px
            rgba(20, 103, 223, 0.26);
    }

    .welcome-card::before {
        content: "";

        position: absolute;

        width: 480px;
        height: 480px;

        top: -290px;
        right: -120px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, 0.40),
                transparent 70%
            );
    }

    .welcome-card::after {
        content: "";

        position: absolute;

        width: 360px;
        height: 360px;

        left: 15%;
        bottom: -280px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(124, 58, 237, 0.35),
                transparent 70%
            );
    }

    .welcome-content {
        position: relative;

        z-index: 2;

        max-width: 760px;

        text-align: center;
    }

    .welcome-icon {
        width: 75px;
        height: 75px;

        display: flex;

        align-items: center;

        justify-content: center;

        margin:
            0 auto 23px;

        border:
            1px solid
            rgba(255, 255, 255, 0.13);

        border-radius: 22px;

        color: #67e8f9;

        background:
            rgba(255, 255, 255, 0.08);

        font-size: 32px;

        box-shadow:
            0 20px 45px
            rgba(0, 0, 0, 0.15);
    }

    .welcome-label {
        display: inline-flex;

        align-items: center;

        gap: 8px;

        margin-bottom: 15px;

        color: #bfdbfe;

        font-size: 10px;

        font-weight: 900;

        text-transform: uppercase;

        letter-spacing: 0.09em;
    }

    .welcome-label::before {
        content: "";

        width: 7px;
        height: 7px;

        border-radius: 50%;

        background: #67e8f9;

        box-shadow:
            0 0 12px #67e8f9;
    }

    .welcome-content h1 {
        margin: 0;

        color: white;

        font-size:
            clamp(
                2.6rem,
                6vw,
                5rem
            );

        font-weight: 950;

        line-height: 1;

        letter-spacing: -0.07em;
    }

    .welcome-content p {
        max-width: 650px;

        margin:
            19px auto 0;

        color: #cbd5e1;

        font-size: 13px;

        line-height: 1.85;
    }

    .welcome-actions {
        display: flex;

        align-items: center;

        justify-content: center;

        gap: 11px;

        flex-wrap: wrap;

        margin-top: 28px;
    }

    .welcome-primary,
    .welcome-secondary {
        min-height: 48px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 8px;

        padding: 0 19px;

        border-radius: 13px;

        text-decoration: none;

        font-size: 12px;

        font-weight: 900;

        transition:
            transform 0.2s ease;
    }

    .welcome-primary {
        color: #06101e;

        background:
            linear-gradient(
                135deg,
                #67e8f9,
                #bfdbfe
            );
    }

    .welcome-primary:hover {
        color: #06101e;

        transform:
            translateY(-3px);
    }

    .welcome-secondary {
        color: white;

        border:
            1px solid
            rgba(255, 255, 255, 0.15);

        background:
            rgba(255, 255, 255, 0.07);
    }

    .welcome-secondary:hover {
        color: white;

        transform:
            translateY(-3px);
    }

    @media (max-width: 575px) {
        .welcome-card {
            min-height: 450px;

            padding:
                35px 22px;

            border-radius: 23px;
        }

        .welcome-primary,
        .welcome-secondary {
            width: 100%;
        }
    }
</style>

@endpush


@section('content')

<div class="container welcome-autocare">

    <section
        class="welcome-card"
        data-reveal="zoom"
    >

        <div class="welcome-content">

            <div
                class="welcome-icon"
                data-tilt
            >

                <i class="bi bi-car-front-fill"></i>

            </div>


            <div class="welcome-label">
                AutoCare Long Biên
            </div>


            <h1>
                Hệ thống chăm sóc xe thông minh
            </h1>


            <p>

                Quản lý phương tiện,
                đặt lịch bảo dưỡng,
                theo dõi lịch sử dịch vụ
                và khai thác các tiện ích
                của AutoCare trên cùng
                một nền tảng.

            </p>


            <div class="welcome-actions">

                <a
                    href="{{ route('home') }}"
                    class="welcome-primary"
                >

                    <i class="bi bi-arrow-right-circle"></i>

                    Vào AutoCare

                </a>


                <a
                    href="{{ route('services.index') }}"
                    class="welcome-secondary"
                >

                    <i class="bi bi-tools"></i>

                    Xem dịch vụ

                </a>

            </div>

        </div>

    </section>

</div>

@endsection