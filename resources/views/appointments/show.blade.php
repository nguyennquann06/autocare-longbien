<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Chi tiết lịch hẹn - AutoCare Long Biên
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
            max-width: 900px;
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
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow:
                0 2px 10px rgba(0, 0, 0, 0.07);
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

        .code {
            color: #6b7280;
            margin-bottom: 8px;
        }

        .status {
            display: inline-block;
            padding: 8px 13px;
            border-radius: 20px;
            font-weight: bold;
            margin-bottom: 20px;
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
                repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 25px 0;
        }

        .info-box {
            background: #f9fafb;
            padding: 16px;
            border-radius: 8px;
        }

        .label {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .value {
            font-weight: bold;
        }

        .section {
            margin-top: 30px;
        }

        .service-item {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 13px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .note {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            line-height: 1.6;
        }

        .cancel-button {
            background: #dc2626;
            color: white;
            border: none;
            padding: 11px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }

        .cancel-button:hover {
            background: #b91c1c;
        }

        .actions {
            margin-top: 30px;
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

            <a href="{{ route('home') }}">
                Trang chủ
            </a>

        </div>

    </header>


    <main class="container">

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


        <article class="card">

            <div class="code">

                Mã lịch hẹn:

                <strong>
                    {{ $appointment->appointment_code }}
                </strong>

            </div>


            <h1>
                Chi tiết lịch hẹn
            </h1>


            <span
                class="status {{ $statusClass }}"
            >
                {{ $statusText }}
            </span>


            <div class="info-grid">

                <div class="info-box">

                    <div class="label">
                        Phương tiện
                    </div>

                    <div class="value">

                        {{ $appointment->vehicle->brand->name }}

                        {{ $appointment->vehicle->vehicleModel->name }}

                    </div>

                </div>


                <div class="info-box">

                    <div class="label">
                        Biển số
                    </div>

                    <div class="value">
                        {{ $appointment->vehicle->license_plate }}
                    </div>

                </div>


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
                        Giờ hẹn
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
                        Tổng giá tham khảo
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


                <div class="info-box">

                    <div class="label">
                        Thời gian dự kiến
                    </div>

                    <div class="value">

                        {{
                            $appointment
                                ->estimated_duration_minutes
                        }} phút

                    </div>

                </div>

            </div>


            <section class="section">

                <h2>
                    Thông tin liên hệ
                </h2>

                <p>
                    <strong>Họ tên:</strong>
                    {{ $appointment->contact_name }}
                </p>

                <p>
                    <strong>Số điện thoại:</strong>
                    {{ $appointment->contact_phone }}
                </p>

                <p>
                    <strong>Email:</strong>

                    {{
                        $appointment->contact_email
                        ?? 'Không có'
                    }}
                </p>

            </section>


            <section class="section">

                <h2>
                    Dịch vụ đã chọn
                </h2>


                @foreach ($appointment->services as $service)

                    <div class="service-item">

                        <div>
                            {{ $service->name }}
                        </div>


                        <strong>

                            {{
                                number_format(
                                    $service->pivot->price,
                                    0,
                                    ',',
                                    '.'
                                )
                            }} đ

                        </strong>

                    </div>

                @endforeach

            </section>


            @if ($appointment->customer_note)

                <section class="section">

                    <h2>
                        Ghi chú của khách hàng
                    </h2>

                    <div class="note">
                        {{ $appointment->customer_note }}
                    </div>

                </section>

            @endif


            @if ($appointment->staff_note)

                <section class="section">

                    <h2>
                        Phản hồi của nhân viên
                    </h2>

                    <div class="note">
                        {{ $appointment->staff_note }}
                    </div>

                </section>

            @endif


            <div class="actions">

                @if ($appointment->status === 'PENDING')

                    <form
                        method="POST"
                        action="{{ route(
                            'appointments.cancel',
                            $appointment->id
                        ) }}"
                        onsubmit="
                            return confirm(
                                'Bạn có chắc chắn muốn hủy lịch hẹn này không?'
                            );
                        "
                    >

                        @csrf

                        @method('PATCH')


                        <button
                            type="submit"
                            class="cancel-button"
                        >
                            Hủy lịch hẹn
                        </button>

                    </form>

                @endif

            </div>


            <a
                href="{{ route('appointments.index') }}"
                class="back"
            >
                ← Quay lại Lịch hẹn của tôi
            </a>

        </article>

    </main>

</body>

</html>