@extends('layouts.app')


@section(
    'title',
    'Đăng nhập - AutoCare Long Biên'
)


@push('styles')

<style>
    .auth-wrapper {
        width: 100%;
        max-width: 1240px;
        margin: 0 auto;
        padding: 10px 20px 30px;
    }


    .auth-shell {
        position: relative;
        overflow: hidden;

        min-height: 680px;

        display: grid;
        grid-template-columns: 1.08fr 0.92fr;

        border:
            1px solid
            rgba(255, 255, 255, 0.82);

        border-radius: 30px;

        background:
            rgba(255, 255, 255, 0.84);

        box-shadow:
            0 35px 100px
            rgba(15, 23, 42, 0.17);

        backdrop-filter:
            blur(22px);
    }


    .auth-showcase {
        position: relative;
        overflow: hidden;

        padding: 56px;

        color: white;

        background:
            linear-gradient(
                135deg,
                #06101e 0%,
                #0a2454 42%,
                #075ecb 100%
            );
    }


    .auth-showcase::before {
        content: "";

        position: absolute;

        width: 480px;
        height: 480px;

        top: -260px;
        right: -190px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, 0.42),
                transparent 67%
            );

        animation:
            authFloatOne
            10s ease-in-out infinite;
    }


    .auth-showcase::after {
        content: "";

        position: absolute;

        width: 400px;
        height: 400px;

        bottom: -250px;
        left: -160px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(124, 58, 237, 0.36),
                transparent 68%
            );

        animation:
            authFloatTwo
            13s ease-in-out infinite;
    }


    .auth-showcase-content {
        position: relative;
        z-index: 3;

        height: 100%;

        display: flex;
        flex-direction: column;
    }


    .auth-brand-chip {
        width: fit-content;

        display: inline-flex;
        align-items: center;
        gap: 9px;

        padding: 8px 13px;

        border:
            1px solid
            rgba(255, 255, 255, 0.14);

        border-radius: 999px;

        background:
            rgba(255, 255, 255, 0.08);

        backdrop-filter:
            blur(14px);

        color: #dbeafe;

        font-size: 12px;
        font-weight: 800;

        letter-spacing: 0.08em;

        text-transform: uppercase;
    }


    .auth-live-dot {
        width: 8px;
        height: 8px;

        border-radius: 50%;

        background: #67e8f9;

        box-shadow:
            0 0 14px
            rgba(103, 232, 249, 0.95);

        animation:
            authPulse
            1.8s ease-in-out infinite;
    }


    .auth-showcase-title {
        max-width: 540px;

        margin-top: 42px;

        color: white;

        font-size:
            clamp(
                2.5rem,
                5vw,
                4.7rem
            );

        font-weight: 900;

        line-height: 0.98;

        letter-spacing: -0.065em;
    }


    .auth-showcase-title span {
        color: transparent;

        background:
            linear-gradient(
                90deg,
                #67e8f9,
                #93c5fd,
                #c4b5fd
            );

        background-clip: text;

        -webkit-background-clip: text;
    }


    .auth-showcase-description {
        max-width: 520px;

        margin-top: 24px;

        color: #cbd5e1;

        font-size: 16px;

        line-height: 1.8;
    }


    .auth-feature-grid {
        display: grid;

        grid-template-columns:
            repeat(
                2,
                minmax(0, 1fr)
            );

        gap: 14px;

        margin-top: 32px;
    }


    .auth-feature {
        position: relative;
        overflow: hidden;

        padding: 17px;

        border:
            1px solid
            rgba(255, 255, 255, 0.10);

        border-radius: 17px;

        background:
            rgba(255, 255, 255, 0.065);

        backdrop-filter:
            blur(12px);

        transition:
            transform 0.25s ease,
            background 0.25s ease,
            border-color 0.25s ease;
    }


    .auth-feature:hover {
        transform:
            translateY(-5px);

        background:
            rgba(255, 255, 255, 0.11);

        border-color:
            rgba(103, 232, 249, 0.28);
    }


    .auth-feature-icon {
        width: 40px;
        height: 40px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-bottom: 11px;

        border-radius: 12px;

        color: #67e8f9;

        background:
            rgba(34, 211, 238, 0.12);

        font-size: 18px;
    }


    .auth-feature-title {
        color: white;

        font-size: 13px;
        font-weight: 800;
    }


    .auth-feature-text {
        margin-top: 4px;

        color: #9fb2cf;

        font-size: 11px;

        line-height: 1.5;
    }


    .auth-showcase-footer {
        margin-top: auto;
        padding-top: 35px;

        color: #93a8c6;

        font-size: 11px;

        letter-spacing: 0.05em;

        text-transform: uppercase;
    }


    .auth-form-panel {
        position: relative;

        display: flex;
        align-items: center;

        padding: 58px;
    }


    .auth-form-container {
        width: 100%;
        max-width: 470px;

        margin: auto;
    }


    .auth-form-icon {
        width: 62px;
        height: 62px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-bottom: 24px;

        border-radius: 19px;

        color: white;

        background:
            linear-gradient(
                135deg,
                #1683ff,
                #4f46e5
            );

        box-shadow:
            0 16px 35px
            rgba(37, 99, 235, 0.28);

        font-size: 25px;

        transform:
            rotate(-4deg);
    }


    .auth-form-title {
        margin: 0;

        color: #0f172a;

        font-size: 35px;

        font-weight: 900;

        letter-spacing: -0.05em;
    }


    .auth-form-subtitle {
        margin: 9px 0 30px;

        color: #64748b;

        line-height: 1.65;
    }


    .auth-field {
        margin-bottom: 19px;
    }


    .auth-label {
        display: flex;

        align-items: center;

        gap: 7px;

        margin-bottom: 8px;

        color: #334155;

        font-size: 13px;

        font-weight: 800;
    }


    .auth-input-wrapper {
        position: relative;
    }


    .auth-input-icon {
        position: absolute;

        top: 50%;
        left: 15px;

        z-index: 2;

        transform:
            translateY(-50%);

        color: #6d8bb5;

        font-size: 16px;

        pointer-events: none;
    }


    .auth-control {
        width: 100%;
        height: 52px;

        padding:
            0 48px 0 45px;

        border:
            1px solid
            #d6e0eb;

        border-radius: 14px;

        outline: none;

        color: #0f172a;

        background:
            rgba(248, 251, 255, 0.92);

        font-size: 14px;

        transition:
            border-color 0.22s ease,
            box-shadow 0.22s ease,
            transform 0.22s ease,
            background 0.22s ease;
    }


    .auth-control:hover {
        border-color: #93c5fd;

        background: white;
    }


    .auth-control:focus {
        border-color: #3b82f6;

        background: white;

        box-shadow:
            0 0 0 4px
            rgba(59, 130, 246, 0.11),

            0 12px 28px
            rgba(37, 99, 235, 0.09);

        transform:
            translateY(-1px);
    }


    .auth-control.is-invalid {
        border-color: #f87171;

        background: #fffafa;

        box-shadow:
            0 0 0 3px
            rgba(239, 68, 68, 0.07);
    }


    .auth-password-toggle {
        position: absolute;

        top: 50%;
        right: 11px;

        z-index: 3;

        width: 36px;
        height: 36px;

        display: flex;

        align-items: center;

        justify-content: center;

        transform:
            translateY(-50%);

        border: none;

        border-radius: 10px;

        color: #64748b;

        background: transparent;

        cursor: pointer;

        transition:
            color 0.2s ease,
            background 0.2s ease;
    }


    .auth-password-toggle:hover {
        color: #2563eb;

        background: #eff6ff;
    }


    .auth-options {
        display: flex;

        justify-content: space-between;

        align-items: center;

        gap: 15px;

        margin: 5px 0 23px;
    }


    .auth-check {
        display: inline-flex;

        align-items: center;

        gap: 8px;

        color: #64748b;

        font-size: 13px;

        cursor: pointer;
    }


    .auth-check input {
        width: 17px;
        height: 17px;

        accent-color: #2563eb;
    }


    .auth-submit {
        position: relative;

        overflow: hidden;

        width: 100%;
        height: 53px;

        display: flex;

        align-items: center;

        justify-content: center;

        gap: 9px;

        border: none;

        border-radius: 14px;

        color: white;

        background:
            linear-gradient(
                120deg,
                #1683ff,
                #2752d8,
                #6d28d9
            );

        background-size:
            180% 100%;

        box-shadow:
            0 15px 35px
            rgba(37, 99, 235, 0.27);

        font-size: 14px;

        font-weight: 850;

        cursor: pointer;

        transition:
            transform 0.25s ease,
            box-shadow 0.25s ease,
            background-position 0.35s ease;
    }


    .auth-submit:hover {
        transform:
            translateY(-3px);

        background-position:
            100% 0;

        box-shadow:
            0 20px 45px
            rgba(37, 99, 235, 0.35);
    }


    .auth-submit::after {
        content: "";

        position: absolute;

        top: -100%;
        left: -30%;

        width: 35%;
        height: 300%;

        transform:
            rotate(20deg);

        background:
            linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.38),
                transparent
            );

        transition:
            left 0.6s ease;
    }


    .auth-submit:hover::after {
        left: 120%;
    }


    .auth-divider {
        position: relative;

        margin: 24px 0;

        text-align: center;

        color: #94a3b8;

        font-size: 11px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: 0.08em;
    }


    .auth-divider::before {
        content: "";

        position: absolute;

        top: 50%;
        left: 0;

        width: 100%;
        height: 1px;

        background: #e5eaf1;
    }


    .auth-divider span {
        position: relative;

        z-index: 2;

        padding: 0 12px;

        background:
            rgba(255, 255, 255, 0.92);
    }


    /*
    |--------------------------------------------------------------------------
    | GOOGLE LOGIN
    |--------------------------------------------------------------------------
    */

    .auth-google {
        position: relative;

        width: 100%;
        min-height: 52px;

        display: flex;
        align-items: center;
        justify-content: center;

        gap: 12px;

        padding:
            0 18px;

        border:
            1px solid
            #d7e0ea;

        border-radius: 14px;

        color: #1e293b;

        text-decoration: none;

        background: #ffffff;

        font-size: 14px;
        font-weight: 850;

        box-shadow:
            0 8px 22px
            rgba(15, 23, 42, 0.055);

        transition:
            transform 0.22s ease,
            border-color 0.22s ease,
            box-shadow 0.22s ease,
            background 0.22s ease;
    }


    .auth-google:hover {
        color: #0f172a;

        border-color: #93c5fd;

        background:
            linear-gradient(
                180deg,
                #ffffff,
                #f8fbff
            );

        transform:
            translateY(-2px);

        box-shadow:
            0 14px 32px
            rgba(37, 99, 235, 0.11);
    }


    .auth-google:focus-visible {
        outline: none;

        border-color: #3b82f6;

        box-shadow:
            0 0 0 4px
            rgba(59, 130, 246, 0.12),

            0 14px 32px
            rgba(37, 99, 235, 0.11);
    }


    .auth-google-icon {
        width: 24px;
        height: 24px;

        flex:
            0 0 24px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        line-height: 0;
    }


    .auth-google-icon svg {
        display: block;

        width: 24px;
        height: 24px;
    }


    .auth-google-text {
        display: inline-flex;

        align-items: center;

        line-height: 1;
    }


    .auth-secondary-link {
        display: flex;

        align-items: center;

        justify-content: center;

        gap: 8px;

        min-height: 47px;

        border:
            1px solid
            #d9e2ed;

        border-radius: 13px;

        color: #334155;

        text-decoration: none;

        background:
            rgba(255, 255, 255, 0.78);

        font-size: 13px;

        font-weight: 800;

        transition:
            all 0.22s ease;
    }


    .auth-secondary-link:hover {
        color: #1d4ed8;

        border-color: #93c5fd;

        background: #eff6ff;

        transform:
            translateY(-2px);
    }


    .auth-home-link {
        display: inline-flex;

        align-items: center;

        gap: 7px;

        margin-top: 23px;

        color: #64748b;

        text-decoration: none;

        font-size: 12px;

        font-weight: 700;

        transition:
            color 0.2s ease,
            transform 0.2s ease;
    }


    .auth-home-link:hover {
        color: #2563eb;

        transform:
            translateX(-3px);
    }


    @media (max-width: 991px) {
        .auth-shell {
            grid-template-columns: 1fr;
        }


        .auth-showcase {
            min-height: 460px;

            padding: 42px;
        }


        .auth-showcase-title {
            max-width: 680px;
        }


        .auth-form-panel {
            padding: 45px;
        }
    }


    @media (max-width: 575px) {
        .auth-wrapper {
            padding:
                0 12px 20px;
        }


        .auth-shell {
            border-radius: 22px;
        }


        .auth-showcase {
            min-height: auto;

            padding: 30px 23px;
        }


        .auth-showcase-title {
            margin-top: 28px;

            font-size: 2.5rem;
        }


        .auth-feature-grid {
            grid-template-columns: 1fr;
        }


        .auth-showcase-footer {
            display: none;
        }


        .auth-form-panel {
            padding:
                36px 22px;
        }


        .auth-form-title {
            font-size: 29px;
        }


        .auth-google {
            min-height: 50px;

            font-size: 13px;
        }
    }


    @keyframes authPulse {
        0%,
        100% {
            opacity: 0.6;

            transform:
                scale(0.9);
        }

        50% {
            opacity: 1;

            transform:
                scale(1.15);
        }
    }


    @keyframes authFloatOne {
        0%,
        100% {
            transform:
                translate3d(
                    0,
                    0,
                    0
                );
        }

        50% {
            transform:
                translate3d(
                    -25px,
                    25px,
                    0
                );
        }
    }


    @keyframes authFloatTwo {
        0%,
        100% {
            transform:
                translate3d(
                    0,
                    0,
                    0
                );
        }

        50% {
            transform:
                translate3d(
                    30px,
                    -20px,
                    0
                );
        }
    }
