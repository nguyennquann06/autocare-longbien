@extends('layouts.app')


@section(
    'title',
    'Hóa đơn - AutoCare Long Biên'
)


@push('styles')

<style>
    .staff-invoice-page {
        max-width: 1080px;
    }

    .invoice-document {
        overflow: hidden;
        border:
            1px solid
            rgba(255, 255, 255, .88);
        border-radius: 24px;
        background:
            rgba(255, 255, 255, .94);
        box-shadow:
            var(--ac-shadow-lg);
    }

    .invoice-header {
        position: relative;
        overflow: hidden;
        padding: 30px;
        color: white;
        background:
            linear-gradient(
                120deg,
                #06101e,
                #0c3474 55%,
                #1677ff
            );
    }

    .invoice-header::before {
        content: "";
        position: absolute;
        width: 320px;
        height: 320px;
        top: -190px;
        right: -100px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, .4),
                transparent 70%
            );
    }

    .invoice-header-inner {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .invoice-code {
        color: #bfdbfe;
        font-size: 11px;
        font-weight: 800;
    }

    .invoice-header h1 {
        margin: 7px 0 0;
        color: white;
        font-size:
            clamp(
                2rem,
                4vw,
                3.1rem
            );
        font-weight: 900;
        letter-spacing: -.055em;
    }

    .invoice-body {
        padding: 29px;
    }

    .invoice-info-grid {
        display: grid;
        grid-template-columns:
            repeat(
                3,
                minmax(0, 1fr)
            );
        gap: 12px;
        margin-bottom: 27px;
    }

    .invoice-info {
        padding: 14px;
        border:
            1px solid
            #e7edf4;
        border-radius: 14px;
        background: #f8fbff;
    }

    .invoice-info-label {
        color: #64748b;
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .invoice-info-value {
        margin-top: 5px;
        color: #0f172a;
        font-size: 12px;
        font-weight: 850;
    }

    .invoice-table-wrap {
        overflow-x: auto;
        border:
            1px solid
            #e7edf4;
        border-radius: 16px;
    }

    .invoice-table {
        width: 100%;
        min-width: 760px;
        margin: 0;
    }

    .invoice-table th {
        padding: 13px;
        color: #475569;
        background: #f8fbff;
        font-size: 10px;
        font-weight: 850;
        text-transform: uppercase;
    }

    .invoice-table td {
        padding: 13px;
        border-bottom:
            1px solid
            #edf1f6;
        color: #334155;
        font-size: 12px;
    }

    .invoice-number {
        text-align: right;
        white-space: nowrap;
    }

    .invoice-bottom {
        display: grid;
        grid-template-columns:
            1fr
            390px;
        gap: 22px;
        margin-top: 25px;
    }

    .invoice-note {
        padding: 16px;
        border:
            1px solid
            #e4ebf3;
        border-radius: 14px;
        color: #475569;
        background: #f8fbff;
        font-size: 12px;
        line-height: 1.7;
    }

    .invoice-totals {
        padding: 22px;
        border-radius: 18px;
        color: white;
        background:
            linear-gradient(
                145deg,
                #06101e,
                #0d2f69
            );
        box-shadow:
            0 20px 45px
            rgba(13, 47, 105, .22);
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        padding: 8px 0;
        color: #bfdbfe;
        font-size: 12px;
    }

    .total-row strong {
        color: white;
    }

    .grand-total {
        margin-top: 8px;
        padding-top: 15px;
        border-top:
            1px solid
            rgba(255, 255, 255, .15);
        color: white;
        font-size: 17px;
        font-weight: 900;
    }


    /* =====================================================
       PAYMENT
       ===================================================== */

    .payment-card {
        margin-top: 24px;
        padding: 20px;
        border:
            1px solid
            #e5ecf4;
        border-radius: 16px;
        background: #f8fbff;
    }

    .payment-title {
        margin-bottom: 6px;
        color: #0f172a;
        font-size: 16px;
        font-weight: 900;
    }

    .payment-label {
        margin-bottom: 8px;
        color: #334155;
        font-size: 12px;
        font-weight: 850;
    }

    .payment-control {
        min-height: 46px;
    }

    .payment-control.is-invalid {
        border-color: #f87171;
        background-color: #fffafa;
        box-shadow:
            0 0 0 3px
            rgba(239, 68, 68, .07);
    }

    .payment-hint {
        margin-top: 6px;
        color: #64748b;
        font-size: 10px;
        line-height: 1.55;
    }

    .payment-submit {
        min-height: 45px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        margin-top: 16px;
        padding: 0 16px;
        border: none;
        border-radius: 11px;
        color: white;
        background:
            linear-gradient(
                135deg,
                #10b981,
                #047857
            );
        box-shadow:
            0 8px 20px
            rgba(5, 150, 105, .18);
        font-size: 12px;
        font-weight: 850;
        transition:
            transform .2s ease,
            box-shadow .2s ease,
            opacity .2s ease;
    }

    .payment-submit:hover:not(:disabled) {
        transform:
            translateY(-2px);
        box-shadow:
            0 12px 25px
            rgba(5, 150, 105, .25);
    }

    .payment-submit:disabled {
        cursor: not-allowed;
        opacity: .55;
    }

    .paid-card {
        margin-top: 24px;
        padding: 18px;
        border:
            1px solid
            #bbf7d0;
        border-radius: 15px;
        color: #065f46;
        background: #ecfdf5;
        line-height: 1.7;
        font-size: 12px;
    }

    .cancelled-card {
        margin-top: 24px;
        padding: 18px;
        border:
            1px solid
            #fecaca;
        border-radius: 15px;
        color: #991b1b;
        background: #fef2f2;
        line-height: 1.7;
        font-size: 12px;
    }

    .invoice-actions {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 24px;
    }

    @media (max-width: 991px) {
        .invoice-bottom {
            grid-template-columns:
                1fr;
        }
    }

    @media (max-width: 767px) {
        .invoice-info-grid {
            grid-template-columns:
                repeat(
                    2,
                    1fr
                );
        }
    }

    @media (max-width: 575px) {
        .invoice-info-grid {
            grid-template-columns:
                1fr;
        }

        .invoice-body {
            padding: 20px;
        }

        .payment-submit {
            width: 100%;
        }
    }

    @media print {
        .app-navbar,
        .autocare-footer,
        .autocare-background,
        .autocare-top-accent,
        #scrollTopButton,
        .payment-card,
        .invoice-actions,
        .autocare-toast-container {
            display: none !important;
        }

        .autocare-main {
            padding: 0 !important;
        }

        body {
            background: white !important;
        }

        .staff-invoice-page {
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .invoice-document {
            border: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        .invoice-header,
        .invoice-totals {
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }
    }
</style>

@endpush


@section('content')

<div class="container staff-invoice-page">

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

        <header class="invoice-header">

            <div class="invoice-header-inner">

                <div>

                    <div class="invoice-code">

                        Mã hóa đơn:

                        <strong>
                            {{ $invoice->invoice_code }}
                        </strong>

                    </div>


                    <h1>
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


        <div class="invoice-body">

            <div class="invoice-info-grid">

                <div class="invoice-info">

                    <div class="invoice-info-label">
                        Khách hàng
                    </div>

                    <div class="invoice-info-value">

                        {{ $invoice->customer->full_name }}

                    </div>

                </div>


                <div class="invoice-info">

                    <div class="invoice-info-label">
                        Xe
                    </div>

                    <div class="invoice-info-value">

                        {{ $invoice->serviceOrder->vehicle->brand->name }}

                        {{ $invoice->serviceOrder->vehicle->vehicleModel->name }}

                    </div>

                </div>


                <div class="invoice-info">

                    <div class="invoice-info-label">
                        Biển số
                    </div>

                    <div class="invoice-info-value">

                        {{ $invoice->serviceOrder->vehicle->license_plate }}

                    </div>

                </div>


                <div class="invoice-info">

                    <div class="invoice-info-label">
                        Phiếu bảo dưỡng
                    </div>

                    <div class="invoice-info-value">

                        {{ $invoice->serviceOrder->order_code }}

                    </div>

                </div>


                <div class="invoice-info">

                    <div class="invoice-info-label">
                        Ngày lập
                    </div>

                    <div class="invoice-info-value">

                        {{
                            $invoice
                                ->issued_at
                                ->format(
                                    'd/m/Y H:i'
                                )
                        }}

                    </div>

                </div>


                <div class="invoice-info">

                    <div class="invoice-info-label">
                        Nhân viên lập
                    </div>

                    <div class="invoice-info-value">

                        {{
                            $invoice
                                ->creator
                                ->name
                            ?? 'Không xác định'
                        }}

                    </div>

                </div>

            </div>


            <div class="invoice-table-wrap">

                <table class="table invoice-table">

                    <thead>

                        <tr>

                            <th>Nội dung</th>
                            <th>Loại</th>
                            <th>SL</th>

                            <th class="invoice-number">
                                Đơn giá
                            </th>

                            <th class="invoice-number">
                                Thành tiền
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach (
                            $invoice->items
                            as $item
                        )

                            <tr>

                                <td>

                                    <strong>
                                        {{ $item->item_name }}
                                    </strong>


                                    @if (
                                        $item->item_code
                                    )

                                        <div class="small text-secondary">

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
                                                $item->item_type
                                                === 'SERVICE'
                                                    ? 'text-bg-primary'
                                                    : 'text-bg-secondary'
                                            }}
                                        "
                                    >

                                        {{
                                            $item->item_type
                                            === 'SERVICE'
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
                                    }}
                                    đ

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
                                    }}
                                    đ

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            <div class="invoice-bottom">

                <div>

                    @if (
                        $invoice->note
                    )

                        <div class="invoice-note">

                            <strong>

                                <i class="bi bi-chat-left-text me-1"></i>

                                Ghi chú:

                            </strong>

                            {{ $invoice->note }}

                        </div>

                    @endif


                    @if (
                        $invoice->payment_status
                        === 'UNPAID'
                    )

                        <section class="payment-card">

                            <div class="payment-title">

                                <i class="bi bi-wallet2 me-1 text-success"></i>

                                Xác nhận thanh toán

                            </div>


                            <p class="text-secondary small">

                                Số tiền khách cần thanh toán:

                                <strong class="text-dark">

                                    {{
                                        number_format(
                                            $invoice->total_amount,
                                            0,
                                            ',',
                                            '.'
                                        )
                                    }}
                                    đ

                                </strong>

                            </p>


                            <form
                                id="paymentForm"
                                method="POST"
                                action="{{ route(
                                    'staff.invoices.pay',
                                    $invoice->id
                                ) }}"
                                novalidate
                            >

                                @csrf
                                @method('PATCH')


                                <label
                                    for="payment_method"
                                    class="payment-label"
                                >

                                    Phương thức thanh toán *

                                </label>


                                <select
                                    id="payment_method"
                                    name="payment_method"
                                    class="
                                        form-select
                                        payment-control
                                        @error('payment_method')
                                            is-invalid
                                        @enderror
                                    "
                                    aria-invalid="{{
                                        $errors->has(
                                            'payment_method'
                                        )
                                            ? 'true'
                                            : 'false'
                                    }}"
                                >

                                    <option value="">

                                        -- Chọn phương thức --

                                    </option>


                                    <option
                                        value="CASH"
                                        {{
                                            old(
                                                'payment_method'
                                            )
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
                                            old(
                                                'payment_method'
                                            )
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
                                            old(
                                                'payment_method'
                                            )
                                            === 'CARD'
                                                ? 'selected'
                                                : ''
                                        }}
                                    >

                                        Thẻ

                                    </option>

                                </select>


                                <div class="payment-hint">

                                    Chỉ xác nhận sau khi
                                    khách hàng thực sự hoàn tất
                                    thanh toán.

                                </div>


                                <button
                                    type="button"
                                    class="payment-submit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#paymentModal"
                                >

                                    <i class="bi bi-check-circle"></i>

                                    Xác nhận đã thanh toán

                                </button>

                            </form>

                        </section>


                    @elseif (
                        $invoice->payment_status
                        === 'PAID'
                    )

                        <section class="paid-card">

                            <strong>

                                <i class="bi bi-patch-check-fill me-1"></i>

                                Hóa đơn đã được thanh toán

                            </strong>

                            <br>

                            Phương thức:

                            <strong>

                                {{
                                    match (
                                        $invoice
                                            ->payment_method
                                    ) {
                                        'CASH' =>
                                            'Tiền mặt',

                                        'BANK_TRANSFER' =>
                                            'Chuyển khoản ngân hàng',

                                        'CARD' =>
                                            'Thẻ',

                                        default =>
                                            $invoice
                                                ->payment_method
                                            ?? 'Không xác định',
                                    }
                                }}

                            </strong>


                            @if (
                                $invoice->paid_at
                            )

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


                    @elseif (
                        $invoice->payment_status
                        === 'CANCELLED'
                    )

                        <section class="cancelled-card">

                            <strong>

                                <i class="bi bi-x-circle-fill me-1"></i>

                                Hóa đơn đã bị hủy

                            </strong>

                            <div class="mt-1">

                                Hóa đơn này không thể
                                thực hiện thanh toán.

                            </div>

                        </section>

                    @endif

                </div>


                <aside class="invoice-totals">

                    <div class="total-row">

                        <span>
                            Dịch vụ
                        </span>

                        <strong>

                            {{
                                number_format(
                                    $invoice
                                        ->service_total,
                                    0,
                                    ',',
                                    '.'
                                )
                            }}
                            đ

                        </strong>

                    </div>


                    <div class="total-row">

                        <span>
                            Phụ tùng
                        </span>

                        <strong>

                            {{
                                number_format(
                                    $invoice
                                        ->parts_total,
                                    0,
                                    ',',
                                    '.'
                                )
                            }}
                            đ

                        </strong>

                    </div>


                    <div class="total-row">

                        <span>
                            Tổng trước giảm giá
                        </span>

                        <strong>

                            {{
                                number_format(
                                    $invoice
                                        ->subtotal,
                                    0,
                                    ',',
                                    '.'
                                )
                            }}
                            đ

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
                                    $invoice
                                        ->discount_amount,
                                    0,
                                    ',',
                                    '.'
                                )
                            }}
                            đ

                        </strong>

                    </div>


                    <div class="total-row">

                        <span>
                            Thuế
                        </span>

                        <strong>

                            {{
                                number_format(
                                    $invoice
                                        ->tax_amount,
                                    0,
                                    ',',
                                    '.'
                                )
                            }}
                            đ

                        </strong>

                    </div>


                    <div class="total-row grand-total">

                        <span>
                            Tổng thanh toán
                        </span>

                        <strong>

                            {{
                                number_format(
                                    $invoice
                                        ->total_amount,
                                    0,
                                    ',',
                                    '.'
                                )
                            }}
                            đ

                        </strong>

                    </div>

                </aside>

            </div>


            <div class="invoice-actions">

                <a
                    href="{{ route(
                        'staff.service-orders.show',
                        $invoice->serviceOrder->id
                    ) }}"
                    class="btn btn-outline-secondary"
                >

                    <i class="bi bi-arrow-left me-2"></i>

                    Phiếu bảo dưỡng

                </a>


                <button
                    type="button"
                    class="btn btn-primary"
                    onclick="window.print()"
                >

                    <i class="bi bi-printer me-2"></i>

                    In hóa đơn

                </button>

            </div>

        </div>

    </article>

