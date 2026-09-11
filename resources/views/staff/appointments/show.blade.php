<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Xử lý lịch hẹn - AutoCare Long Biên
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
            max-width: 950px;
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
            max-width: 950px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
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
        }

        .status {
            display: inline-block;
            padding: 8px 14px;
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
            border-bottom: 1px solid #e5e7eb;
            padding: 12px 0;
        }

        .note {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            line-height: 1.6;
        }

        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            min-height: 110px;
            resize: vertical;
            font-family: Arial, sans-serif;
        }

        .action-form {
            margin-top: 25px;
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
        }

        .action-button {
            margin-top: 15px;
            border: none;
            color: white;
            padding: 12px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            background: #111827;
        }

        .back {
            display: inline-block;
            margin-top: 25px;
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

            <a href="{{ route('staff.appointments.index') }}">
                Danh sách lịch
            </a>

        </div>

    </header>


    <main class="container">

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


            $nextStatus = match (
                $appointment->status
            ) {
                'PENDING' => 'CONFIRMED',

                'CONFIRMED' => 'IN_PROGRESS',

                'IN_PROGRESS' => 'COMPLETED',

                default => null,
            };


            $nextButtonText = match (
                $appointment->status
            ) {
                'PENDING' =>
                    'Xác nhận lịch hẹn',

                'CONFIRMED' =>
                    'Bắt đầu thực hiện',

                'IN_PROGRESS' =>
                    'Hoàn thành dịch vụ',

                default =>
                    null,
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
                Xử lý lịch hẹn
            </h1>


            <span
                class="status {{ $statusClass }}"
            >
                {{ $statusText }}
            </span>


            <div class="info-grid">

                <div class="info-box">

                    <div class="label">
                        Khách hàng
                    </div>

                    <div class="value">
                        {{ $appointment->contact_name }}
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
                        Email
                    </div>

                    <div class="value">

                        {{
                            $appointment->contact_email
                            ?? 'Không có'
                        }}

                    </div>

                </div>


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
                        Giờ
                    </div>

                    <div class="value">

                        {{
                            substr(
                                $appointment->appointment_time,
                                0,
                                5
                            )
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
                                $appointment->estimated_total,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </div>

                </div>

            </div>


            <section class="section">

                <h2>
                    Dịch vụ khách đã chọn
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


            @if ($nextStatus)

                <section class="section">

                    <h2>
                        Xử lý lịch hẹn
                    </h2>


                    <form
                        method="POST"
                        action="{{ route(
                            'staff.appointments.updateStatus',
                            $appointment->id
                        ) }}"
                        class="action-form"
                    >

                        @csrf

                        @method('PATCH')


                        <input
                            type="hidden"
                            name="status"
                            value="{{ $nextStatus }}"
                        >


                        <label for="staff_note">

                            <strong>
                                Ghi chú của nhân viên
                            </strong>

                        </label>

                        <br><br>


                        <textarea
                            id="staff_note"
                            name="staff_note"
                            maxlength="1000"
                            placeholder="Ví dụ: Đã liên hệ khách hàng và xác nhận thời gian..."
                        >{{ old(
                            'staff_note',
                            $appointment->staff_note
                        ) }}</textarea>


                        <button
                            type="submit"
                            class="action-button"
                            onclick="
                                return confirm(
                                    'Bạn có chắc chắn muốn cập nhật trạng thái lịch hẹn?'
                                );
                            "
                        >
                            {{ $nextButtonText }}
                        </button>

                    </form>

                </section>

            @elseif ($appointment->status === 'COMPLETED')

                <section class="section">

                    <div class="note">
                        Lịch hẹn này đã hoàn thành.
                    </div>

                </section>

            @elseif ($appointment->status === 'CANCELLED')

                <section class="section">

                    <div class="note">
                        Khách hàng đã hủy lịch hẹn này.
                    </div>

                </section>

            @endif


            @if ($appointment->staff_note)

                <section class="section">

                    <h2>
                        Ghi chú hiện tại của nhân viên
                    </h2>

                    <div class="note">
                        {{ $appointment->staff_note }}
                    </div>

                </section>

            @endif


            <a
                href="{{ route('staff.appointments.index') }}"
                class="back"
            >
                ← Quay lại danh sách lịch hẹn
            </a>

        </article>

    </main>

</body>

</html>