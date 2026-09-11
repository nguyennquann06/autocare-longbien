@extends('layouts.app')


@section(
    'title',
    'Hóa đơn của tôi - AutoCare Long Biên'
)


@push('styles')

<style>
    .customer-invoices-page {
        max-width: 1180px;
    }

    .invoice-list-hero {
        position: relative;
        overflow: hidden;
        padding: 31px;
        margin-bottom: 25px;
        border-radius: 26px;
        color: white;
        background:
            linear-gradient(
                120deg,
                #07111f,
                #0c3474 55%,
                #1677ff
            );
        box-shadow:
            0 25px 70px
            rgba(22, 119, 255, 0.22);
    }

    .invoice-list-hero::before {
        content: "";
        position: absolute;
        width: 330px;
        height: 330px;
        top: -190px;
        right: -100px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, 0.42),
                transparent 70%
            );
    }

    .invoice-list-hero-content {
        position: relative;
        z-index: 2;
    }

    .invoice-list-hero h1 {
        margin: 0;
        color: white;
        font-size:
            clamp(
                2rem,
                4vw,
                3.3rem
            );
        font-weight: 900;
        letter-spacing: -0.055em;
    }

    .invoice-list-hero p {
        max-width: 650px;
        margin: 11px 0 0;
        color: #cbd5e1;
        line-height: 1.75;
    }

    .invoice-summary-grid {
        display: grid;
        grid-template-columns:
            repeat(
                3,
                minmax(0, 1fr)
            );
        gap: 15px;
        margin-bottom: 24px;
    }

    .invoice-summary-card {
        padding: 19px;
        border:
            1px solid
            rgba(255, 255, 255, 0.88);
        border-radius: 17px;
        background:
            rgba(255, 255, 255, 0.91);
        box-shadow:
            var(--ac-shadow);
        backdrop-filter: blur(15px);
    }

    .invoice-summary-icon {
        width: 43px;
        height: 43px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        border-radius: 13px;
        color: #2563eb;
        background:
            linear-gradient(
                135deg,
                #dbeafe,
                #ecfeff
            );
        font-size: 18px;
    }

    .invoice-summary-label {
        color: #64748b;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .invoice-summary-value {
        margin-top: 5px;
        color: #0f172a;
        font-size: 24px;
        font-weight: 900;
    }

    .customer-invoice-card {
        position: relative;
        overflow: hidden;
        padding: 24px;
        margin-bottom: 18px;
        border:
            1px solid
            rgba(255, 255, 255, 0.88);
        border-radius: 20px;
        background:
            rgba(255, 255, 255, 0.92);
        box-shadow:
            var(--ac-shadow);
        backdrop-filter: blur(16px);
        transition:
            transform 0.23s ease,
            box-shadow 0.23s ease;
    }

    .customer-invoice-card:hover {
        transform: translateY(-5px);
        box-shadow:
            var(--ac-shadow-lg);
    }

    .customer-invoice-card::after {
        content: "";
        position: absolute;
        width: 150px;
        height: 150px;
        top: -100px;
        right: -100px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(59, 130, 246, 0.13),
                transparent 70%
            );
    }

    .invoice-card-top {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
        flex-wrap: wrap;
    }

    .invoice-code {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #64748b;
        font-size: 12px;
        font-weight: 800;
    }

    .invoice-vehicle {
        margin-top: 8px;
        color: #0f172a;
        font-size: 20px;
        font-weight: 900;
        letter-spacing: -0.03em;
    }

    .invoice-license {
        margin-top: 5px;
        color: #64748b;
        font-size: 12px;
    }

    .invoice-info-grid {
        position: relative;
        z-index: 2;
        display: grid;
        grid-template-columns:
            repeat(
                4,
                minmax(0, 1fr)
            );
        gap: 12px;
        margin-top: 20px;
    }

    .invoice-info {
        padding: 14px;
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
        margin-bottom: 7px;
        color: #2563eb;
        font-size: 17px;
    }

    .invoice-info-label {
        color: #64748b;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .invoice-info-value {
        margin-top: 4px;
        color: #0f172a;
        font-size: 13px;
        font-weight: 850;
    }

    .invoice-total {
        color: #1d4ed8;
        font-size: 16px;
        font-weight: 900;
    }

    .invoice-card-footer {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: flex-end;
        margin-top: 19px;
    }

    .invoice-detail-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 11px;
        color: white;
        text-decoration: none;
        background:
            linear-gradient(
                135deg,
                #1683ff,
                #4f46e5
            );
        font-size: 12px;
        font-weight: 850;
        box-shadow:
            0 8px 22px
            rgba(37, 99, 235, 0.20);
        transition:
            all 0.2s ease;
    }

    .invoice-detail-button:hover {
        color: white;
        transform: translateY(-2px);
    }

    .invoice-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 12px;
        color: #64748b;
        text-decoration: none;
        font-size: 13px;
        font-weight: 750;
    }

    .invoice-back:hover {
        color: #2563eb;
    }

    @media (max-width: 991px) {
        .invoice-info-grid {
            grid-template-columns:
                repeat(
                    2,
                    minmax(0, 1fr)
                );
        }
    }

    @media (max-width: 767px) {
        .invoice-summary-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575px) {
        .invoice-info-grid {
            grid-template-columns: 1fr;
        }

        .customer-invoice-card {
            padding: 19px;
        }
    }
</style>

@endpush


