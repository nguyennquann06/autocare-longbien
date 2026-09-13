<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <meta
        name="theme-color"
        content="#07111f"
    >


    <title>
        @yield(
            'title',
            'AutoCare Long Biên'
        )
    </title>


    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])


    @stack('styles')


    <style>
        /* =====================================================
           AUTOCARE GLOBAL TOAST SYSTEM
           ===================================================== */

        .autocare-toast-stack {
            position: fixed;

            top: 88px;
            right: 22px;

            z-index: 999999;

            width:
                min(
                    410px,
                    calc(100vw - 28px)
                );

            display: flex;

            flex-direction: column;

            gap: 12px;

            pointer-events: none;
        }


        .autocare-toast {
            position: relative;

            overflow: hidden;

            display: flex;

            align-items: flex-start;

            gap: 12px;

            padding:
                15px 46px 15px 15px;

            border:
                1px solid
                rgba(255, 255, 255, 0.85);

            border-radius: 16px;

            background:
                rgba(255, 255, 255, 0.97);

            box-shadow:
                0 18px 50px
                rgba(15, 23, 42, 0.18);

            backdrop-filter:
                blur(18px);

            pointer-events: auto;

            animation:
                autocareToastIn
                0.32s
                cubic-bezier(
                    0.2,
                    0.8,
                    0.2,
                    1
                );
        }


        .autocare-toast::after {
            content: "";

            position: absolute;

            left: 0;
            bottom: 0;

            width: 100%;
            height: 3px;

            background:
                var(
                    --toast-accent,
                    #3b82f6
                );
        }


        .autocare-toast.is-hiding {
            animation:
                autocareToastOut
                0.24s
                ease
                forwards;
        }


        .autocare-toast-icon {
            width: 38px;
            height: 38px;

            flex: 0 0 auto;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 12px;

            color: white;

            background:
                var(
                    --toast-accent,
                    #3b82f6
                );

            box-shadow:
                0 8px 18px
                color-mix(
                    in srgb,
                    var(
                        --toast-accent,
                        #3b82f6
                    )
                    25%,
                    transparent
                );

            font-size: 17px;
        }


        .autocare-toast-body {
            min-width: 0;

            flex: 1;
        }


        .autocare-toast-title {
            color: #0f172a;

            font-size: 13px;

            font-weight: 900;

            line-height: 1.35;
        }


        .autocare-toast-message {
            margin-top: 4px;

            color: #475569;

            font-size: 12px;

            line-height: 1.6;

            overflow-wrap: anywhere;
        }


        .autocare-toast-list {
            margin:
                7px 0 0;

            padding-left: 18px;

            color: #475569;

            font-size: 12px;

            line-height: 1.55;
        }


        .autocare-toast-list li
        + li {
            margin-top: 4px;
        }


        .autocare-toast-close {
            position: absolute;

            top: 10px;
            right: 10px;

            width: 30px;
            height: 30px;

            display: flex;

            align-items: center;

            justify-content: center;

            border: none;

            border-radius: 9px;

            color: #64748b;

            background:
                transparent;

            cursor: pointer;

            transition:
                color 0.2s ease,
                background 0.2s ease;
        }


        .autocare-toast-close:hover {
            color: #0f172a;

            background: #f1f5f9;
        }


        .autocare-toast-success {
            --toast-accent:
                #10b981;
        }


        .autocare-toast-error {
            --toast-accent:
                #ef4444;
        }


        .autocare-toast-warning {
            --toast-accent:
                #f59e0b;
        }


        .autocare-toast-info {
            --toast-accent:
                #3b82f6;
        }


        @keyframes autocareToastIn {
            from {
                opacity: 0;

                transform:
                    translateX(24px)
                    scale(0.97);
            }

            to {
                opacity: 1;

                transform:
                    translateX(0)
                    scale(1);
            }
        }


        @keyframes autocareToastOut {
            from {
                opacity: 1;

                transform:
                    translateX(0)
                    scale(1);
            }

            to {
                opacity: 0;

                transform:
                    translateX(28px)
                    scale(0.97);
            }
        }


        @media (max-width: 575px) {
            .autocare-toast-stack {
                top: 74px;
                right: 14px;
                left: 14px;

                width: auto;
            }


            .autocare-toast {
                padding:
                    14px 43px 14px 14px;
            }
        }


        @media (
            prefers-reduced-motion:
            reduce
        ) {
            .autocare-toast,
            .autocare-toast.is-hiding {
                animation: none;
            }
        }
    </style>

