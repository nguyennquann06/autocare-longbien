@extends('layouts.app')


@section(
    'title',
    'Chi tiết hóa đơn - AutoCare Long Biên'
)


@push('styles')

<style>
    .customer-invoice-detail {
        max-width: 1050px;
    }

    .invoice-document {
        position: relative;
        overflow: hidden;
        border:
            1px solid
            rgba(255, 255, 255, 0.88);
        border-radius: 24px;
        background:
            rgba(255, 255, 255, 0.94);
        box-shadow:
            var(--ac-shadow-lg);
        backdrop-filter: blur(16px);
    }

    .invoice-document-header {
        position: relative;
        overflow: hidden;
        padding: 30px;
        color: white;
        background:
            linear-gradient(
                120deg,
                #07111f,
                #0d3476 55%,
                #1677ff
            );
    }

    .invoice-document-header::before {
        content: "";
        position: absolute;
        width: 300px;
        height: 300px;
        right: -100px;
        top: -180px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, 0.40),
                transparent 70%
            );
    }

    .invoice-header-inner {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        flex-wrap: wrap;
    }

    .invoice-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .invoice-brand-logo {
        width: 46px;
        height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        color: white;
        background:
            linear-gradient(
                135deg,
                #38bdf8,
                #4f46e5
            );
        box-shadow:
            0 10px 25px
            rgba(37, 99, 235, 0.32);
        font-weight: 900;
    }

    .invoice-brand-name {
        color: white;
        font-size: 16px;
        font-weight: 900;
    }

    .invoice-brand-subtitle {
        margin-top: 2px;
        color: #bfdbfe;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .invoice-code {
        color: #bfdbfe;
        font-size: 12px;
        font-weight: 750;
    }

    .invoice-title {
        margin: 6px 0 0;
        color: white;
        font-size:
            clamp(
                2rem,
                4vw,
                3rem
            );
        font-weight: 900;
        letter-spacing: -0.05em;
    }

    .invoice-document-body {
        padding: 30px;
    }

    .invoice-info-grid {
        display: grid;
        grid-template-columns:
            repeat(
                5,
                minmax(0, 1fr)
            );
        gap: 12px;
        margin-bottom: 28px;
    }

    .invoice-info-card {
        padding: 15px;
        border:
            1px solid #e7edf4;
        border-radius: 14px;
        background:
            linear-gradient(
                180deg,
                #f8fbff,
                #f5f8fc
            );
    }

    .invoice-info-icon {
        margin-bottom: 8px;
        color: #2563eb;
        font-size: 17px;
    }

    .invoice-info-label {
        color: #64748b;
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .invoice-info-value {
        margin-top: 5px;
        color: #0f172a;
        font-size: 12px;
        font-weight: 850;
    }

    .invoice-table-wrapper {
        overflow-x: auto;
        border:
            1px solid #e7edf4;
        border-radius: 16px;
    }

    .invoice-table {
        width: 100%;
        margin: 0;
        border-collapse: collapse;
        min-width: 760px;
    }

    .invoice-table th {
        padding: 13px 14px;
        color: #475569;
        background:
            linear-gradient(
                180deg,
                #f8fbff,
                #f3f7fb
            );
        border-bottom:
            1px solid #e7edf4;
        font-size: 10px;
        font-weight: 850;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .invoice-table td {
        padding: 14px;
        border-bottom:
            1px solid #edf1f6;
        color: #334155;
        font-size: 12px;
        vertical-align: middle;
    }

    .invoice-table tr:last-child td {
        border-bottom: none;
    }

    .invoice-item-name {
        color: #0f172a;
        font-weight: 850;
    }

    .invoice-item-code {
        margin-top: 3px;
        color: #94a3b8;
        font-size: 10px;
    }

    .invoice-number {
        text-align: right;
        white-space: nowrap;
    }

    .invoice-bottom-grid {
        display: grid;
        grid-template-columns:
            1fr 390px;
        gap: 22px;
        margin-top: 26px;
        align-items: start;
    }

    .payment-card {
        padding: 20px;
        border:
            1px solid #e4ebf3;
        border-radius: 16px;
        background:
            linear-gradient(
                135deg,
                #f8fbff,
                #f7faff
            );
    }

    .payment-card-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 13px;
        color: #0f172a;
        font-size: 14px;
        font-weight: 900;
    }

    .payment-text {
        color: #475569;
        font-size: 12px;
        line-height: 1.8;
    }

    .invoice-note {
        margin-top: 15px;
        padding: 14px;
        border-radius: 13px;
        color: #475569;
        background: #f8fafc;
        font-size: 12px;
        line-height: 1.7;
    }

    .invoice-totals {
        overflow: hidden;
        padding: 22px;
        border-radius: 18px;
        color: white;
        background:
            linear-gradient(
                145deg,
                #07111f,
                #0d2f69
            );
        box-shadow:
            0 20px 45px
            rgba(13, 47, 105, 0.22);
    }

    .invoice-total-row {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        padding: 8px 0;
        color: #bfdbfe;
        font-size: 12px;
    }

    .invoice-total-row strong {
        color: white;
    }

    .invoice-grand-total {
        margin-top: 9px;
        padding-top: 15px;
        border-top:
            1px solid
            rgba(255, 255, 255, 0.14);
        color: white;
        font-size: 17px;
        font-weight: 900;
    }

    .invoice-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 24px;
    }

    .invoice-back,
    .invoice-print {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 43px;
        padding: 10px 15px;
        border-radius: 11px;
        font-size: 12px;
        font-weight: 850;
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .invoice-back {
        color: #334155;
        text-decoration: none;
        border:
            1px solid #d9e2ed;
        background: white;
    }

    .invoice-print {
        color: white;
        border: none;
        background:
            linear-gradient(
                135deg,
                #1683ff,
                #4f46e5
            );
        box-shadow:
            0 8px 22px
            rgba(37, 99, 235, 0.20);
    }

    .invoice-back:hover,
    .invoice-print:hover {
        transform: translateY(-2px);
    }

    @media (max-width: 1199px) {
        .invoice-info-grid {
            grid-template-columns:
                repeat(
                    3,
                    minmax(0, 1fr)
                );
        }
    }

    @media (max-width: 991px) {
        .invoice-bottom-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .invoice-info-grid {
            grid-template-columns:
                repeat(
                    2,
                    minmax(0, 1fr)
                );
        }

        .invoice-document-body {
            padding: 20px;
        }
    }

    @media (max-width: 575px) {
        .invoice-info-grid {
            grid-template-columns: 1fr;
        }

        .invoice-document-header {
            padding: 23px;
        }
    }

    @media print {
        .app-navbar,
        .autocare-footer,
        #scrollTopButton,
        .autocare-top-accent,
        .autocare-background,
        .invoice-actions {
            display: none !important;
        }

        .autocare-main {
            padding: 0 !important;
        }

        body {
            background: white !important;
        }

        .customer-invoice-detail {
            max-width: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .invoice-document {
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        .invoice-document-header {
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }

        .invoice-totals {
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }
    }
</style>

@endpush


@section('content')

<div class="container customer-invoice-detail">

    @php
        $statusText = match (
            $invoice->payment_status
        ) {
            'UNPAID' =>
                'Chưa thanh toán',

            'PAID' =>
                'Đã thanh toán',

            'CANCELLED' =>
                'Đã hủy',

            default =>
                $invoice->payment_status,
        };


        $statusClass = match (
            $invoice->payment_status
        ) {
            'UNPAID' =>
                'status-unpaid',

            'PAID' =>
                'status-paid',

            'CANCELLED' =>
                'status-cancelled',

            default =>
                'status-unpaid',
        };
    @endphp


    <article
        class="invoice-document"
        data-reveal="zoom"
    >

        <header class="invoice-document-header">

            <div class="invoice-header-inner">

                <div>

                    <div class="invoice-brand">

                        <div class="invoice-brand-logo">
                            AC
                        </div>

                        <div>

                            <div class="invoice-brand-name">
                                AutoCare Long Biên
                            </div>

                            <div class="invoice-brand-subtitle">
                                Car Service System
                            </div>

                        </div>

                    </div>


                    <div class="invoice-code">

                        Mã hóa đơn:

                        <strong>
                            {{ $invoice->invoice_code }}
                        </strong>

                    </div>


                    <h1 class="invoice-title">
                        Hóa đơn dịch vụ
                    </h1>

                </div>


                <span
                    class="
                        status-badge
                        {{ $statusClass }}
                    "
                >
                    {{ $statusText }}
                </span>

            </div>

        </header>


        <div class="invoice-document-body">

            <section class="invoice-info-grid">

                <div class="invoice-info-card">

                    <div class="invoice-info-icon">
                        <i class="bi bi-car-front-fill"></i>
                    </div>

                    <div class="invoice-info-label">
                        Xe
                    </div>

                    <div class="invoice-info-value">

                        {{ $invoice->serviceOrder->vehicle->brand->name }}

                        {{ $invoice->serviceOrder->vehicle->vehicleModel->name }}

                    </div>

                </div>


                <div class="invoice-info-card">

                    <div class="invoice-info-icon">
                        <i class="bi bi-credit-card-2-front"></i>
                    </div>

                    <div class="invoice-info-label">
                        Biển số
                    </div>

                    <div class="invoice-info-value">
                        {{ $invoice->serviceOrder->vehicle->license_plate }}
                    </div>

                </div>


                <div class="invoice-info-card">

                    <div class="invoice-info-icon">
                        <i class="bi bi-tools"></i>
                    </div>

                    <div class="invoice-info-label">
                        Phiếu bảo dưỡng
                    </div>

                    <div class="invoice-info-value">
                        {{ $invoice->serviceOrder->order_code }}
                    </div>

                </div>


                <div class="invoice-info-card">

                    <div class="invoice-info-icon">
                        <i class="bi bi-calendar3"></i>
                    </div>

                    <div class="invoice-info-label">
                        Ngày lập
                    </div>

                    <div class="invoice-info-value">

                        {{
                            $invoice
                                ->issued_at
                                ->format('d/m/Y H:i')
                        }}

                    </div>

                </div>


                <div class="invoice-info-card">

                    <div class="invoice-info-icon">
                        <i class="bi bi-person-gear"></i>
                    </div>

                    <div class="invoice-info-label">
                        Kỹ thuật viên
                    </div>

                    <div class="invoice-info-value">

                        {{
                            $invoice
                                ->serviceOrder
                                ->technician
                                ->name
                            ?? 'Không xác định'
                        }}

                    </div>

                </div>

            </section>


            <section class="invoice-table-wrapper">

                <table class="invoice-table">

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

                            <th class="invoice-number">
                                Đơn giá
                            </th>

                            <th class="invoice-number">
                                Thành tiền
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach ($invoice->items as $item)

                            <tr>

                                <td>

                                    <div class="invoice-item-name">
                                        {{ $item->item_name }}
                                    </div>

                                    @if ($item->item_code)

                                        <div class="invoice-item-code">
                                            {{ $item->item_code }}
                                        </div>

                                    @endif

                                </td>


                                <td>

                                    <span
                                        class="
                                            badge
                                            rounded-pill
                                            {{
                                                $item->item_type === 'SERVICE'
                                                    ? 'text-bg-primary'
                                                    : 'text-bg-secondary'
                                            }}
                                        "
                                    >

                                        {{
                                            $item->item_type === 'SERVICE'
                                                ? 'Dịch vụ'
                                                : 'Phụ tùng'
                                        }}

                                    </span>

                                </td>


                                <td>

                                    {{ $item->quantity }}

                                    {{ $item->unit }}

                                </td>


                                <td class="invoice-number">

                                    {{
                                        number_format(
                                            $item->unit_price,
                                            0,
                                            ',',
                                            '.'
                                        )
                                    }} đ

                                </td>


                                <td
                                    class="
                                        invoice-number
                                        fw-bold
                                    "
                                >

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

            </section>


            <div class="invoice-bottom-grid">

                <div>

                    <section class="payment-card">

                        <div class="payment-card-title">

                            <i class="bi bi-credit-card"></i>

                            Thông tin thanh toán

                        </div>


                        <div class="payment-text">

                            <strong>
                                Trạng thái:
                            </strong>

                            {{ $statusText }}


                            @if (
                                $invoice->payment_status
                                === 'PAID'
                            )

                                <br>

                                <strong>
                                    Phương thức:
                                </strong>

                                {{
                                    match ($invoice->payment_method) {
                                        'CASH' =>
                                            'Tiền mặt',

                                        'BANK_TRANSFER' =>
                                            'Chuyển khoản ngân hàng',

                                        'CARD' =>
                                            'Thẻ',

                                        default =>
                                            'Không xác định',
                                    }
                                }}


                                @if ($invoice->paid_at)

                                    <br>

                                    <strong>
                                        Thanh toán lúc:
                                    </strong>

                                    {{
                                        $invoice
                                            ->paid_at
                                            ->format('d/m/Y H:i')
                                    }}

                                @endif


                            @elseif (
                                $invoice->payment_status
                                === 'UNPAID'
                            )

                                <br><br>

                                Vui lòng thanh toán tại
                                AutoCare Long Biên theo
                                hướng dẫn của nhân viên.

                            @endif

                        </div>

                    </section>


                    @if ($invoice->note)

                        <div class="invoice-note">

                            <strong>
                                <i class="bi bi-chat-left-text me-1"></i>
                                Ghi chú:
                            </strong>

                            {{ $invoice->note }}

                        </div>

                    @endif

                </div>


                <aside class="invoice-totals">

                    <div class="invoice-total-row">

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


                    <div class="invoice-total-row">

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


                    <div class="invoice-total-row">

                        <span>
                            Tạm tính
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


                    <div class="invoice-total-row">

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


                    <div class="invoice-total-row">

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


                    <div
                        class="
                            invoice-total-row
                            invoice-grand-total
                        "
                    >

                        <span>
                            Tổng thanh toán
                        </span>

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

                    </div>

                </aside>

            </div>


            <div class="invoice-actions">

                <a
                    href="{{ route('customer.invoices.index') }}"
                    class="invoice-back"
                >

                    <i class="bi bi-arrow-left"></i>

                    Hóa đơn của tôi

                </a>


                <button
                    type="button"
                    class="invoice-print"
                    onclick="window.print()"
                >

                    <i class="bi bi-printer"></i>

                    In hóa đơn

                </button>

            </div>

        </div>

    </article>

</div>

@endsection