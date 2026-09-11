<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Công việc kỹ thuật viên - AutoCare Long Biên
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
        }

        .success {
            background: #ecfdf5;
            color: #065f46;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .empty {
            background: white;
            border-radius: 12px;
            padding: 50px;
            text-align: center;
        }

        .order-card {
            background: white;
            padding: 24px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow:
                0 2px 10px rgba(0, 0, 0, 0.07);
        }

        .top {
            display: flex;
            justify-content: space-between;
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
            margin-top: 6px;
        }

        .license {
            color: #6b7280;
            margin-top: 5px;
        }

        .status {
            padding: 7px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 13px;
        }

        .received {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .progress {
            background: #f5f3ff;
            color: #6d28d9;
        }

        .completed {
            background: #ecfdf5;
            color: #047857;
        }

        .cancelled {
            background: #fef2f2;
            color: #b91c1c;
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
            margin-top: 20px;
            background: #111827;
            color: white;
            padding: 10px 16px;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>
</head>

<body>

    <header class="header">

        <div class="header-inner">

            <div class="logo">
                AutoCare - Kỹ thuật viên
            </div>

            <a href="{{ route('home') }}">
                Trang chủ
            </a>

        </div>

    </header>


    <main class="container">

        <h1>
            Công việc được phân công
        </h1>

        <p class="page-description">
            Danh sách các phiếu bảo dưỡng
            được giao cho bạn thực hiện.
        </p>


        @if (session('success'))

            <div class="success">
                {{ session('success') }}
            </div>

        @endif


        @if ($serviceOrders->isEmpty())

            <div class="empty">

                <h2>
                    Chưa có công việc được phân công
                </h2>

                <p>
                    Khi nhân viên tiếp nhận xe và
                    phân công cho bạn, phiếu bảo dưỡng
                    sẽ xuất hiện tại đây.
                </p>

            </div>

        @else

            @foreach ($serviceOrders as $serviceOrder)

                @php

                    $statusText = match (
                        $serviceOrder->status
                    ) {
                        'RECEIVED' =>
                            'Đã tiếp nhận',

                        'IN_PROGRESS' =>
                            'Đang thực hiện',

                        'COMPLETED' =>
                            'Hoàn thành',

                        'CANCELLED' =>
                            'Đã hủy',

                        default =>
                            $serviceOrder->status,
                    };


                    $statusClass = match (
                        $serviceOrder->status
                    ) {
                        'RECEIVED' =>
                            'received',

                        'IN_PROGRESS' =>
                            'progress',

                        'COMPLETED' =>
                            'completed',

                        'CANCELLED' =>
                            'cancelled',

                        default =>
                            'received',
                    };

                @endphp


                <article class="order-card">

                    <div class="top">

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

                                {{ $serviceOrder->vehicle->license_plate }}

                            </div>

                        </div>


                        <span class="status {{ $statusClass }}">

                            {{ $statusText }}

                        </span>

                    </div>


                    <div class="info-grid">

                        <div class="info-box">

                            <div class="label">
                                Khách hàng
                            </div>

                            <div class="value">
                                {{ $serviceOrder->customer->full_name }}
                            </div>

                        </div>


                        <div class="info-box">

                            <div class="label">
                                ODO tiếp nhận
                            </div>

                            <div class="value">

                                {{
                                    number_format(
                                        $serviceOrder
                                            ->received_mileage
                                    )
                                }} km

                            </div>

                        </div>


                        <div class="info-box">

                            <div class="label">
                                Hạng mục
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
                                Tiếp nhận lúc
                            </div>

                            <div class="value">

                                {{
                                    $serviceOrder->received_at
                                        ? $serviceOrder
                                            ->received_at
                                            ->format(
                                                'd/m/Y H:i'
                                            )
                                        : 'Chưa cập nhật'
                                }}

                            </div>

                        </div>

                    </div>


                    <a
                        href="{{ route(
                            'technician.service-orders.show',
                            $serviceOrder->id
                        ) }}"
                        class="button"
                    >
                        Xem công việc
                    </a>

                </article>

            @endforeach

        @endif

    </main>

</body>

</html>