@section('content')

<div class="container customer-invoices-page">

    @php
        $totalInvoices =
            $invoices->count();

        $paidInvoices =
            $invoices
                ->where(
                    'payment_status',
                    'PAID'
                )
                ->count();

        $unpaidInvoices =
            $invoices
                ->where(
                    'payment_status',
                    'UNPAID'
                )
                ->count();
    @endphp


    <section
        class="invoice-list-hero"
        data-reveal="zoom"
    >

        <div class="invoice-list-hero-content">

            <h1>
                Hóa đơn của tôi
            </h1>

            <p>

                Theo dõi chi phí dịch vụ,
                phương thức thanh toán và trạng thái
                của từng lần bảo dưỡng tại AutoCare.

            </p>

        </div>

    </section>


    <section class="invoice-summary-grid">

        <div
            class="invoice-summary-card"
            data-reveal
            data-tilt
        >

            <div class="invoice-summary-icon">
                <i class="bi bi-receipt"></i>
            </div>

            <div class="invoice-summary-label">
                Tổng hóa đơn
            </div>

            <div class="invoice-summary-value">
                {{ $totalInvoices }}
            </div>

        </div>


        <div
            class="invoice-summary-card"
            data-reveal
            data-tilt
        >

            <div
                class="invoice-summary-icon"
                style="
                    color: #059669;
                    background:
                        linear-gradient(
                            135deg,
                            #d1fae5,
                            #ecfdf5
                        );
                "
            >
                <i class="bi bi-check-circle"></i>
            </div>

            <div class="invoice-summary-label">
                Đã thanh toán
            </div>

            <div class="invoice-summary-value">
                {{ $paidInvoices }}
            </div>

        </div>


        <div
            class="invoice-summary-card"
            data-reveal
            data-tilt
        >

            <div
                class="invoice-summary-icon"
                style="
                    color: #d97706;
                    background:
                        linear-gradient(
                            135deg,
                            #ffedd5,
                            #fff7ed
                        );
                "
            >
                <i class="bi bi-hourglass-split"></i>
            </div>

            <div class="invoice-summary-label">
                Chưa thanh toán
            </div>

            <div class="invoice-summary-value">
                {{ $unpaidInvoices }}
            </div>

        </div>

    </section>


    @if ($invoices->isEmpty())

        <div
            class="empty-state"
            data-reveal="zoom"
        >

            <div class="empty-state-icon">
                <i class="bi bi-receipt-cutoff"></i>
            </div>

            <h3>
                Chưa có hóa đơn
            </h3>

            <p>

                Hóa đơn sẽ xuất hiện tại đây
                sau khi gara hoàn thành bảo dưỡng
                và lập hóa đơn.

            </p>

        </div>

    @else

        @foreach ($invoices as $invoice)

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
                class="customer-invoice-card"
                data-reveal
                data-tilt
            >

                <div class="invoice-card-top">

                    <div>

                        <div class="invoice-code">

                            <i class="bi bi-hash"></i>

                            {{ $invoice->invoice_code }}

                        </div>


                        <div class="invoice-vehicle">

                            {{ $invoice->serviceOrder->vehicle->brand->name }}

                            {{ $invoice->serviceOrder->vehicle->vehicleModel->name }}

                        </div>


                        <div class="invoice-license">

                            <i class="bi bi-car-front me-1"></i>

                            {{ $invoice->serviceOrder->vehicle->license_plate }}

                        </div>

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


                <div class="invoice-info-grid">

                    <div class="invoice-info">

                        <div class="invoice-info-icon">
                            <i class="bi bi-calendar3"></i>
                        </div>

                        <div class="invoice-info-label">
                            Ngày lập
                        </div>

                        <div class="invoice-info-value">

                            {{
                                $invoice->issued_at
                                    ->format('d/m/Y H:i')
                            }}

                        </div>

                    </div>


                    <div class="invoice-info">

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


                    <div class="invoice-info">

                        <div class="invoice-info-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>

                        <div class="invoice-info-label">
                            Tổng thanh toán
                        </div>

                        <div
                            class="
                                invoice-info-value
                                invoice-total
                            "
                        >

                            {{
                                number_format(
                                    $invoice->total_amount,
                                    0,
                                    ',',
                                    '.'
                                )
                            }} đ

                        </div>

                    </div>


                    <div class="invoice-info">

                        <div class="invoice-info-icon">
                            <i class="bi bi-credit-card"></i>
                        </div>

                        <div class="invoice-info-label">
                            Phương thức
                        </div>

                        <div class="invoice-info-value">

                            {{
                                match ($invoice->payment_method) {
                                    'CASH' =>
                                        'Tiền mặt',

                                    'BANK_TRANSFER' =>
                                        'Chuyển khoản',

                                    'CARD' =>
                                        'Thẻ',

                                    default =>
                                        'Chưa thanh toán',
                                }
                            }}

                        </div>

                    </div>

                </div>


                <div class="invoice-card-footer">

                    <a
                        href="{{ route(
                            'customer.invoices.show',
                            $invoice->id
                        ) }}"
                        class="invoice-detail-button"
                    >

                        Xem chi tiết

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </article>

        @endforeach

    @endif


    <a
        href="{{ route('customer.dashboard') }}"
        class="invoice-back"
    >

        <i class="bi bi-arrow-left"></i>

        Quay lại Tổng quan

    </a>

</div>

@endsection