<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Lập hóa đơn - AutoCare Long Biên
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

        .errors {
            background: #fef2f2;
            color: #991b1b;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
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
            padding: 15px;
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

        .item {
            display: grid;
            grid-template-columns: 1fr 100px 150px;
            gap: 15px;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .totals {
            background: #111827;
            color: white;
            padding: 20px;
            border-radius: 9px;
            margin-top: 25px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
        }

        .subtotal {
            border-top: 1px solid #4b5563;
            margin-top: 8px;
            padding-top: 14px;
            font-size: 18px;
            font-weight: bold;
        }

        .form-group {
            margin-top: 22px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 15px;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .button {
            margin-top: 25px;
            border: none;
            background: #111827;
            color: white;
            padding: 13px 20px;
            border-radius: 7px;
            cursor: pointer;
            font-size: 15px;
        }

        .back {
            display: inline-block;
            margin-top: 25px;
            color: #111827;
            text-decoration: none;
        }

        @media (max-width: 650px) {
            .item {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <header class="header">

        <div class="header-inner">

            <div class="logo">
                AutoCare - Lập hóa đơn
            </div>

            <a
                href="{{ route(
                    'staff.service-orders.show',
                    $serviceOrder->id
                ) }}"
            >
                Phiếu bảo dưỡng
            </a>

        </div>

    </header>


    <main class="container">

        <article class="card">

            <h1>
                Lập hóa đơn
            </h1>


            <p>
                Phiếu bảo dưỡng:

                <strong>
                    {{ $serviceOrder->order_code }}
                </strong>
            </p>


            @if ($errors->any())

                <div class="errors">

                    <ul>

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


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

            </div>


            <section class="section">

                <h2>
                    Dịch vụ
                </h2>

                @foreach ($serviceOrder->items as $item)

                    <div class="item">

                        <span>
                            {{ $item->service_name }}
                        </span>

                        <span>
                            x {{ $item->quantity }}
                        </span>

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

                @endforeach

            </section>


            <section class="section">

                <h2>
                    Phụ tùng
                </h2>

                @if ($serviceOrder->parts->isEmpty())

                    <p>
                        Không sử dụng phụ tùng.
                    </p>

                @else

                    @foreach ($serviceOrder->parts as $part)

                        <div class="item">

                            <span>
                                {{ $part->part_name }}
                            </span>

                            <span>
                                {{ $part->quantity }}
                                {{ $part->unit }}
                            </span>

                            <strong>

                                {{
                                    number_format(
                                        $part->line_total,
                                        0,
                                        ',',
                                        '.'
                                    )
                                }} đ

                            </strong>

                        </div>

                    @endforeach

                @endif

            </section>


            <div class="totals">

                <div class="total-row">

                    <span>
                        Tiền dịch vụ
                    </span>

                    <strong>

                        {{
                            number_format(
                                $serviceTotal,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </strong>

                </div>


                <div class="total-row">

                    <span>
                        Tiền phụ tùng
                    </span>

                    <strong>

                        {{
                            number_format(
                                $partsTotal,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </strong>

                </div>


                <div class="total-row subtotal">

                    <span>
                        Tổng trước giảm giá
                    </span>

                    <span>

                        {{
                            number_format(
                                $subtotal,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </span>

                </div>

            </div>


            <form
                method="POST"
                action="{{ route(
                    'staff.invoices.store',
                    $serviceOrder->id
                ) }}"
            >

                @csrf


                <div class="form-group">

                    <label for="discount_amount">
                        Giảm giá
                    </label>

                    <input
                        type="number"
                        id="discount_amount"
                        name="discount_amount"
                        min="0"
                        max="{{ $subtotal }}"
                        step="1000"
                        value="{{ old(
                            'discount_amount',
                            0
                        ) }}"
                    >

                </div>


                <div class="form-group">

                    <label for="note">
                        Ghi chú hóa đơn
                    </label>

                    <textarea
                        id="note"
                        name="note"
                        maxlength="2000"
                        placeholder="Ví dụ: Giảm giá khách hàng thân thiết..."
                    >{{ old('note') }}</textarea>

                </div>


                <button
                    type="submit"
                    class="button"
                    onclick="
                        return confirm(
                            'Xác nhận lập hóa đơn? Sau khi tạo, dữ liệu hóa đơn sẽ được lưu snapshot.'
                        );
                    "
                >
                    Lập hóa đơn
                </button>

            </form>


            <a
                href="{{ route(
                    'staff.service-orders.show',
                    $serviceOrder->id
                ) }}"
                class="back"
            >
                ← Quay lại phiếu bảo dưỡng
            </a>

        </article>

    </main>

</body>

</html>