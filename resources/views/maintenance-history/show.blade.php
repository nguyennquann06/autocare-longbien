<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Chi tiết bảo dưỡng - AutoCare Long Biên
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

        .code {
            color: #6b7280;
        }

        .status {
            display: inline-block;
            background: #ecfdf5;
            color: #047857;
            padding: 8px 14px;
            border-radius: 20px;
            font-weight: bold;
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
            border-bottom: 1px solid #e5e7eb;
            padding: 16px 0;
        }

        .service-top {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .service-name {
            font-weight: bold;
        }

        .service-status {
            color: #047857;
            margin-top: 7px;
            font-size: 14px;
        }

        .note {
            margin-top: 10px;
            background: #f9fafb;
            padding: 14px;
            border-radius: 7px;
            line-height: 1.6;
        }

        .total-box {
            background: #111827;
            color: white;
            margin-top: 25px;
            padding: 20px;
            border-radius: 9px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 6px 0;
        }

        .grand-total {
            margin-top: 10px;
            padding-top: 12px;
            border-top: 1px solid #4b5563;
            font-size: 20px;
            font-weight: bold;
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
                AutoCare Long Biên
            </div>

            <a href="{{ route('maintenance-history.index') }}">
                Lịch sử bảo dưỡng
            </a>

        </div>

    </header>


    <main class="container">

        <article class="card">

            <div class="code">

                Mã phiếu:

                <strong>
                    {{ $serviceOrder->order_code }}
                </strong>

            </div>


            <h1>
                Chi tiết bảo dưỡng
            </h1>


            <span class="status">
                Hoàn thành
            </span>


            <div class="info-grid">

                <div class="info-box">

                    <div class="label">
                        Xe
                    </div>

                    <div class="value">

                        {{ $serviceOrder->vehicle->brand->name }}

                        {{ $serviceOrder->vehicle->vehicleModel->name }}

                    </div>

                </div>


                <div class="info-box">

                    <div class="label">
                        Biển số
                    </div>

                    <div class="value">
                        {{ $serviceOrder->vehicle->license_plate }}
                    </div>

                </div>


                <div class="info-box">

                    <div class="label">
                        ODO khi bảo dưỡng
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
                        Tiếp nhận
                    </div>

                    <div class="value">

                        {{
                            $serviceOrder->received_at
                                ? $serviceOrder
                                    ->received_at
                                    ->format('d/m/Y H:i')
                                : 'Chưa cập nhật'
                        }}

                    </div>

                </div>


                <div class="info-box">

                    <div class="label">
                        Hoàn thành
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

            </div>


            @if ($serviceOrder->vehicle_condition)

                <section class="section">

                    <h2>
                        Tình trạng xe khi tiếp nhận
                    </h2>

                    <div class="note">
                        {{ $serviceOrder->vehicle_condition }}
                    </div>

                </section>

            @endif


            @if ($serviceOrder->diagnosis)

                <section class="section">

                    <h2>
                        Chẩn đoán
                    </h2>

                    <div class="note">
                        {{ $serviceOrder->diagnosis }}
                    </div>

                </section>

            @endif


            <section class="section">

                <h2>
                    Các hạng mục đã thực hiện
                </h2>


                @foreach ($serviceOrder->items as $item)

                    <div class="service-item">

                        <div class="service-top">

                            <div class="service-name">
                                {{ $item->service_name }}
                            </div>


                            <strong>

                                {{
                                    number_format(
                                        $item->line_total,
                                        0,
                                        ',',
                                        '.'
                                    )
                                }} đ

                            </strong>

                        </div>


                        <div class="service-status">
                            ✓ Đã hoàn thành
                        </div>


                        @if ($item->technician_note)

                            <div class="note">

                                <strong>
                                    Ghi chú kỹ thuật:
                                </strong>

                                {{ $item->technician_note }}

                            </div>

                        @endif

                    </div>

                @endforeach

            </section>


            @if ($serviceOrder->technician_note)

                <section class="section">

                    <h2>
                        Kết luận của kỹ thuật viên
                    </h2>

                    <div class="note">
                        {{ $serviceOrder->technician_note }}
                    </div>

                </section>

            @endif


            <div class="total-box">

                <div class="total-row">

                    <span>
                        Dịch vụ
                    </span>

                    <strong>

                        {{
                            number_format(
                                $serviceOrder->service_total,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </strong>

                </div>


                <div class="total-row">

                    <span>
                        Phụ tùng
                    </span>

                    <strong>

                        {{
                            number_format(
                                $serviceOrder->parts_total,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </strong>

                </div>


                <div class="total-row grand-total">

                    <span>
                        Tổng cộng
                    </span>

                    <span>

                        {{
                            number_format(
                                $serviceOrder->total_amount,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </span>

                </div>

            </div>


            <a
                href="{{ route('maintenance-history.index') }}"
                class="back"
            >
                ← Quay lại lịch sử bảo dưỡng
            </a>

        </article>

    </main>

</body>

</html>