</style>

@endpush


@section('content')

<div
    class="auth-wrapper"
    data-reveal="zoom"
>

    <section class="auth-shell">

        <div class="auth-showcase">

            <div class="auth-showcase-content">

                <div class="auth-brand-chip">

                    <span class="auth-live-dot"></span>

                    AutoCare Digital Garage

                </div>


                <h1 class="auth-showcase-title">

                    Chăm sóc xe
                    <span>thông minh hơn.</span>

                </h1>


                <p class="auth-showcase-description">

                    Một nền tảng duy nhất để quản lý
                    phương tiện, đặt lịch bảo dưỡng,
                    theo dõi lịch sử sửa chữa và nhận
                    hỗ trợ thông minh từ AutoCare.

                </p>


                <div class="auth-feature-grid">

                    <div class="auth-feature">

                        <div class="auth-feature-icon">
                            <i class="bi bi-calendar2-check"></i>
                        </div>

                        <div class="auth-feature-title">
                            Đặt lịch nhanh
                        </div>

                        <div class="auth-feature-text">
                            Chọn xe, dịch vụ và thời gian
                            chỉ trong vài thao tác.
                        </div>

                    </div>


                    <div class="auth-feature">

                        <div class="auth-feature-icon">
                            <i class="bi bi-car-front-fill"></i>
                        </div>

                        <div class="auth-feature-title">
                            Quản lý phương tiện
                        </div>

                        <div class="auth-feature-text">
                            Theo dõi ODO và thông tin
                            từng chiếc xe của bạn.
                        </div>

                    </div>


                    <div class="auth-feature">

                        <div class="auth-feature-icon">
                            <i class="bi bi-tools"></i>
                        </div>

                        <div class="auth-feature-title">
                            Lịch sử bảo dưỡng
                        </div>

                        <div class="auth-feature-text">
                            Kiểm tra toàn bộ quá trình
                            chăm sóc và sửa chữa xe.
                        </div>

                    </div>


                    <div class="auth-feature">

                        <div class="auth-feature-icon">
                            <i class="bi bi-stars"></i>
                        </div>

                        <div class="auth-feature-title">
                            AI Assistant
                        </div>

                        <div class="auth-feature-text">
                            Hỗ trợ tra cứu và tư vấn
                            bảo dưỡng với AutoCare AI.
                        </div>

                    </div>

                </div>


                <div class="auth-showcase-footer">

                    AutoCare Long Biên · Hà Nội

                </div>

            </div>

        </div>


        <div class="auth-form-panel">

            <div class="auth-form-container">

                <div class="auth-form-icon">

                    <i class="bi bi-person-lock"></i>

                </div>


                <h2 class="auth-form-title">

                    Chào mừng trở lại.

                </h2>


                <p class="auth-form-subtitle">

                    Đăng nhập để tiếp tục sử dụng
                    hệ thống AutoCare Long Biên.

                </p>


                <form
                    method="POST"
                    action="{{ route('login.submit') }}"
                    novalidate
                >

                    @csrf


                    <div class="auth-field">

                        <label
                            for="email"
                            class="auth-label"
                        >

                            <i class="bi bi-envelope"></i>

                            Email

                        </label>


                        <div class="auth-input-wrapper">

                            <i
                                class="
                                    bi
                                    bi-envelope-fill
                                    auth-input-icon
                                "
                            ></i>


                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="
                                    auth-control
                                    @error('email')
                                        is-invalid
                                    @enderror
                                "
                                value="{{ old('email') }}"
                                placeholder="example@email.com"
                                autocomplete="email"
                                maxlength="254"
                                autofocus
                                aria-invalid="{{
                                    $errors->has('email')
                                        ? 'true'
                                        : 'false'
                                }}"
                            >

                        </div>

                    </div>


                    <div class="auth-field">

                        <label
                            for="password"
                            class="auth-label"
                        >

                            <i class="bi bi-shield-lock"></i>

                            Mật khẩu

                        </label>


                        <div class="auth-input-wrapper">

                            <i
                                class="
                                    bi
                                    bi-lock-fill
                                    auth-input-icon
                                "
                            ></i>


                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="
                                    auth-control
                                    @error('password')
                                        is-invalid
                                    @enderror
                                "
                                placeholder="Nhập mật khẩu"
                                autocomplete="current-password"
                                maxlength="72"
                                aria-invalid="{{
                                    $errors->has('password')
                                        ? 'true'
                                        : 'false'
                                }}"
                            >


                            <button
                                type="button"
                                class="auth-password-toggle"
                                data-password-toggle="password"
                                aria-label="Hiển thị mật khẩu"
                            >

                                <i class="bi bi-eye"></i>

                            </button>

                        </div>

                    </div>


                    <div class="auth-options">

                        <label class="auth-check">

                            <input
                                type="checkbox"
                                id="remember"
                                name="remember"
                                @checked(
                                    old('remember')
                                )
                            >

                            <span>
                                Ghi nhớ đăng nhập
                            </span>

                        </label>

                    </div>


                    <button
                        type="submit"
                        class="auth-submit"
                    >

                        <span>
                            Đăng nhập
                        </span>

                        <i class="bi bi-arrow-right"></i>

                    </button>

                </form>


                <div class="auth-divider">

                    <span>
                        Hoặc
                    </span>

                </div>


                <a
                    href="{{ route('auth.google.redirect') }}"
                    class="auth-google"
                >

                    <span
                        class="auth-google-icon"
                        aria-hidden="true"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            role="img"
                        >

                            <path
                                fill="#4285F4"
                                d="
                                    M21.35 11.1
                                    H12.17
                                    v3.74
                                    h5.28
                                    c-.23 1.2
                                    -.92 2.22
                                    -1.96 2.91
                                    v2.42
                                    h3.17
                                    c1.86-1.71
                                    2.94-4.23
                                    2.94-7.23
                                    0-.64
                                    -.06-1.26
                                    -.17-1.84
                                    z
                                "
                            />

                            <path
                                fill="#34A853"
                                d="
                                    M12.17 22
                                    c2.66 0
                                    4.89-.88
                                    6.52-2.39
                                    l-3.17-2.42
                                    c-.88.59
                                    -2 .94
                                    -3.35.94
                                    -2.57 0
                                    -4.75-1.73
                                    -5.53-4.06
                                    H3.36
                                    v2.55
                                    A9.85 9.85 0 0 0
                                    12.17 22
                                    z
                                "
                            />

                            <path
                                fill="#FBBC05"
                                d="
                                    M6.64 14.07
                                    a5.94 5.94 0 0 1
                                    0-3.81
                                    V7.71
                                    H3.36
                                    a9.85 9.85 0 0 0
                                    0 8.91
                                    l3.28-2.55
                                    z
                                "
                            />

                            <path
                                fill="#EA4335"
                                d="
                                    M12.17 6.2
                                    c1.45 0
                                    2.75.5
                                    3.77 1.47
                                    l2.83-2.83
                                    C17.05 3.23
                                    14.82 2.25
                                    12.17 2.25
                                    a9.85 9.85 0 0 0
                                    -8.81 5.46
                                    l3.28 2.55
                                    c.78-2.33
                                    2.96-4.06
                                    5.53-4.06
                                    z
                                "
                            />

                        </svg>

                    </span>


                    <span class="auth-google-text">

                        Tiếp tục với Google

                    </span>

                </a>


                <div class="auth-divider">

                    <span>
                        Chưa có tài khoản?
                    </span>

                </div>


                <a
                    href="{{ route('register') }}"
                    class="auth-secondary-link"
                >

                    <i class="bi bi-person-plus"></i>

                    Tạo tài khoản khách hàng

                </a>


                <a
                    href="{{ route('home') }}"
                    class="auth-home-link"
                >

                    <i class="bi bi-arrow-left"></i>

                    Quay lại trang chủ

                </a>

            </div>

        </div>

    </section>

</div>

@endsection


@push('scripts')

<script>
    document
        .querySelectorAll(
            '[data-password-toggle]'
        )
        .forEach(
            function (button) {
                button.addEventListener(
                    'click',
                    function () {
                        const inputId =
                            button.getAttribute(
                                'data-password-toggle'
                            );


                        const input =
                            document.getElementById(
                                inputId
                            );


                        const icon =
                            button.querySelector(
                                'i'
                            );


                        if (!input) {
                            return;
                        }


                        if (
                            input.type
                            === 'password'
                        ) {
                            input.type =
                                'text';

                            icon.className =
                                'bi bi-eye-slash';

                            button.setAttribute(
                                'aria-label',
                                'Ẩn mật khẩu'
                            );
                        } else {
                            input.type =
                                'password';

                            icon.className =
                                'bi bi-eye';

                            button.setAttribute(
                                'aria-label',
                                'Hiển thị mật khẩu'
                            );
                        }
                    }
                );
            }
        );
</script>

@endpush