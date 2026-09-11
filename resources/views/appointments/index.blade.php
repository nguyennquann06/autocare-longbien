<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Lịch hẹn của tôi - AutoCare Long Biên
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

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .page-header h1 {
            margin: 0 0 8px;
        }

        .page-header p {
            margin: 0;
            color: #6b7280;
        }

        .new-button {
            background: #111827;
            color: white;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 7px;
        }

        .success {
            background: #ecfdf5;
            color: #065f46;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .error {
            background: #fef2f2;
            color: #991b1b;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .empty {
            background: white;
            padding: 50px;
            text-align: center;
            border-radius: 12px;
        }

        .appointment-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 22px;
            box-shadow:
                0 2px 10px rgba(0, 0, 0, 0.07);
        }

        .appointment-top {
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
            margin-top: 5px;
        }

        .license {
            color: #6b7280;
            margin-top: 5px;
        }

        .status {
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .pending {
            background: #fff7ed;
            color: #c2410c;
        }

        .confirmed {
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

        .actions {
            margin-top: 20px;
        }

        .detail-button {
            display: inline-block;
            background: #111827;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            text-decoration: none;
        }

        .back {
            display: inline-block;
            margin-top: 15px;
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

            <a href="{{ route('home') }}">
                Trang chủ
            </a>

        </div>

    </header>


    <main class="container">

        <div class="page-header">

            <div>

                <h1>
                    Lịch hẹn của tôi
                </h1>

                <p>
                    Theo dõi các lịch bảo dưỡng
                    của phương tiện.
                </p>

            </div>


            <a
                href="{{ route('appointments.create') }}"
                class="new-button"
            >
                + Đặt lịch mới
            </a>

        </div>


        @if (session('success'))

            <div class="success">
                {{ session('success') }}
            </div>

        @endif


        @if (session('error'))

            <div class="error">
                {{ session('error') }}
            </div>

        @endif


        @if ($appointments->isEmpty())

            <div class="empty">

                <h2>
                    Bạn chưa có lịch hẹn nào
                </h2>

                <a
                    href="{{ route('appointments.create') }}"
                    class="new-button"
                >
                    Đặt lịch bảo dưỡng
                </a>

            </div>

        @else

            @foreach ($appointments as $appointment)

                @php

                    $statusText = match (
                        $appointment->status
                    ) {
                        'PENDING' =>
                            'Chờ xác nhận',

                        'CONFIRMED' =>
                            'Đã xác nhận',

                        'IN_PROGRESS' =>
                            'Đang thực hiện',

                        'COMPLETED' =>
                            'Hoàn thành',

                        'CANCELLED' =>
                            'Đã hủy',

                        default =>
                            $appointment->status,
                    };


                    $statusClass = match (
                        $appointment->status
                    ) {
                        'PENDING' =>
                            'pending',

                        'CONFIRMED' =>
                            'confirmed',

                        'IN_PROGRESS' =>
                            'progress',

                        'COMPLETED' =>
                            'completed',

                        'CANCELLED' =>
                            'cancelled',

                        default =>
                            'pending',
                    };

                @endphp


                <article class="appointment-card">

                    <div class="appointment-top">

                        <div>

                            <div class="code">

                                Mã:

                                <strong>
                                    {{ $appointment->appointment_code }}
                                </strong>

                            </div>


                            <div class="vehicle">

                                {{ $appointment->vehicle->brand->name }}

                                {{ $appointment->vehicle->vehicleModel->name }}

                            </div>


                            <div class="license">

                                {{
                                    $appointment
                                        ->vehicle
                                        ->license_plate
                                }}

                            </div>

                        </div>


                        <span
                            class="status {{ $statusClass }}"
                        >
                            {{ $statusText }}
                        </span>

                    </div>


                    <div class="info-grid">

                        <div class="info-box">

                            <div class="label">
                                Ngày
                            </div>

                            <div class="value">

                                {{
                                    $appointment
                                        ->appointment_date
                                        ->format('d/m/Y')
                                }}

                            </div>

                        </div>


                        <div class="info-box">

                            <div class="label">
                                Giờ
                            </div>

                            <div class="value">

                                {{
                                    substr(
                                        $appointment
                                            ->appointment_time,
                                        0,
                                        5
                                    )
                                }}

                            </div>

                        </div>


                        <div class="info-box">

                            <div class="label">
                                Dịch vụ
                            </div>

                            <div class="value">

                                {{
                                    $appointment
                                        ->services
                                        ->count()
                                }}

                            </div>

                        </div>


                        <div class="info-box">

                            <div class="label">
                                Giá tham khảo
                            </div>

                            <div class="value">

                                {{
                                    number_format(
                                        $appointment
                                            ->estimated_total,
                                        0,
                                        ',',
                                        '.'
                                    )
                                }} đ

                            </div>

                        </div>

                    </div>


                    <div class="actions">

                        <a
                            href="{{ route(
                                'appointments.show',
                                $appointment->id
                            ) }}"
                            class="detail-button"
                        >
                            Xem chi tiết
                        </a>

                    </div>

                </article>

            @endforeach

        @endif


        <a
            href="{{ route('home') }}"
            class="back"
        >
            ← Quay lại trang chủ
        </a>

    </main>

</body>

</html>