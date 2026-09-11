<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Lịch sử bảo dưỡng - AutoCare Long Biên
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
            padding: 20px 30px;
        }

        .header-inner {
            max-width: 1100px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
        }

        .header a {
            color: white;
            text-decoration: none;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .page-description {
            color: #6b7280;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .empty {
            background: white;
            padding: 50px 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow:
                0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .history-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow:
                0 2px 10px rgba(0, 0, 0, 0.07);
        }

        .card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            flex-wrap: wrap;
        }

        .code {
            color: #6b7280;
            font-size: 14px;
        }

        .vehicle {
            font-size: 21px;
            font-weight: bold;
            margin-top: 7px;
        }

        .license {
            margin-top: 5px;
            color: #4b5563;
        }

        .status {
            background: #ecfdf5;
            color: #047857;
            padding: 7px 13px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .info-grid {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .info-box {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
        }

        .label {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .value {
            font-weight: bold;
        }

        .button {
            display: inline-block;
            background: #111827;
            color: white;
            text-decoration: none;
            padding: 11px 17px;
            border-radius: 6px;
            margin-top: 20px;
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            color: #111827;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <header class="header">

        <div class="header-inner">

            <div class="logo">
                AutoCare Long Biên
            </div>

            <a href="{{ route('vehicles.index') }}">
                Xe của tôi
            </a>

        </div>

    </header>


    <main class="container">

        <h1>
            Lịch sử bảo dưỡng
        </h1>

        <p class="page-description">
            Theo dõi các lần bảo dưỡng đã hoàn thành,
            số kilomet khi tiếp nhận xe,
            dịch vụ đã thực hiện và kỹ thuật viên phụ trách.
        </p>


        @if ($serviceOrders->isEmpty())

            <div class="empty">

                <h2>
                    Chưa có lịch sử bảo dưỡng
                </h2>

                <p>
                    Các phiếu bảo dưỡng đã hoàn thành
                    sẽ được hiển thị tại đây.
                </p>

            </div>

        @else

            @foreach ($serviceOrders as $serviceOrder)

                <article class="history-card">

                    <div class="card-top">

                        <div>

                            <div class="code">

                                Mã phiếu:

                                <strong>
                                    {{ $serviceOrder->order_code }}
                                </strong>

                            </div>


                            <div class="vehicle">

                                {{ $serviceOrder->vehicle->brand->name }}

                                {{ $serviceOrder->vehicle->vehicleModel->name }}

                            </div>


                            <div class="license">

                                Biển số:

                                <strong>
                                    {{ $serviceOrder->vehicle->license_plate }}
                                </strong>

                            </div>

                        </div>


                        <span class="status">
                            Hoàn thành
                        </span>

                    </div>


                    <div class="info-grid">

                        <div class="info-box">

                            <div class="label">
                                Ngày hoàn thành
                            </div>

                            <div class="value">

                                {{
                                    $serviceOrder->completed_at
                                        ? $serviceOrder
                                            ->completed_at
                                            ->format('d/m/Y H:i')
                                        : 'Chưa cập nhật'
                                }}

                            </div>

                        </div>


                        <div class="info-box">

                            <div class="label">
                                ODO bảo dưỡng
                            </div>

                            <div class="value">

                                {{
                                    number_format(
                                        $serviceOrder->received_mileage
                                    )
                                }} km

                            </div>

                        </div>


                        <div class="info-box">

                            <div class="label">
                                Số hạng mục
                            </div>

                            <div class="value">

                                {{
                                    $serviceOrder
                                        ->items
                                        ->count()
                                }}

                            </div>

                        </div>


                        <div class="info-box">

                            <div class="label">
                                Kỹ thuật viên
                            </div>

                            <div class="value">

                                {{
                                    $serviceOrder->technician->name
                                    ?? 'Không xác định'
                                }}

                            </div>

                        </div>


                        <div class="info-box">

                            <div class="label">
                                Tổng chi phí
                            </div>

                            <div class="value">

                                {{
                                    number_format(
                                        $serviceOrder->total_amount,
                                        0,
                                        ',',
                                        '.'
                                    )
                                }} đ

                            </div>

                        </div>

                    </div>


                    <a
                        href="{{ route(
                            'maintenance-history.show',
                            $serviceOrder->id
                        ) }}"
                        class="button"
                    >
                        Xem chi tiết
                    </a>

                </article>

            @endforeach

        @endif


        <a
            href="{{ route('vehicles.index') }}"
            class="back"
        >
            ← Quay lại Xe của tôi
        </a>

    </main>

</body>

</html>