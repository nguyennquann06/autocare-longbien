@extends('layouts.app')


@section(
    'title',
    'Lập hóa đơn - AutoCare Long Biên'
)


@push('styles')

<style>
    .invoice-create-page {
        max-width: 1050px;
    }

    .invoice-create-hero {
        position: relative;
        overflow: hidden;
        padding: 31px;
        margin-bottom: 23px;
        border-radius: 26px;
        color: white;
        background:
            linear-gradient(
                120deg,
                #06101e,
                #0c3474 52%,
                #1677ff
            );
        box-shadow:
            0 25px 70px
            rgba(22, 119, 255, .22);
    }

    .invoice-create-hero h1 {
        margin: 0;
        color: white;
        font-size:
            clamp(2rem, 4vw, 3.2rem);
        font-weight: 900;
        letter-spacing: -.055em;
    }

    .invoice-order {
        margin-top: 8px;
        color: #bfdbfe;
        font-size: 12px;
    }

    .invoice-create-card {
        border:
            1px solid
            rgba(255, 255, 255, .88);
        border-radius: 21px;
        background:
            rgba(255, 255, 255, .92);
        box-shadow: var(--ac-shadow);
    }

    .invoice-create-body {
        padding: 27px;
    }

    .info-grid {
        display: grid;
        grid-template-columns:
            repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 27px;
    }

    .info-card {
        padding: 15px;
        border: 1px solid #e7edf4;
        border-radius: 14px;
        background: #f8fbff;
    }

    .info-label {
        color: #64748b;
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .info-value {
        margin-top: 5px;
        color: #0f172a;
        font-size: 12px;
        font-weight: 850;
    }

    .invoice-section {
        margin-top: 27px;
    }

    .invoice-section-title {
        margin-bottom: 13px;
        color: #0f172a;
        font-size: 17px;
        font-weight: 900;
    }

    .invoice-item {
        display: grid;
        grid-template-columns:
            1fr 100px 160px;
        gap: 15px;
        padding: 13px 0;
        border-bottom: 1px solid #edf1f6;
        font-size: 12px;
    }

    .invoice-item strong:last-child {
        color: #1d4ed8;
        text-align: right;
    }

    .totals {
        margin-top: 25px;
        padding: 22px;
        border-radius: 17px;
        color: white;
        background:
            linear-gradient(
                145deg,
                #06101e,
                #0d2f69
            );
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        color: #bfdbfe;
        font-size: 12px;
    }

    .total-row strong,
    .total-row span:last-child {
        color: white;
    }

    .subtotal {
        margin-top: 8px;
        padding-top: 14px;
        border-top:
            1px solid
            rgba(255, 255, 255, .15);
        font-size: 16px;
        font-weight: 900;
    }

    .invoice-form {
        margin-top: 25px;
        padding: 20px;
        border: 1px solid #e5ecf4;
        border-radius: 16px;
        background: #f8fbff;
    }

    .invoice-submit {
        min-height: 47px;
        padding: 0 20px;
        border: none;
        border-radius: 13px;
        color: white;
        background:
            linear-gradient(
                135deg,
                #10b981,
                #047857
            );
        font-weight: 850;
    }

    .invoice-back {
        display: inline-flex;
        gap: 7px;
        margin-top: 20px;
        color: #64748b;
        text-decoration: none;
        font-size: 12px;
        font-weight: 750;
    }

    @media (max-width: 767px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .invoice-item {
            grid-template-columns: 1fr;
        }

        .invoice-item strong:last-child {
            text-align: left;
        }
    }
</style>

@endpush


@section('content')

<div class="container invoice-create-page">

    <section class="invoice-create-hero" data-reveal="zoom">

        <h1>
            Lập hóa đơn
        </h1>

        <div class="invoice-order">
            Phiếu bảo dưỡng:
            <strong>
                {{ $serviceOrder->order_code }}
            </strong>
        </div>

    </section>


    @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <article class="invoice-create-card" data-reveal>

        <div class="invoice-create-body">

            <div class="info-grid">

                <div class="info-card">
                    <div class="info-label">
                        Khách hàng
                    </div>
                    <div class="info-value">
                        {{ $serviceOrder->customer->full_name }}
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-label">
                        Xe
                    </div>
                    <div class="info-value">
                        {{ $serviceOrder->vehicle->brand->name }}
                        {{ $serviceOrder->vehicle->vehicleModel->name }}
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-label">
                        Biển số
                    </div>
                    <div class="info-value">
                        {{ $serviceOrder->vehicle->license_plate }}
                    </div>
                </div>

            </div>


            <section class="invoice-section">

                <div class="invoice-section-title">
                    Dịch vụ
                </div>

                @foreach ($serviceOrder->items as $item)

                    <div class="invoice-item">

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


            <section class="invoice-section">

                <div class="invoice-section-title">
                    Phụ tùng
                </div>

                @if ($serviceOrder->parts->isEmpty())

                    <div class="alert alert-light">
                        Không sử dụng phụ tùng.
                    </div>

                @else

                    @foreach ($serviceOrder->parts as $part)

                        <div class="invoice-item">

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
                    <span>Tiền dịch vụ</span>
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
                    <span>Tiền phụ tùng</span>
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
                    <span>Tổng trước giảm giá</span>
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
                id="invoiceCreateForm"
                method="POST"
                action="{{ route(
                    'staff.invoices.store',
                    $serviceOrder->id
                ) }}"
                class="invoice-form"
            >

                @csrf


                <div class="mb-3">

                    <label
                        for="discount_amount"
                        class="form-label fw-bold"
                    >
                        Giảm giá
                    </label>

                    <input
                        type="number"
                        id="discount_amount"
                        name="discount_amount"
                        class="form-control"
                        min="0"
                        max="{{ $subtotal }}"
                        step="1000"
                        value="{{ old(
                            'discount_amount',
                            0
                        ) }}"
                    >

                </div>


                <div class="mb-3">

                    <label
                        for="note"
                        class="form-label fw-bold"
                    >
                        Ghi chú hóa đơn
                    </label>

                    <textarea
                        id="note"
                        name="note"
                        class="form-control"
                        rows="4"
                        maxlength="2000"
                        placeholder="Ví dụ: Giảm giá khách hàng thân thiết..."
                    >{{ old('note') }}</textarea>

                </div>


                <button
                    type="button"
                    class="invoice-submit"
                    data-bs-toggle="modal"
                    data-bs-target="#createInvoiceModal"
                >
                    <i class="bi bi-receipt me-2"></i>
                    Lập hóa đơn
                </button>

            </form>


            <a
                href="{{ route(
                    'staff.service-orders.show',
                    $serviceOrder->id
                ) }}"
                class="invoice-back"
            >
                <i class="bi bi-arrow-left"></i>
                Quay lại phiếu bảo dưỡng
            </a>

        </div>

    </article>

</div>


<div
    class="modal fade"
    id="createInvoiceModal"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 rounded-4 shadow-lg">

            <div class="modal-body p-4 p-md-5 text-center">

                <div class="fs-1 text-success mb-3">
                    <i class="bi bi-receipt"></i>
                </div>

                <h3>
                    Xác nhận lập hóa đơn?
                </h3>

                <p class="text-secondary">
                    Sau khi tạo, dữ liệu hóa đơn
                    sẽ được lưu snapshot
                    từ phiếu bảo dưỡng hiện tại.
                </p>

                <div class="d-flex justify-content-center gap-2">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Quay lại
                    </button>

                    <button
                        type="button"
                        class="btn btn-success"
                        onclick="
                            document
                                .getElementById(
                                    'invoiceCreateForm'
                                )
                                .submit();
                        "
                    >
                        Xác nhận lập
                    </button>

                </div>

            </div>

        </div>

    </div>
</div>

@endsection