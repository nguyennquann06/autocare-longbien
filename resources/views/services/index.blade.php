<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Dịch vụ bảo dưỡng - AutoCare Long Biên
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
            max-width: 1200px;
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .page-title {
            text-align: center;
            margin-bottom: 10px;
        }

        .page-description {
            text-align: center;
            color: #6b7280;
            margin-bottom: 40px;
        }

        .category {
            margin-bottom: 45px;
        }

        .category-title {
            font-size: 24px;
            margin-bottom: 8px;
            color: #111827;
        }

        .category-description {
            color: #6b7280;
            margin-bottom: 20px;
        }

        .service-grid {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .service-card {
            background: white;
            border-radius: 10px;
            padding: 22px;
            box-shadow:
                0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .service-card h3 {
            margin-top: 0;
            margin-bottom: 10px;
        }

        .service-description {
            color: #6b7280;
            line-height: 1.6;
            min-height: 70px;
        }

        .service-info {
            margin-top: 18px;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }

        .service-info p {
            margin: 8px 0;
        }

        .price {
            font-size: 20px;
            font-weight: bold;
            color: #d97706;
        }

        .detail-button {
            display: inline-block;
            margin-top: 15px;
            background: #111827;
            color: white;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 6px;
        }

        .detail-button:hover {
            background: #374151;
        }

        .empty {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 10px;
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #111827;
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

        <h1 class="page-title">
            Dịch vụ bảo dưỡng ô tô
        </h1>

        <p class="page-description">
            Tham khảo các dịch vụ chăm sóc,
            kiểm tra và bảo dưỡng xe tại
            AutoCare Long Biên.
        </p>


        @if ($categories->isEmpty())

            <div class="empty">
                Hiện chưa có dịch vụ nào.
            </div>

        @else

            @foreach ($categories as $category)

                @if ($category->services->isNotEmpty())

                    <section class="category">

                        <h2 class="category-title">
                            {{ $category->name }}
                        </h2>


                        @if ($category->description)

                            <p class="category-description">
                                {{ $category->description }}
                            </p>

                        @endif


                        <div class="service-grid">

                            @foreach ($category->services as $service)

                                <article class="service-card">

                                    <h3>
                                        {{ $service->name }}
                                    </h3>


                                    <p class="service-description">

                                        {{
                                            $service->description
                                            ?? 'Chưa có mô tả.'
                                        }}

                                    </p>


                                    <div class="service-info">

                                        <p>
                                            <strong>
                                                Giá tham khảo:
                                            </strong>
                                        </p>

                                        <p class="price">

                                            {{
                                                number_format(
                                                    $service->base_price,
                                                    0,
                                                    ',',
                                                    '.'
                                                )
                                            }} đ

                                        </p>


                                        @if ($service->estimated_duration_minutes)

                                            <p>
                                                <strong>
                                                    Thời gian:
                                                </strong>

                                                {{
                                                    $service
                                                        ->estimated_duration_minutes
                                                }} phút
                                            </p>

                                        @endif


                                        @if ($service->mileage_interval)

                                            <p>
                                                <strong>
                                                    Chu kỳ:
                                                </strong>

                                                {{
                                                    number_format(
                                                        $service
                                                            ->mileage_interval,
                                                        0,
                                                        ',',
                                                        '.'
                                                    )
                                                }} km
                                            </p>

                                        @endif


                                        @if ($service->month_interval)

                                            <p>
                                                <strong>
                                                    Thời gian định kỳ:
                                                </strong>

                                                {{
                                                    $service
                                                        ->month_interval
                                                }} tháng
                                            </p>

                                        @endif


                                        <a
                                            class="detail-button"
                                            href="{{ route(
                                                'services.show',
                                                $service->id
                                            ) }}"
                                        >
                                            Xem chi tiết
                                        </a>

                                    </div>

                                </article>

                            @endforeach

                        </div>

                    </section>

                @endif

            @endforeach

        @endif


        <a
            class="back"
            href="{{ route('home') }}"
        >
            ← Quay lại trang chủ
        </a>

    </main>


    <footer class="footer">
        AutoCare Long Biên
    </footer>

</body>

</html>