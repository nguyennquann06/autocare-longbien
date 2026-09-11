<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Phiếu bảo dưỡng - AutoCare Long Biên
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
            max-width: 1000px;
            margin: auto;
            display: flex;
            justify-content: space-between;
        }

        .header a {
            color: white;
            text-decoration: none;
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
        }

        .container {
            max-width: 1000px;
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

        .success,
        .error,
        .errors,
        .note {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .success {
            background: #ecfdf5;
            color: #065f46;
        }

        .error,
        .errors {
            background: #fef2f2;
            color: #991b1b;
        }

        .note {
            background: #f9fafb;
            line-height: 1.6;
        }

        .status {
            display: inline-block;
            padding: 8px 13px;
            border-radius: 20px;
            font-weight: bold;
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
            margin-top: 35px;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 120px 160px;
            gap: 15px;
            padding: 13px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .form-box {
            background: #f9fafb;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        select,
        input,
        textarea {
            width: 100%;
            padding: 11px;
            margin: 7px 0 15px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
        }

        .button {
            display: inline-block;
            background: #111827;
            color: white;
            text-decoration: none;
            border: none;
            padding: 11px 17px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
        }

        .invoice-button {
            background: #047857;
        }

        .totals {
            margin-top: 30px;
            background: #111827;
            color: white;
            padding: 20px;
            border-radius: 10px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 7px 0;
        }

        .grand-total {
            border-top: 1px solid #4b5563;
            padding-top: 14px;
            margin-top: 8px;
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
                AutoCare - Phiếu bảo dưỡng
            </div>

            <a href="{{ route('staff.appointments.index') }}">
                Lịch hẹn
            </a>

        </div>

    </header>


    <main class="container">

        @php

            $statusText = match ($serviceOrder->status) {
                'RECEIVED' => 'Đã tiếp nhận',
                'IN_PROGRESS' => 'Đang thực hiện',
                'COMPLETED' => 'Hoàn thành',
                'CANCELLED' => 'Đã hủy',
                default => $serviceOrder->status,
            };

            $statusClass = match ($serviceOrder->status) {
                'RECEIVED' => 'received',
                'IN_PROGRESS' => 'progress',
                'COMPLETED' => 'completed',
                'CANCELLED' => 'cancelled',
                default => 'received',
            };

            $canAddParts = in_array(
                $serviceOrder->status,
                [
                    'RECEIVED',
                    'IN_PROGRESS',
                ],
                true
            );

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


        <article class="card">

            <p>
                Mã phiếu:
                <strong>
                    {{ $serviceOrder->order_code }}
                </strong>
            </p>

            <h1>
                Phiếu bảo dưỡng
            </h1>

            <span class="status {{ $statusClass }}">
                {{ $statusText }}
            </span>


            <div class="info-grid">

                <div class="info-box">
                    <div class="label">Khách hàng</div>
                    <div class="value">
                        {{ $serviceOrder->customer->full_name }}
                    </div>
                </div>

                <div class="info-box">
                    <div class="label">Xe</div>
                    <div class="value">
                        {{ $serviceOrder->vehicle->brand->name }}
                        {{ $serviceOrder->vehicle->vehicleModel->name }}
                    </div>
                </div>

                <div class="info-box">
                    <div class="label">Biển số</div>
                    <div class="value">
                        {{ $serviceOrder->vehicle->license_plate }}
                    </div>
                </div>

                <div class="info-box">
                    <div class="label">ODO</div>
                    <div class="value">
                        {{ number_format($serviceOrder->received_mileage) }}
                        km
                    </div>
                </div>

                <div class="info-box">
                    <div class="label">Kỹ thuật viên</div>
                    <div class="value">
                        {{
                            $serviceOrder->technician->name
                            ?? 'Chưa phân công'
                        }}
                    </div>
                </div>

            </div>


            <section class="section">

                <h2>
                    Dịch vụ
                </h2>

                @foreach ($serviceOrder->items as $item)

                    <div class="row">

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

                @forelse ($serviceOrder->parts as $part)

                    <div class="row">

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

                @empty

                    <div class="note">
                        Chưa sử dụng phụ tùng.
                    </div>

                @endforelse


                @if ($canAddParts)

                    <form
                        method="POST"
                        action="{{ route(
                            'staff.service-orders.parts.store',
                            $serviceOrder->id
                        ) }}"
                        class="form-box"
                    >

                        @csrf

                        <h3>
                            Xuất phụ tùng
                        </h3>


                        <label for="part_id">
                            Phụ tùng
                        </label>

                        <select
                            id="part_id"
                            name="part_id"
                            required
                        >

                            <option value="">
                                -- Chọn phụ tùng --
                            </option>

                            @foreach ($availableParts as $part)

                                <option
                                    value="{{ $part->id }}"
                                    {{
                                        $part->stock_quantity <= 0
                                            ? 'disabled'
                                            : ''
                                    }}
                                >

                                    {{ $part->name }}
                                    -
                                    tồn {{ $part->stock_quantity }}
                                    {{ $part->unit }}

                                </option>

                            @endforeach

                        </select>


                        <label for="quantity">
                            Số lượng
                        </label>

                        <input
                            type="number"
                            id="quantity"
                            name="quantity"
                            min="1"
                            value="1"
                            required
                        >


                        <label for="note">
                            Ghi chú
                        </label>

                        <textarea
                            id="note"
                            name="note"
                            maxlength="1000"
                        ></textarea>


                        <button
                            type="submit"
                            class="button"
                        >
                            Xuất phụ tùng
                        </button>

                    </form>

                @endif

            </section>


            <div class="totals">

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


            {{-- =========================
                INVOICE
            ========================== --}}
            @if ($serviceOrder->status === 'COMPLETED')

                <section class="section">

                    <h2>
                        Hóa đơn
                    </h2>


                    @if ($serviceOrder->invoice)

                        <div class="note">

                            Hóa đơn đã được lập:

                            <strong>
                                {{ $serviceOrder->invoice->invoice_code }}
                            </strong>

                        </div>


                        <a
                            href="{{ route(
                                'staff.invoices.show',
                                $serviceOrder->invoice->id
                            ) }}"
                            class="button invoice-button"
                        >
                            Xem hóa đơn
                        </a>

                    @else

                        <div class="note">
                            Phiếu đã hoàn thành.
                            Bạn có thể lập hóa đơn cho khách hàng.
                        </div>


                        <a
                            href="{{ route(
                                'staff.invoices.create',
                                $serviceOrder->id
                            ) }}"
                            class="button invoice-button"
                        >
                            Lập hóa đơn
                        </a>

                    @endif

                </section>

            @endif


            @if ($serviceOrder->vehicle_condition)

                <section class="section">
                    <h2>Tình trạng xe</h2>

                    <div class="note">
                        {{ $serviceOrder->vehicle_condition }}
                    </div>
                </section>

            @endif


            @if ($serviceOrder->diagnosis)

                <section class="section">
                    <h2>Chẩn đoán</h2>

                    <div class="note">
                        {{ $serviceOrder->diagnosis }}
                    </div>
                </section>

            @endif


            @if ($serviceOrder->appointment)

                <a
                    href="{{ route(
                        'staff.appointments.show',
                        $serviceOrder->appointment->id
                    ) }}"
                    class="back"
                >
                    ← Quay lại lịch hẹn
                </a>

            @endif

        </article>

    </main>

</body>

</html>