</head>

<body>

    {{-- =====================================================
        TOP ANIMATED ACCENT
    ====================================================== --}}
    <div
        class="autocare-top-accent"
        aria-hidden="true"
    ></div>


    {{-- =====================================================
        DECORATIVE BACKGROUND
    ====================================================== --}}
    <div
        class="autocare-background"
        aria-hidden="true"
    >

        <div class="autocare-grid"></div>

        <div
            class="
                autocare-orb
                autocare-orb-one
            "
        ></div>

        <div
            class="
                autocare-orb
                autocare-orb-two
            "
        ></div>

        <div
            class="
                autocare-orb
                autocare-orb-three
            "
        ></div>

    </div>


    {{-- =====================================================
        ROLE NAVIGATION
    ====================================================== --}}
    @include('partials.role-nav')


    {{-- =====================================================
        GLOBAL TOASTS
    ====================================================== --}}
    <div
        id="autocareToastStack"
        class="autocare-toast-stack"
        aria-live="polite"
        aria-atomic="true"
    >

        {{-- SUCCESS --}}
        @if (session('success'))

            <div
                class="
                    autocare-toast
                    autocare-toast-success
                "
                data-autocare-toast
                data-auto-dismiss="5500"
                role="status"
            >

                <div class="autocare-toast-icon">
                    <i class="bi bi-check-lg"></i>
                </div>


                <div class="autocare-toast-body">

                    <div class="autocare-toast-title">
                        Thành công
                    </div>

                    <div class="autocare-toast-message">
                        {{ session('success') }}
                    </div>

                </div>


                <button
                    type="button"
                    class="autocare-toast-close"
                    data-toast-close
                    aria-label="Đóng thông báo"
                >
                    <i class="bi bi-x-lg"></i>
                </button>

            </div>

        @endif


        {{-- ERROR FLASH --}}
        @if (session('error'))

            <div
                class="
                    autocare-toast
                    autocare-toast-error
                "
                data-autocare-toast
                data-auto-dismiss="7500"
                role="alert"
            >

                <div class="autocare-toast-icon">
                    <i class="bi bi-exclamation-lg"></i>
                </div>


                <div class="autocare-toast-body">

                    <div class="autocare-toast-title">
                        Không thể thực hiện
                    </div>

                    <div class="autocare-toast-message">
                        {{ session('error') }}
                    </div>

                </div>


                <button
                    type="button"
                    class="autocare-toast-close"
                    data-toast-close
                    aria-label="Đóng thông báo"
                >
                    <i class="bi bi-x-lg"></i>
                </button>

            </div>

        @endif


        {{-- WARNING --}}
        @if (session('warning'))

            <div
                class="
                    autocare-toast
                    autocare-toast-warning
                "
                data-autocare-toast
                data-auto-dismiss="7000"
                role="status"
            >

                <div class="autocare-toast-icon">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>


                <div class="autocare-toast-body">

                    <div class="autocare-toast-title">
                        Lưu ý
                    </div>

                    <div class="autocare-toast-message">
                        {{ session('warning') }}
                    </div>

                </div>


                <button
                    type="button"
                    class="autocare-toast-close"
                    data-toast-close
                    aria-label="Đóng thông báo"
                >
                    <i class="bi bi-x-lg"></i>
                </button>

            </div>

        @endif


        {{-- INFO --}}
        @if (session('info'))

            <div
                class="
                    autocare-toast
                    autocare-toast-info
                "
                data-autocare-toast
                data-auto-dismiss="6000"
                role="status"
            >

                <div class="autocare-toast-icon">
                    <i class="bi bi-info-lg"></i>
                </div>


                <div class="autocare-toast-body">

                    <div class="autocare-toast-title">
                        Thông tin
                    </div>

                    <div class="autocare-toast-message">
                        {{ session('info') }}
                    </div>

                </div>


                <button
                    type="button"
                    class="autocare-toast-close"
                    data-toast-close
                    aria-label="Đóng thông báo"
                >
                    <i class="bi bi-x-lg"></i>
                </button>

            </div>

        @endif


        {{-- VALIDATION ERRORS --}}
        @if ($errors->any())

            @php
                $validationErrors =
                    array_values(
                        array_unique(
                            $errors->all()
                        )
                    );
            @endphp

            <div
                class="
                    autocare-toast
                    autocare-toast-error
                "
                data-autocare-toast
                data-auto-dismiss="9000"
                role="alert"
            >

                <div class="autocare-toast-icon">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>


                <div class="autocare-toast-body">

                    <div class="autocare-toast-title">
                        Thông tin chưa hợp lệ
                    </div>


                    <ul class="autocare-toast-list">

                        @foreach (
                            $validationErrors
                            as $validationError
                        )

                            <li>
                                {{ $validationError }}
                            </li>

                        @endforeach

                    </ul>

                </div>


                <button
                    type="button"
                    class="autocare-toast-close"
                    data-toast-close
                    aria-label="Đóng thông báo"
                >
                    <i class="bi bi-x-lg"></i>
                </button>

            </div>

        @endif

    </div>


    {{-- =====================================================
        PAGE CONTENT
    ====================================================== --}}
    <main class="autocare-main">

        @yield('content')

    </main>


    {{-- =====================================================
        FOOTER
    ====================================================== --}}
    <footer
        class="
            autocare-footer
            py-4
            mt-4
        "
    >

        <div class="container">

            <div
                class="
                    d-flex
                    flex-column
                    flex-lg-row
                    justify-content-between
                    align-items-lg-center
                    gap-3
                "
            >

                <div>

                    <div
                        class="
                            autocare-footer-brand
                            fs-5
                        "
                    >
                        AutoCare Long Biên
                    </div>

                    <div
                        class="
                            small
                            mt-1
                        "
                    >
                        Hệ thống thông tin quản lý
                        và hỗ trợ bảo dưỡng ô tô
                    </div>

                </div>


                <div
                    class="
                        text-lg-end
                        small
                    "
                >

                    <div>
                        Long Biên, Hà Nội
                    </div>

                    <div class="mt-1">
                        © {{ date('Y') }}
                        AutoCare Long Biên
                    </div>

                </div>

            </div>

        </div>

    </footer>


    {{-- =====================================================
        SCROLL TO TOP
    ====================================================== --}}
    <button
        id="scrollTopButton"
        type="button"
        aria-label="Lên đầu trang"
        title="Lên đầu trang"
    >
        <i class="bi bi-arrow-up"></i>
    </button>


    {{-- =====================================================
        GLOBAL TOAST SCRIPT
    ====================================================== --}}
    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function () {
                const toasts =
                    document.querySelectorAll(
                        '[data-autocare-toast]'
                    );


                toasts.forEach(
                    function (toast) {
                        let removed =
                            false;


                        const removeToast =
                            function () {
                                if (removed) {
                                    return;
                                }


                                removed =
                                    true;


                                toast.classList.add(
                                    'is-hiding'
                                );


                                window.setTimeout(
                                    function () {
                                        toast.remove();
                                    },
                                    260
                                );
                            };


                        const closeButton =
                            toast.querySelector(
                                '[data-toast-close]'
                            );


                        if (closeButton) {
                            closeButton
                                .addEventListener(
                                    'click',
                                    removeToast
                                );
                        }


                        const dismissDelay =
                            Number.parseInt(
                                toast.dataset
                                    .autoDismiss
                                || '0',
                                10
                            );


                        if (
                            Number.isFinite(
                                dismissDelay
                            )
                            &&
                            dismissDelay > 0
                        ) {
                            window.setTimeout(
                                removeToast,
                                dismissDelay
                            );
                        }
                    }
                );
            }
        );
    </script>


    @stack('scripts')

</body>

</html>