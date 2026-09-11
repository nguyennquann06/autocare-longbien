<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        {{ $service->name }}
        - AutoCare Long Biên
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6f8;
            color: #1f2937;
        }

        .header {
            background: #111827;
            color: white;
            padding: 20px 40px;
        }

        .header-content {
            max-width: 1000px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .header a {
            color: white;
            text-decoration: none;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 50px 20px;
        }

        .service-card {
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow:
                0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .category {
            color: #d97706;
            font-weight: bold;
            margin-bottom: 10px;
        }

        h1 {
            margin-top: 0;
            color: #111827;
        }

        .description {
            color: #6b7280;
            line-height: 1.7;
            font-size: 16px;
        }

        .info-grid {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .info-box {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
        }

        .info-label {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .info-value {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
        }

        .price {
            color: #d97706;
        }

        .notice {
            margin-top: 30px;
            padding: 18px;
            background: #fff7ed;
            border-radius: 8px;
            color: #9a3412;
            line-height: 1.6;
        }

        .actions {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .button {
            display: inline-block;
            text-decoration: none;
            padding: 11px 18px;
            border-radius: 6px;
        }

        .button-primary {
            background: #111827;
            color: white;
        }

        .button-secondary {
            border: 1px solid #d1d5db;
            color: #111827;
            background: white;
        }

        .footer {
            margin-top: 50px;
            background: #111827;
            color: white;
            text-align: center;
            padding: 25px;
        }
    </style>
</head>

<body>

    <header class="header">

        <div class="header-content">

            <div class="logo">
                AutoCare Long Biên
            </div>

            <a href="{{ route('home') }}">
                Trang chủ
            </a>

        </div>

    </header>


    <main class="container">

        <article class="service-card">

            <div class="category">
                {{ $service->category->name }}
            </div>


            <h1>
                {{ $service->name }}
            </h1>


            <p class="description">

                {{
                    $service->description
                    ?? 'Thông tin dịch vụ đang được cập nhật.'
                }}

            </p>


            <div class="info-grid">

                <div class="info-box">

                    <div class="info-label">
                        Giá tham khảo
                    </div>

                    <div class="info-value price">

                        {{
                            number_format(
                                $service->base_price,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </div>

                </div>


                <div class="info-box">

                    <div class="info-label">
                        Thời gian dự kiến
                    </div>

                    <div class="info-value">

                        @if ($service->estimated_duration_minutes)

                            {{
                                $service
                                    ->estimated_duration_minutes
                            }} phút

                        @else

                            Liên hệ

                        @endif

                    </div>

                </div>


                <div class="info-box">

                    <div class="info-label">
                        Chu kỳ theo số km
                    </div>

                    <div class="info-value">

                        @if ($service->mileage_interval)

                            {{
                                number_format(
                                    $service->mileage_interval,
                                    0,
                                    ',',
                                    '.'
                                )
                            }} km

                        @else

                            Theo tình trạng xe

                        @endif

                    </div>

                </div>


                <div class="info-box">

                    <div class="info-label">
                        Chu kỳ theo thời gian
                    </div>

                    <div class="info-value">

                        @if ($service->month_interval)

                            {{
                                $service->month_interval
                            }} tháng

                        @else

                            Theo tình trạng xe

                        @endif

                    </div>

                </div>

            </div>


            <div class="notice">

                <strong>Lưu ý:</strong>

                Giá trên là giá tham khảo.
                Chi phí thực tế có thể thay đổi
                tùy theo dòng xe, tình trạng xe,
                phụ tùng và hạng mục phát sinh
                trong quá trình kiểm tra.

            </div>


            <div class="actions">

                <a
                    href="{{ route('services.index') }}"
                    class="button button-primary"
                >
                    ← Danh sách dịch vụ
                </a>

                <a
                    href="{{ route('home') }}"
                    class="button button-secondary"
                >
                    Trang chủ
                </a>

            </div>

        </article>

    </main>


    <footer class="footer">
        AutoCare Long Biên
    </footer>

</body>

</html>