</div>


@if (
    $invoice->payment_status
    === 'UNPAID'
)

    <div
        class="modal fade"
        id="paymentModal"
        tabindex="-1"
        aria-hidden="true"
    >

        <div class="modal-dialog modal-dialog-centered">

            <div
                class="
                    modal-content
                    border-0
                    rounded-4
                    shadow-lg
                "
            >

                <div class="modal-body p-4 p-md-5 text-center">

                    <div class="fs-1 text-success mb-3">

                        <i class="bi bi-cash-coin"></i>

                    </div>


                    <h3>
                        Xác nhận thanh toán?
                    </h3>


                    <p class="text-secondary">

                        Xác nhận khách hàng
                        đã thanh toán

                        <strong>

                            {{
                                number_format(
                                    $invoice
                                        ->total_amount,
                                    0,
                                    ',',
                                    '.'
                                )
                            }}
                            đ

                        </strong>

                        cho hóa đơn này.

                    </p>


                    <div
                        id="paymentMethodPreview"
                        class="
                            small
                            fw-bold
                            text-success
                            mb-3
                        "
                    ></div>


                    <div
                        class="
                            d-flex
                            justify-content-center
                            gap-2
                        "
                    >

                        <button
                            type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal"
                        >

                            Quay lại

                        </button>


                        <button
                            type="button"
                            id="confirmPaymentButton"
                            class="btn btn-success"
                            onclick="submitPaymentForm(this)"
                        >

                            Xác nhận

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endif

