<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        AutoCare Long Biên
    </title>

    <link
        rel="stylesheet"
        href="{{ asset('css/home.css') }}"
    >
</head>

<body>

    {{-- =========================
        HEADER
    ========================== --}}
    <header class="header">

        <div class="container header-container">

            <a
                href="{{ route('home') }}"
                class="logo"
            >
                AutoCare
                <span>Long Biên</span>
            </a>


            <nav class="nav">

                <a
                    href="{{ route('home') }}"
                    class="nav-link"
                >
                    Trang chủ
                </a>


                <a
                    href="{{ route('services.index') }}"
                    class="nav-link"
                >
                    Dịch vụ
                </a>


                {{-- Đặt lịch thật --}}
                <a
                    href="{{ route('appointments.create') }}"
                    class="nav-link"
                >
                    Đặt lịch
                </a>


                <a
                    href="#ai-chatbot"
                    class="nav-link"
                >
                    AI Chatbot
                </a>


                <a
                    href="#contact"
                    class="nav-link"
                >
                    Liên hệ
                </a>

            </nav>


            <a
                href="{{ route('login') }}"
                class="login-button"
            >
                Đăng nhập
            </a>

        </div>

    </header>


    {{-- =========================
        MAIN
    ========================== --}}
    <main>

        {{-- =========================
            HERO
        ========================== --}}
        <section class="hero">

            <div class="container hero-container">

                <div class="hero-content">

                    <p class="hero-label">
                        CHĂM SÓC XE CHUYÊN NGHIỆP
                    </p>


                    <h1>
                        Đồng hành cùng bạn
                        trên mọi hành trình
                    </h1>


                    <p class="hero-description">

                        AutoCare Long Biên cung cấp
                        các dịch vụ kiểm tra,
                        bảo dưỡng và chăm sóc ô tô,
                        giúp khách hàng quản lý
                        phương tiện và lịch sử
                        bảo dưỡng thuận tiện hơn.

                    </p>


                    <div class="hero-actions">

                        {{-- Đặt lịch thật --}}
                        <a
                            href="{{ route('appointments.create') }}"
                            class="primary-button"
                        >
                            Đặt lịch bảo dưỡng
                        </a>


                        <a
                            href="{{ route('services.index') }}"
                            class="secondary-button"
                        >
                            Xem dịch vụ
                        </a>

                    </div>

                </div>


                <div class="hero-card">

                    <div class="hero-card-icon">
                        🚗
                    </div>


                    <h3>
                        AutoCare Long Biên
                    </h3>


                    <p>
                        Quản lý phương tiện,
                        bảo dưỡng và chăm sóc xe
                        thuận tiện trên một hệ thống.
                    </p>

                </div>

            </div>

        </section>


        {{-- =========================
            SERVICES
        ========================== --}}
        <section
            class="services-section"
            id="services"
        >

            <div class="container">

                <div class="section-heading">

                    <p class="section-label">
                        DỊCH VỤ
                    </p>


                    <h2>
                        Dịch vụ chăm sóc và bảo dưỡng
                    </h2>


                    <p>
                        Các hạng mục phổ biến giúp
                        phương tiện vận hành an toàn
                        và ổn định.
                    </p>

                </div>


                <div class="service-list">

                    <div class="service-card">

                        <div class="service-icon">
                            🔧
                        </div>

                        <h3>
                            Bảo dưỡng định kỳ
                        </h3>

                        <p>
                            Kiểm tra và bảo dưỡng xe
                            theo số kilomet hoặc
                            thời gian sử dụng.
                        </p>

                    </div>


                    <div class="service-card">

                        <div class="service-icon">
                            🛢️
                        </div>

                        <h3>
                            Dầu nhớt và bộ lọc
                        </h3>

                        <p>
                            Thay dầu động cơ,
                            lọc dầu, lọc gió và
                            các hạng mục liên quan.
                        </p>

                    </div>


                    <div class="service-card">

                        <div class="service-icon">
                            🛞
                        </div>

                        <h3>
                            Lốp và hệ thống phanh
                        </h3>

                        <p>
                            Kiểm tra lốp, đảo lốp,
                            cân bằng bánh xe và
                            hệ thống phanh.
                        </p>

                    </div>


                    <div class="service-card">

                        <div class="service-icon">
                            🔋
                        </div>

                        <h3>
                            Điện và ắc quy
                        </h3>

                        <p>
                            Kiểm tra hệ thống điện,
                            ắc quy và các thiết bị
                            điện trên xe.
                        </p>

                    </div>

                </div>


                <div
                    style="
                        text-align: center;
                        margin-top: 35px;
                    "
                >

                    <a
                        href="{{ route('services.index') }}"
                        class="primary-button"
                    >
                        Xem tất cả dịch vụ
                    </a>

                </div>

            </div>

        </section>


        {{-- =========================
            BOOKING
        ========================== --}}
        <section
            class="booking-section"
            id="booking"
        >

            <div class="container">

                <div class="section-heading">

                    <p class="section-label">
                        ĐẶT LỊCH
                    </p>


                    <h2>
                        Chủ động đặt lịch bảo dưỡng
                    </h2>


                    <p>
                        Lựa chọn phương tiện,
                        dịch vụ và thời gian phù hợp
                        để gửi yêu cầu đặt lịch.
                    </p>

                </div>


                <div class="booking-placeholder">

                    <h3>
                        Đặt lịch bảo dưỡng trực tuyến
                    </h3>


                    <p>
                        Đăng nhập để lựa chọn xe,
                        dịch vụ và thời gian
                        bảo dưỡng mong muốn.
                    </p>


                    <br>


                    <a
                        href="{{ route('appointments.create') }}"
                        class="primary-button"
                    >
                        Đặt lịch ngay
                    </a>

                </div>

            </div>

        </section>


        {{-- =========================
            AI CHATBOT
        ========================== --}}
        <section
            class="ai-section"
            id="ai-chatbot"
        >

            <div class="container ai-container">

                <div class="ai-content">

                    <p class="section-label">
                        AI CHATBOT
                    </p>


                    <h2>
                        Trợ lý hỗ trợ bảo dưỡng ô tô
                    </h2>


                    <p>
                        AI Chatbot sẽ hỗ trợ người dùng
                        tìm hiểu dịch vụ, tham khảo
                        lịch bảo dưỡng và khai thác
                        thông tin liên quan đến
                        phương tiện.
                    </p>


                    <ul>

                        <li>
                            Tư vấn thông tin dịch vụ
                            bảo dưỡng.
                        </li>

                        <li>
                            Hỗ trợ tra cứu lịch sử
                            bảo dưỡng.
                        </li>

                        <li>
                            Gợi ý các hạng mục cần
                            kiểm tra dựa trên dữ liệu xe.
                        </li>

                    </ul>

                </div>


                <div class="ai-card">

                    <div class="ai-icon">
                        🤖
                    </div>


                    <h3>
                        AutoCare AI
                    </h3>


                    <p>
                        Trợ lý thông minh cho khách hàng
                        sử dụng dịch vụ AutoCare.
                    </p>

                </div>

            </div>

        </section>


        {{-- =========================
            CONTACT
        ========================== --}}
        <section
            class="contact-section"
            id="contact"
        >

            <div class="container">

                <div class="section-heading">

                    <p class="section-label">
                        LIÊN HỆ
                    </p>


                    <h2>
                        AutoCare Long Biên
                    </h2>


                    <p>
                        Hệ thống quản lý và hỗ trợ
                        bảo dưỡng ô tô tại khu vực
                        Long Biên, Hà Nội.
                    </p>

                </div>

            </div>

        </section>

    </main>


    {{-- =========================
        FOOTER
    ========================== --}}
    <footer class="footer">

        <div class="container footer-container">

            <div>

                <strong>
                    AutoCare Long Biên
                </strong>

                <p>
                    Hệ thống quản lý và hỗ trợ
                    bảo dưỡng ô tô.
                </p>

            </div>


            <div>

                <p>
                    © {{ date('Y') }}
                    AutoCare Long Biên
                </p>

            </div>

        </div>

    </footer>

</body>

</html>