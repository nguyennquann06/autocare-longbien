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


    @stack('scripts')

</body>

</html>