@endsection


@push('scripts')

@if (
    $invoice->payment_status
    === 'UNPAID'
)

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function () {
            const paymentMethod =
                document.getElementById(
                    'payment_method'
                );

            const paymentPreview =
                document.getElementById(
                    'paymentMethodPreview'
                );

            const paymentModal =
                document.getElementById(
                    'paymentModal'
                );


            function updatePaymentPreview() {
                if (
                    !paymentMethod
                    ||
                    !paymentPreview
                ) {
                    return;
                }


                const labels = {
                    CASH:
                        'Tiền mặt',

                    BANK_TRANSFER:
                        'Chuyển khoản ngân hàng',

                    CARD:
                        'Thẻ',
                };


                const value =
                    paymentMethod.value;


                paymentPreview.textContent =
                    labels[value]
                        ? 'Phương thức: '
                            + labels[value]
                        : 'Chưa chọn phương thức thanh toán.';
            }


            if (paymentMethod) {
                paymentMethod.addEventListener(
                    'change',
                    updatePaymentPreview
                );


                updatePaymentPreview();
            }


            if (paymentModal) {
                paymentModal.addEventListener(
                    'show.bs.modal',
                    updatePaymentPreview
                );
            }
        }
    );


    function submitPaymentForm(
        button
    ) {
        const form =
            document.getElementById(
                'paymentForm'
            );


        if (!form) {
            return;
        }


        button.disabled =
            true;


        button.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>'
            + 'Đang xác nhận...';


        form.submit();
    }
</script>

@endif

@endpush