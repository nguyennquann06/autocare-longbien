<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Quản lý lịch hẹn - AutoCare Long Biên
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
            max-width: 1200px;
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
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .page-title {
            margin-bottom: 8px;
        }

        .page-description {
            color: #6b7280;
            margin-bottom: 30px;
        }

        .empty {
            background: white;
            padding: 50px;
            border-radius: 12px;
            text-align: center;
        }

        .appointment-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow:
                0 2px 10px rgba(0, 0, 0, 0.07);
        }

        .top {
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

        .customer {
            font-size: 20px;
            font-weight: bold;
            margin-top: 7px;
        }

        .vehicle {
            margin-top: 5px;
            color: #4b5563;
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
                repeat(auto-fit, minmax(170px, 1fr));
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

        .detail-button {
            display: inline-block;
            margin-top: 20px;
            background: #111827;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 6px;
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
                AutoCare - Nhân viên
            </div>

            <a href="{{ route('home') }}">
                Trang chủ
            </a>

        </div>

    </header>


    <main class="container">

        <h1 class="page-title">
            Quản lý lịch hẹn
        </h1>

        <p class="page-description">
            Theo dõi và xử lý các lịch bảo dưỡng
            do khách hàng gửi tới hệ thống.
        </p>


        @if ($appointments->isEmpty())

            <div class="empty">
                Hiện chưa có lịch hẹn nào.
            </div>

        @else

            @foreach ($appointments as $appointment)

                @php

                    $statusText = match (
                        $appointment->status
                    ) {
                        'PENDING' => 'Chờ xác nhận',

                        'CONFIRMED' => 'Đã xác nhận',

                        'IN_PROGRESS' => 'Đang thực hiện',

                        'COMPLETED' => 'Hoàn thành',

                        'CANCELLED' => 'Đã hủy',

                        default => $appointment->status,
                    };


                    $statusClass = match (
                        $appointment->status
                    ) {
                        'PENDING' => 'pending',

                        'CONFIRMED' => 'confirmed',

                        'IN_PROGRESS' => 'progress',

                        'COMPLETED' => 'completed',

                        'CANCELLED' => 'cancelled',

                        default => 'pending',
                    };

                @endphp


                <article class="appointment-card">

                    <div class="top">

                        <div>

                            <div class="code">

                                Mã lịch:

                                <strong>
                                    {{ $appointment->appointment_code }}
                                </strong>

                            </div>


                            <div class="customer">

                                {{ $appointment->contact_name }}

                            </div>


                            <div class="vehicle">

                                {{ $appointment->vehicle->brand->name }}

                                {{ $appointment->vehicle->vehicleModel->name }}

                                -

                                {{ $appointment->vehicle->license_plate }}

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
                                Ngày hẹn
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
                                Điện thoại
                            </div>

                            <div class="value">

                                {{ $appointment->contact_phone }}

                            </div>

                        </div>


                        <div class="info-box">

                            <div class="label">
                                Số dịch vụ
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


                    <a
                        href="{{ route(
                            'staff.appointments.show',
                            $appointment->id
                        ) }}"
                        class="detail-button"
                    >
                        Xem và xử lý
                    </a>

                </article>

            @endforeach

        @endif


        <a
            href="{{ route('home') }}"
            class="back"
        >
            ← Trang chủ
        </a>

    </main>

</body>

</html>