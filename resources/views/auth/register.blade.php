@extends('layouts.app')


@section(
    'title',
    'Đăng ký tài khoản - AutoCare Long Biên'
)


@push('styles')

<style>
    .register-wrapper {
        width: 100%;
        max-width: 1280px;

        margin: 0 auto;

        padding:
            10px 20px 35px;
    }


    .register-shell {
        position: relative;

        overflow: hidden;

        min-height: 720px;

        display: grid;

        grid-template-columns:
            0.95fr 1.05fr;

        border:
            1px solid
            rgba(255, 255, 255, 0.85);

        border-radius: 30px;

        background:
            rgba(255, 255, 255, 0.87);

        box-shadow:
            0 35px 100px
            rgba(15, 23, 42, 0.17);

        backdrop-filter:
            blur(22px);
    }


    /* =====================================================
       LEFT SHOWCASE
       ===================================================== */

    .register-showcase {
        position: relative;

        overflow: hidden;

        padding: 52px;

        color: white;

        background:
            linear-gradient(
                145deg,
                #06101e 0%,
                #102454 45%,
                #164ec4 100%
            );
    }


    .register-showcase::before {
        content: "";

        position: absolute;

        width: 470px;
        height: 470px;

        top: -250px;
        right: -210px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(34, 211, 238, 0.42),
                transparent 67%
            );

        animation:
            registerFloatOne
            11s ease-in-out infinite;
    }


    .register-showcase::after {
        content: "";

        position: absolute;

        width: 400px;
        height: 400px;

        bottom: -230px;
        left: -190px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(124, 58, 237, 0.38),
                transparent 68%
            );

        animation:
            registerFloatTwo
            13s ease-in-out infinite;
    }


    .register-showcase-content {
        position: relative;

        z-index: 3;

        height: 100%;

        display: flex;

        flex-direction: column;
    }


    .register-chip {
        width: fit-content;

        display: inline-flex;

        align-items: center;

        gap: 9px;

        padding:
            8px 13px;

        border:
            1px solid
            rgba(255, 255, 255, 0.14);

        border-radius: 999px;

        background:
            rgba(255, 255, 255, 0.08);

        backdrop-filter:
            blur(14px);

        color: #dbeafe;

        font-size: 11px;

        font-weight: 800;

        letter-spacing: 0.08em;

        text-transform: uppercase;
    }


    .register-chip-dot {
        width: 8px;
        height: 8px;

        border-radius: 50%;

        background: #67e8f9;

        box-shadow:
            0 0 14px #67e8f9;

        animation:
            registerPulse
            1.8s ease-in-out infinite;
    }


    .register-heading {
        max-width: 520px;

        margin-top: 40px;

        color: white;

        font-size:
            clamp(
                2.5rem,
                5vw,
                4.6rem
            );

        font-weight: 900;

        line-height: 0.98;

        letter-spacing: -0.065em;
    }


    .register-heading span {
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


    .register-description {
        max-width: 500px;

        margin-top: 23px;

        color: #cbd5e1;

        font-size: 15px;

        line-height: 1.8;
    }


    /* =====================================================
       ROADMAP
       ===================================================== */

    .register-roadmap {
        margin-top: 30px;
    }


    .register-roadmap-item {
        position: relative;

        display: flex;

        align-items: flex-start;

        gap: 14px;

        padding: 12px 0;
    }


    .register-roadmap-item:not(:last-child)::after {
        content: "";

        position: absolute;

        left: 19px;
        top: 47px;

        width: 2px;

        height:
            calc(100% - 17px);

        background:
            linear-gradient(
                to bottom,
                #38bdf8,
                rgba(56, 189, 248, 0.08)
            );
    }


    .register-step {
        position: relative;

        z-index: 2;

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
                #38bdf8,
                #2563eb
            );

        box-shadow:
            0 8px 20px
            rgba(37, 99, 235, 0.28);

        font-weight: 900;
    }


    .register-roadmap-title {
        color: white;

        font-size: 13px;

        font-weight: 850;
    }


    .register-roadmap-text {
        margin-top: 4px;

        color: #9fb2cf;

        font-size: 11px;

        line-height: 1.55;
    }


    .register-showcase-footer {
        margin-top: auto;

        padding-top: 30px;

        color: #93a8c6;

        font-size: 10px;

        font-weight: 700;

        letter-spacing: 0.08em;

        text-transform: uppercase;
    }


    /* =====================================================
       FORM PANEL
       ===================================================== */

    .register-form-panel {
        display: flex;

        align-items: center;

        padding:
            48px 58px;
    }


    .register-form-container {
        width: 100%;

        max-width: 520px;

        margin: auto;
    }


    .register-form-icon {
        width: 62px;
        height: 62px;

        display: flex;

        align-items: center;

        justify-content: center;

        margin-bottom: 23px;

        border-radius: 19px;

        color: white;

        background:
            linear-gradient(
                135deg,
                #22c1dc,
                #2563eb,
                #7c3aed
            );

        box-shadow:
            0 16px 36px
            rgba(37, 99, 235, 0.28);

        font-size: 25px;

        transform:
            rotate(4deg);
    }


    .register-form-title {
        margin: 0;

        color: #0f172a;

        font-size: 35px;

        font-weight: 900;

        letter-spacing: -0.05em;
    }


    .register-form-subtitle {
        margin:
            9px 0 28px;

        color: #64748b;

        line-height: 1.65;
    }


    /* =====================================================
       ALERT
       ===================================================== */

    .register-alert {
        display: flex;

        align-items: flex-start;

        gap: 11px;

        padding:
            14px 16px;

        margin-bottom: 20px;

        border-radius: 14px;

        font-size: 13px;

        line-height: 1.55;
    }


    .register-alert-success {
        color: #065f46;

        background:
            linear-gradient(
                135deg,
                #ecfdf5,
                #f0fdf4
            );

        border:
            1px solid
            #bbf7d0;
    }


    .register-alert-danger {
        color: #991b1b;

        background:
            linear-gradient(
                135deg,
                #fef2f2,
                #fff7f7
            );

        border:
            1px solid
            #fecaca;
    }


    .register-alert ul {
        margin:
            6px 0 0;

        padding-left: 18px;
    }


    /* =====================================================
       FORM
       ===================================================== */

    .register-field {
        margin-bottom: 17px;
    }


    .register-label {
        display: flex;

        align-items: center;

        gap: 7px;

        margin-bottom: 8px;

        color: #334155;

        font-size: 13px;

        font-weight: 800;
    }


    .register-input-wrapper {
        position: relative;
    }


    .register-input-icon {
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


    .register-control {
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


    .register-control:hover {
        border-color: #93c5fd;

        background: white;
    }


    .register-control:focus {
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


    .register-password-toggle {
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
            all 0.2s ease;
    }


    .register-password-toggle:hover {
        color: #2563eb;

        background: #eff6ff;
    }


    /* =====================================================
       PASSWORD STRENGTH
       ===================================================== */

    .password-strength {
        margin-top: 9px;
    }


    .password-strength-track {
        height: 5px;

        overflow: hidden;

        border-radius: 999px;

        background: #e5e7eb;
    }


    .password-strength-bar {
        width: 0;
        height: 100%;

        border-radius: 999px;

        background: #ef4444;

        transition:
            width 0.3s ease,
            background 0.3s ease;
    }


    .password-strength-text {
        margin-top: 5px;

        color: #94a3b8;

        font-size: 10px;

        font-weight: 700;
    }


    /* =====================================================
       SUBMIT
       ===================================================== */

    .register-submit {
        position: relative;

        overflow: hidden;

        width: 100%;
        height: 53px;

        margin-top: 8px;

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
                #0ea5e9,
                #2563eb,
                #7c3aed
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


    .register-submit:hover {
        transform:
            translateY(-3px);

        background-position:
            100% 0;

        box-shadow:
            0 20px 45px
            rgba(37, 99, 235, 0.35);
    }


    .register-submit::after {
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


    .register-submit:hover::after {
        left: 120%;
    }


    /* =====================================================
       FOOT LINKS
       ===================================================== */

    .register-divider {
        position: relative;

        margin: 25px 0;

        color: #94a3b8;

        text-align: center;

        font-size: 11px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: 0.08em;
    }


    .register-divider::before {
        content: "";

        position: absolute;

        top: 50%;
        left: 0;

        width: 100%;
        height: 1px;

        background: #e5eaf1;
    }


    .register-divider span {
        position: relative;

        z-index: 2;

        padding:
            0 12px;

        background:
            rgba(255, 255, 255, 0.94);
    }


    .register-login-link {
        min-height: 47px;

        display: flex;

        align-items: center;

        justify-content: center;

        gap: 8px;

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


    .register-login-link:hover {
        color: #1d4ed8;

        border-color: #93c5fd;

        background: #eff6ff;

        transform:
            translateY(-2px);
    }


    .register-home-link {
        display: inline-flex;

        align-items: center;

        gap: 7px;

        margin-top: 21px;

        color: #64748b;

        text-decoration: none;

        font-size: 12px;

        font-weight: 700;

        transition:
            color 0.2s ease,
            transform 0.2s ease;
    }


    .register-home-link:hover {
        color: #2563eb;

        transform:
            translateX(-3px);
    }


    /* =====================================================
       RESPONSIVE
       ===================================================== */

    @media (max-width: 991px) {

        .register-shell {
            grid-template-columns: 1fr;
        }


        .register-showcase {
            padding: 40px;
        }


        .register-form-panel {
            padding: 44px;
        }

    }


    @media (max-width: 575px) {

        .register-wrapper {
            padding:
                0 12px 20px;
        }


        .register-shell {
            border-radius: 22px;
        }


        .register-showcase {
            padding:
                29px 22px;
        }


        .register-heading {
            margin-top: 28px;

            font-size: 2.45rem;
        }


        .register-form-panel {
            padding:
                35px 22px;
        }


        .register-form-title {
            font-size: 29px;
        }

    }


    /* =====================================================
       ANIMATION
       ===================================================== */

    @keyframes registerPulse {

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


    @keyframes registerFloatOne {

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
                    -28px,
                    20px,
                    0
                );
        }

    }


    @keyframes registerFloatTwo {

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
                    28px,
                    -22px,
                    0
                );
        }

    }
</style>

@endpush


@section('content')

<div
    class="register-wrapper"
    data-reveal="zoom"
>

    <section class="register-shell">

        {{-- =========================================
            SHOWCASE
        ========================================== --}}
        <div class="register-showcase">

            <div class="register-showcase-content">

                <div class="register-chip">

                    <span class="register-chip-dot"></span>

                    Join AutoCare

                </div>


                <h1 class="register-heading">

                    Bắt đầu hành trình
                    <span>chăm sóc xe.</span>

                </h1>


                <p class="register-description">

                    Tạo tài khoản để quản lý phương tiện,
                    đặt lịch bảo dưỡng, theo dõi lịch sử
                    chăm sóc xe và sử dụng các tiện ích
                    thông minh của AutoCare Long Biên.

                </p>


                <div class="register-roadmap">

                    <div class="register-roadmap-item">

                        <div class="register-step">
                            1
                        </div>

                        <div>

                            <div class="register-roadmap-title">
                                Tạo tài khoản
                            </div>

                            <div class="register-roadmap-text">
                                Thiết lập tài khoản khách hàng
                                của riêng bạn.
                            </div>

                        </div>

                    </div>


                    <div class="register-roadmap-item">

                        <div class="register-step">
                            2
                        </div>

                        <div>

                            <div class="register-roadmap-title">
                                Thêm phương tiện
                            </div>

                            <div class="register-roadmap-text">
                                Quản lý hãng xe, dòng xe,
                                biển số và ODO.
                            </div>

                        </div>

                    </div>


                    <div class="register-roadmap-item">

                        <div class="register-step">
                            3
                        </div>

                        <div>

                            <div class="register-roadmap-title">
                                Đặt lịch bảo dưỡng
                            </div>

                            <div class="register-roadmap-text">
                                Chọn dịch vụ và thời gian
                                phù hợp với nhu cầu.
                            </div>

                        </div>

                    </div>


                    <div class="register-roadmap-item">

                        <div class="register-step">

                            <i class="bi bi-stars"></i>

                        </div>

                        <div>

                            <div class="register-roadmap-title">
                                AI Assistant
                            </div>

                            <div class="register-roadmap-text">
                                Hướng tới trải nghiệm hỗ trợ
                                và tư vấn bảo dưỡng bằng AI.
                            </div>

                        </div>

                    </div>

                </div>


                <div class="register-showcase-footer">

                    Smart maintenance · Better driving

                </div>

            </div>

        </div>


        {{-- =========================================
            REGISTER FORM
        ========================================== --}}
        <div class="register-form-panel">

            <div class="register-form-container">

                <div class="register-form-icon">

                    <i class="bi bi-person-plus-fill"></i>

                </div>


                <h2 class="register-form-title">
                    Tạo tài khoản.
                </h2>


                <p class="register-form-subtitle">

                    Chỉ mất một phút để bắt đầu
                    sử dụng AutoCare Long Biên.

                </p>


                @if (session('success'))

                    <div
                        class="
                            register-alert
                            register-alert-success
                        "
                    >

                        <i class="bi bi-check-circle-fill"></i>

                        <div>
                            {{ session('success') }}
                        </div>

                    </div>

                @endif


                @if ($errors->any())

                    <div
                        class="
                            register-alert
                            register-alert-danger
                        "
                    >

                        <i class="bi bi-exclamation-triangle-fill"></i>

                        <div>

                            <strong>
                                Vui lòng kiểm tra lại thông tin
                            </strong>


                            <ul>

                                @foreach ($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    </div>

                @endif


                <form
                    method="POST"
                    action="{{ route('register.submit') }}"
                >

                    @csrf


                    {{-- HỌ TÊN --}}
                    <div class="register-field">

                        <label
                            for="name"
                            class="register-label"
                        >

                            <i class="bi bi-person"></i>

                            Họ và tên

                        </label>


                        <div class="register-input-wrapper">

                            <i
                                class="
                                    bi
                                    bi-person-fill
                                    register-input-icon
                                "
                            ></i>


                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="register-control"
                                value="{{ old('name') }}"
                                placeholder="Nguyễn Văn A"
                                autocomplete="name"
                                required
                                autofocus
                            >

                        </div>

                    </div>


                    {{-- EMAIL --}}
                    <div class="register-field">

                        <label
                            for="email"
                            class="register-label"
                        >

                            <i class="bi bi-envelope"></i>

                            Email

                        </label>


                        <div class="register-input-wrapper">

                            <i
                                class="
                                    bi
                                    bi-envelope-fill
                                    register-input-icon
                                "
                            ></i>


                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="register-control"
                                value="{{ old('email') }}"
                                placeholder="example@email.com"
                                autocomplete="email"
                                required
                            >

                        </div>

                    </div>


                    {{-- PASSWORD --}}
                    <div class="register-field">

                        <label
                            for="password"
                            class="register-label"
                        >

                            <i class="bi bi-shield-lock"></i>

                            Mật khẩu

                        </label>


                        <div class="register-input-wrapper">

                            <i
                                class="
                                    bi
                                    bi-lock-fill
                                    register-input-icon
                                "
                            ></i>


                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="register-control"
                                placeholder="Tối thiểu 6 ký tự"
                                autocomplete="new-password"
                                required
                            >


                            <button
                                type="button"
                                class="register-password-toggle"
                                data-password-toggle="password"
                                aria-label="Hiển thị mật khẩu"
                            >

                                <i class="bi bi-eye"></i>

                            </button>

                        </div>


                        <div class="password-strength">

                            <div class="password-strength-track">

                                <div
                                    id="passwordStrengthBar"
                                    class="password-strength-bar"
                                ></div>

                            </div>


                            <div
                                id="passwordStrengthText"
                                class="password-strength-text"
                            >
                                Nhập ít nhất 6 ký tự.
                            </div>

                        </div>

                    </div>


                    {{-- PASSWORD CONFIRMATION --}}
                    <div class="register-field">

                        <label
                            for="password_confirmation"
                            class="register-label"
                        >

                            <i class="bi bi-shield-check"></i>

                            Nhập lại mật khẩu

                        </label>


                        <div class="register-input-wrapper">

                            <i
                                class="
                                    bi
                                    bi-lock-fill
                                    register-input-icon
                                "
                            ></i>


                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="register-control"
                                placeholder="Nhập lại mật khẩu"
                                autocomplete="new-password"
                                required
                            >


                            <button
                                type="button"
                                class="register-password-toggle"
                                data-password-toggle="password_confirmation"
                                aria-label="Hiển thị mật khẩu"
                            >

                                <i class="bi bi-eye"></i>

                            </button>

                        </div>

                    </div>


                    <button
                        type="submit"
                        class="register-submit"
                    >

                        <span>
                            Tạo tài khoản
                        </span>

                        <i class="bi bi-arrow-right"></i>

                    </button>

                </form>


                <div class="register-divider">

                    <span>
                        Đã có tài khoản?
                    </span>

                </div>


                <a
                    href="{{ route('login') }}"
                    class="register-login-link"
                >

                    <i class="bi bi-box-arrow-in-right"></i>

                    Đăng nhập vào AutoCare

                </a>


                <a
                    href="{{ route('home') }}"
                    class="register-home-link"
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
                            button.querySelector('i');


                        if (!input) {
                            return;
                        }


                        if (
                            input.type === 'password'
                        ) {

                            input.type = 'text';

                            icon.className =
                                'bi bi-eye-slash';

                        } else {

                            input.type = 'password';

                            icon.className =
                                'bi bi-eye';

                        }

                    }
                );

            }
        );


    const passwordInput =
        document.getElementById(
            'password'
        );


    const strengthBar =
        document.getElementById(
            'passwordStrengthBar'
        );


    const strengthText =
        document.getElementById(
            'passwordStrengthText'
        );


    if (
        passwordInput
        &&
        strengthBar
        &&
        strengthText
    ) {

        passwordInput.addEventListener(
            'input',
            function () {

                const value =
                    passwordInput.value;


                let score = 0;


                if (value.length >= 6) {
                    score++;
                }


                if (value.length >= 10) {
                    score++;
                }


                if (
                    /[A-Z]/.test(value)
                    &&
                    /[a-z]/.test(value)
                ) {
                    score++;
                }


                if (
                    /\d/.test(value)
                ) {
                    score++;
                }


                if (
                    /[^A-Za-z0-9]/.test(value)
                ) {
                    score++;
                }


                if (!value.length) {

                    strengthBar.style.width =
                        '0%';

                    strengthText.textContent =
                        'Nhập ít nhất 6 ký tự.';

                    return;
                }


                if (score <= 1) {

                    strengthBar.style.width =
                        '25%';

                    strengthBar.style.background =
                        '#ef4444';

                    strengthText.textContent =
                        'Mật khẩu yếu';

                } else if (score <= 3) {

                    strengthBar.style.width =
                        '60%';

                    strengthBar.style.background =
                        '#f59e0b';

                    strengthText.textContent =
                        'Mật khẩu trung bình';

                } else {

                    strengthBar.style.width =
                        '100%';

                    strengthBar.style.background =
                        '#10b981';

                    strengthText.textContent =
                        'Mật khẩu mạnh';

                }

            }
        );

    }
</script>

@endpush