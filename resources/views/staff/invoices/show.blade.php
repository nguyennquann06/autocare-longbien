<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Hóa đơn - AutoCare Long Biên
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

        .invoice {
            background: white;
            padding: 35px;
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

        .error,
        .errors {
            background: #fef2f2;
            color: #991b1b;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .invoice-top {
            display: flex;
            justify-content: space-between;
            gap: 25px;
            flex-wrap: wrap;
        }

        .invoice-code {
            color: #6b7280;
        }

        .status {
            display: inline-block;
            padding: 8px 14px;
            background: #fff7ed;
            color: #c2410c;
            border-radius: 20px;
            font-weight: bold;
        }

        .paid {
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
            margin: 30px 0;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 13px 10px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        th {
            background: #f9fafb;
            color: #4b5563;
            font-size: 13px;
        }

        .number {
            text-align: right;
        }

        .totals {
            max-width: 430px;
            margin: 30px 0 0 auto;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 8px 0;
        }

        .grand-total {
            margin-top: 7px;
            padding-top: 15px;
            border-top: 2px solid #111827;
            font-size: 21px;
            font-weight: bold;
        }

        .note {
            margin-top: 25px;
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
        }

        .payment-box {
            margin-top: 35px;
            padding: 22px;
            background: #f9fafb;
            border-radius: 10px;
        }

        .payment-box h2 {
            margin-top: 0;
        }

        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 15px;
            margin-top: 8px;
        }

        .pay-button {
            margin-top: 18px;
            background: #047857;
            color: white;
            border: none;
            padding: 12px 18px;
            border-radius: 7px;
            cursor: pointer;
            font-size: 15px;
        }

        .paid-box {
            margin-top: 35px;
            padding: 20px;
            background: #ecfdf5;
            color: #065f46;
            border-radius: 10px;
            line-height: 1.7;
        }

        .back {
            display: inline-block;
            margin-top: 25px;
            color: #111827;
            text-decoration: none;
        }

        @media print {
            .header,
            .payment-box,
            .back {
                display: none;
            }

            body {
                background: white;
            }

            .container {
                max-width: none;
                margin: 0;
                padding: 0;
            }

            .invoice {
                box-shadow: none;
            }
        }
    </style>
</head>

<body>

    <header class="header">

        <div class="header-inner">

            <div class="logo">
                AutoCare - Hóa đơn
            </div>

            <a href="{{ route('staff.appointments.index') }}">
                Quản lý
            </a>

        </div>

    </header>


    <main class="container">

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


        <article class="invoice">

            <div class="invoice-top">

                <div>

                    <div class="invoice-code">

                        Mã hóa đơn:

                        <strong>
                            {{ $invoice->invoice_code }}
                        </strong>

                    </div>

                    <h1>
                        HÓA ĐƠN DỊCH VỤ
                    </h1>

                </div>


                <span
                    class="status
                    {{
                        $invoice->payment_status === 'PAID'
                            ? 'paid'
                            : (
                                $invoice->payment_status === 'CANCELLED'
                                    ? 'cancelled'
                                    : ''
                            )
                    }}"
                >

                    {{
                        match ($invoice->payment_status) {
                            'UNPAID' =>
                                'Chưa thanh toán',

                            'PAID' =>
                                'Đã thanh toán',

                            'CANCELLED' =>
                                'Đã hủy',

                            default =>
                                $invoice->payment_status,
                        }
                    }}

                </span>

            </div>


            <div class="info-grid">

                <div class="info-box">

                    <div class="label">
                        Khách hàng
                    </div>

                    <div class="value">
                        {{ $invoice->customer->full_name }}
                    </div>

                </div>


                <div class="info-box">

                    <div class="label">
                        Xe
                    </div>

                    <div class="value">

                        {{ $invoice->serviceOrder->vehicle->brand->name }}

                        {{ $invoice->serviceOrder->vehicle->vehicleModel->name }}

                    </div>

                </div>


                <div class="info-box">

                    <div class="label">
                        Biển số
                    </div>

                    <div class="value">
                        {{ $invoice->serviceOrder->vehicle->license_plate }}
                    </div>

                </div>


                <div class="info-box">

                    <div class="label">
                        Phiếu bảo dưỡng
                    </div>

                    <div class="value">
                        {{ $invoice->serviceOrder->order_code }}
                    </div>

                </div>


                <div class="info-box">

                    <div class="label">
                        Ngày lập
                    </div>

                    <div class="value">

                        {{
                            $invoice->issued_at
                                ->format('d/m/Y H:i')
                        }}

                    </div>

                </div>


                <div class="info-box">

                    <div class="label">
                        Nhân viên lập
                    </div>

                    <div class="value">

                        {{
                            $invoice->creator->name
                            ?? 'Không xác định'
                        }}

                    </div>

                </div>

            </div>


            <table>

                <thead>

                    <tr>

                        <th>
                            Nội dung
                        </th>

                        <th>
                            Loại
                        </th>

                        <th>
                            SL
                        </th>

                        <th class="number">
                            Đơn giá
                        </th>

                        <th class="number">
                            Thành tiền
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach ($invoice->items as $item)

                        <tr>

                            <td>

                                <strong>
                                    {{ $item->item_name }}
                                </strong>

                                @if ($item->item_code)

                                    <br>

                                    <small>
                                        {{ $item->item_code }}
                                    </small>

                                @endif

                            </td>


                            <td>

                                {{
                                    $item->item_type === 'SERVICE'
                                        ? 'Dịch vụ'
                                        : 'Phụ tùng'
                                }}

                            </td>


                            <td>

                                {{ $item->quantity }}

                                {{ $item->unit }}

                            </td>


                            <td class="number">

                                {{
                                    number_format(
                                        $item->unit_price,
                                        0,
                                        ',',
                                        '.'
                                    )
                                }} đ

                            </td>


                            <td class="number">

                                {{
                                    number_format(
                                        $item->line_total,
                                        0,
                                        ',',
                                        '.'
                                    )
                                }} đ

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>


            <div class="totals">

                <div class="total-row">

                    <span>
                        Dịch vụ
                    </span>

                    <strong>

                        {{
                            number_format(
                                $invoice->service_total,
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
                                $invoice->parts_total,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </strong>

                </div>


                <div class="total-row">

                    <span>
                        Tổng trước giảm giá
                    </span>

                    <strong>

                        {{
                            number_format(
                                $invoice->subtotal,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </strong>

                </div>


                <div class="total-row">

                    <span>
                        Giảm giá
                    </span>

                    <strong>

                        -
                        {{
                            number_format(
                                $invoice->discount_amount,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </strong>

                </div>


                <div class="total-row">

                    <span>
                        Thuế
                    </span>

                    <strong>

                        {{
                            number_format(
                                $invoice->tax_amount,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </strong>

                </div>


                <div class="total-row grand-total">

                    <span>
                        Tổng thanh toán
                    </span>

                    <span>

                        {{
                            number_format(
                                $invoice->total_amount,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </span>

                </div>

            </div>


            @if ($invoice->note)

                <div class="note">

                    <strong>
                        Ghi chú:
                    </strong>

                    {{ $invoice->note }}

                </div>

            @endif


            {{-- =========================
                PAYMENT
            ========================== --}}
            @if ($invoice->payment_status === 'UNPAID')

                <section class="payment-box">

                    <h2>
                        Xác nhận thanh toán
                    </h2>

                    <p>
                        Số tiền khách cần thanh toán:
                        <strong>

                            {{
                                number_format(
                                    $invoice->total_amount,
                                    0,
                                    ',',
                                    '.'
                                )
                            }} đ

                        </strong>
                    </p>


                    <form
                        method="POST"
                        action="{{ route(
                            'staff.invoices.pay',
                            $invoice->id
                        ) }}"
                        onsubmit="
                            return confirm(
                                'Xác nhận khách hàng đã thanh toán hóa đơn này?'
                            );
                        "
                    >

                        @csrf
                        @method('PATCH')


                        <label for="payment_method">

                            <strong>
                                Phương thức thanh toán
                            </strong>

                        </label>


                        <select
                            id="payment_method"
                            name="payment_method"
                            required
                        >

                            <option value="">
                                -- Chọn phương thức --
                            </option>


                            <option
                                value="CASH"
                                {{
                                    old('payment_method')
                                    === 'CASH'
                                        ? 'selected'
                                        : ''
                                }}
                            >
                                Tiền mặt
                            </option>


                            <option
                                value="BANK_TRANSFER"
                                {{
                                    old('payment_method')
                                    === 'BANK_TRANSFER'
                                        ? 'selected'
                                        : ''
                                }}
                            >
                                Chuyển khoản ngân hàng
                            </option>


                            <option
                                value="CARD"
                                {{
                                    old('payment_method')
                                    === 'CARD'
                                        ? 'selected'
                                        : ''
                                }}
                            >
                                Thẻ
                            </option>

                        </select>


                        <button
                            type="submit"
                            class="pay-button"
                        >
                            Xác nhận đã thanh toán
                        </button>

                    </form>

                </section>


            @elseif ($invoice->payment_status === 'PAID')

                <section class="paid-box">

                    <strong>
                        ✓ Hóa đơn đã được thanh toán
                    </strong>

                    <br>

                    Phương thức:

                    <strong>

                        {{
                            match ($invoice->payment_method) {
                                'CASH' =>
                                    'Tiền mặt',

                                'BANK_TRANSFER' =>
                                    'Chuyển khoản ngân hàng',

                                'CARD' =>
                                    'Thẻ',

                                default =>
                                    $invoice->payment_method
                                    ?? 'Không xác định',
                            }
                        }}

                    </strong>


                    @if ($invoice->paid_at)

                        <br>

                        Thanh toán lúc:

                        <strong>

                            {{
                                $invoice
                                    ->paid_at
                                    ->format(
                                        'd/m/Y H:i'
                                    )
                            }}

                        </strong>

                    @endif

                </section>

            @endif


            <a
                href="{{ route(
                    'staff.service-orders.show',
                    $invoice->serviceOrder->id
                ) }}"
                class="back"
            >
                ← Quay lại phiếu bảo dưỡng
            </a>

        </article>

    </main>

</body>

</html>