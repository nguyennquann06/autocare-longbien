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

    .invoice-create-hero::before {
        content: "";
        position: absolute;
        width: 340px;
        height: 340px;
        top: -205px;
        right: -105px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, .40),
                transparent 70%
            );
    }

    .invoice-create-hero-content {
        position: relative;
        z-index: 2;
    }

    .invoice-create-hero h1 {
        margin: 0;
        color: white;
        font-size:
            clamp(
                2rem,
                4vw,
                3.2rem
            );
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
            repeat(
                3,
                1fr
            );
        gap: 12px;
        margin-bottom: 27px;
    }

    .info-card {
        padding: 15px;
        border:
            1px solid
            #e7edf4;
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
            1fr
            100px
            160px;
        gap: 15px;
        padding: 13px 0;
        border-bottom:
            1px solid
            #edf1f6;
        font-size: 12px;
    }

    .invoice-item strong:last-child {
        color: #1d4ed8;
        text-align: right;
    }

    .empty-parts {
        padding: 15px;
        border:
            1px solid
            #e4ebf3;
        border-radius: 13px;
        color: #64748b;
        background: #f8fbff;
        font-size: 12px;
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
        gap: 15px;
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
        border:
            1px solid
            #e5ecf4;
        border-radius: 16px;
        background: #f8fbff;
    }

    .invoice-form-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 18px;
        color: #0f172a;
        font-size: 17px;
        font-weight: 900;
    }

    .invoice-label {
        margin-bottom: 8px;
        color: #334155;
        font-size: 12px;
        font-weight: 850;
    }

    .invoice-control {
        min-height: 48px;
    }

    textarea.invoice-control {
        min-height: auto;
    }

    .invoice-control.is-invalid {
        border-color: #f87171;
        background-color: #fffafa;
        box-shadow:
            0 0 0 3px
            rgba(239, 68, 68, .07);
    }

    .invoice-hint {
        margin-top: 6px;
        color: #64748b;
        font-size: 10px;
        line-height: 1.55;
    }

    .invoice-preview {
        margin-top: 20px;
        padding: 17px;
        border:
            1px solid
            #bbf7d0;
        border-radius: 14px;
        color: #065f46;
        background:
            linear-gradient(
                135deg,
                #ecfdf5,
                #f0fdf4
            );
        font-size: 12px;
    }

    .invoice-preview-row {
        display: flex;
        justify-content: space-between;
        gap: 15px;
    }

    .invoice-preview-total {
        margin-top: 9px;
        padding-top: 10px;
        border-top:
            1px solid
            #bbf7d0;
        font-size: 15px;
        font-weight: 900;
    }

    .invoice-submit {
        min-height: 47px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 20px;
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
        box-shadow:
            0 9px 24px
            rgba(5, 150, 105, .20);
        transition:
            transform .2s ease,
            box-shadow .2s ease,
            opacity .2s ease;
    }

    .invoice-submit:hover:not(:disabled) {
        transform:
            translateY(-2px);
        box-shadow:
            0 13px 28px
            rgba(5, 150, 105, .28);
    }

    .invoice-submit:disabled {
        cursor: not-allowed;
        opacity: .55;
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

    @media (max-width: 575px) {
        .invoice-create-body {
            padding: 20px;
        }

        .invoice-form {
            padding: 17px;
        }

        .invoice-submit {
            width: 100%;
        }
    }
</style>

@endpush


@section('content')

<div class="container invoice-create-page">

    <section
        class="invoice-create-hero"
        data-reveal="zoom"
    >

        <div class="invoice-create-hero-content">

            <h1>
                Lập hóa đơn
            </h1>


            <div class="invoice-order">

                Phiếu bảo dưỡng:

                <strong>
                    {{ $serviceOrder->order_code }}
                </strong>

            </div>

        </div>

    </section>


    <article
        class="invoice-create-card"
        data-reveal
    >

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


                @foreach (
                    $serviceOrder->items
                    as $item
                )

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
                            }}
                            đ

                        </strong>

                    </div>

                @endforeach

            </section>


            <section class="invoice-section">

                <div class="invoice-section-title">
                    Phụ tùng
                </div>


                @if (
                    $serviceOrder
                        ->parts
                        ->isEmpty()
                )

                    <div class="empty-parts">

                        <i class="bi bi-info-circle me-1"></i>

                        Phiếu bảo dưỡng này
                        không sử dụng phụ tùng.

                    </div>

                @else

                    @foreach (
                        $serviceOrder->parts
                        as $part
                    )

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
                                }}
                                đ

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
                        }}
                        đ

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
                        }}
                        đ

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
                        }}
                        đ

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
                novalidate
            >

                @csrf


                <div class="invoice-form-title">

                    <i class="bi bi-receipt text-success"></i>

                    Thông tin hóa đơn

                </div>


                <div class="mb-3">

                    <label
                        for="discount_amount"
                        class="invoice-label"
                    >

                        Giảm giá

                    </label>


                    <input
                        type="number"
                        id="discount_amount"
                        name="discount_amount"
                        class="
                            form-control
                            invoice-control
                            @error('discount_amount')
                                is-invalid
                            @enderror
                        "
                        min="0"
                        max="{{ $subtotal }}"
                        step="1"
                        inputmode="decimal"
                        value="{{ old(
                            'discount_amount',
                            0
                        ) }}"
                        aria-invalid="{{
                            $errors->has(
                                'discount_amount'
                            )
                                ? 'true'
                                : 'false'
                        }}"
                    >


                    <div class="invoice-hint">

                        Từ 0 đến

                        <strong>
                            {{
                                number_format(
                                    $subtotal,
                                    0,
                                    ',',
                                    '.'
                                )
                            }}
                            đ
                        </strong>.

                        Không thể giảm vượt giá trị hóa đơn.

                    </div>

                </div>


                <div class="mb-0">

                    <label
                        for="note"
                        class="invoice-label"
                    >

                        Ghi chú hóa đơn

                    </label>


                    <textarea
                        id="note"
                        name="note"
                        class="
                            form-control
                            invoice-control
                            @error('note')
                                is-invalid
                            @enderror
                        "
                        rows="4"
                        maxlength="2000"
                        placeholder="Ví dụ: Giảm giá khách hàng thân thiết..."
                        aria-invalid="{{
                            $errors->has(
                                'note'
                            )
                                ? 'true'
                                : 'false'
                        }}"
                    >{{ old('note') }}</textarea>


                    <div class="invoice-hint">
                        Tối đa 2000 ký tự.
                    </div>

                </div>


                <div class="invoice-preview">

                    <div class="invoice-preview-row">

                        <span>
                            Tổng trước giảm giá
                        </span>

                        <strong>

                            {{
                                number_format(
                                    $subtotal,
                                    0,
                                    ',',
                                    '.'
                                )
                            }}
                            đ

                        </strong>

                    </div>


                    <div class="invoice-preview-row mt-2">

                        <span>
                            Giảm giá
                        </span>

                        <strong id="discountPreview">
                            0 đ
                        </strong>

                    </div>


                    <div
                        class="
                            invoice-preview-row
                            invoice-preview-total
                        "
                    >

                        <span>
                            Khách cần thanh toán
                        </span>

                        <strong id="finalTotalPreview">

                            {{
                                number_format(
                                    $subtotal,
                                    0,
                                    ',',
                                    '.'
                                )
                            }}
                            đ

                        </strong>

                    </div>

                </div>


                <button
                    type="button"
                    class="invoice-submit"
                    data-bs-toggle="modal"
                    data-bs-target="#createInvoiceModal"
                >

                    <i class="bi bi-receipt"></i>

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

                    <i class="bi bi-receipt"></i>

                </div>


                <h3>
                    Xác nhận lập hóa đơn?
                </h3>


                <p class="text-secondary">

                    Sau khi tạo, dữ liệu dịch vụ,
                    phụ tùng và giá trị thanh toán
                    sẽ được lưu snapshot từ
                    phiếu bảo dưỡng hiện tại.

                </p>


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
                        id="confirmCreateInvoiceButton"
                        class="btn btn-success"
                        onclick="submitInvoiceForm(this)"
                    >

                        Xác nhận lập

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function () {
            const discountInput =
                document.getElementById(
                    'discount_amount'
                );

            const discountPreview =
                document.getElementById(
                    'discountPreview'
                );

            const finalTotalPreview =
                document.getElementById(
                    'finalTotalPreview'
                );


            const subtotal =
                Number(
                    '{{ (float) $subtotal }}'
                );


            function formatCurrency(
                amount
            ) {
                return Math.max(
                    0,
                    amount
                ).toLocaleString(
                    'vi-VN'
                )
                + ' đ';
            }


            function updateInvoicePreview() {
                if (
                    !discountInput
                    ||
                    !discountPreview
                    ||
                    !finalTotalPreview
                ) {
                    return;
                }


                let discount =
                    Number(
                        discountInput.value
                    );


                if (
                    !Number.isFinite(
                        discount
                    )
                    ||
                    discount < 0
                ) {
                    discount =
                        0;
                }


                discountPreview.textContent =
                    formatCurrency(
                        discount
                    );


                finalTotalPreview.textContent =
                    formatCurrency(
                        subtotal
                        -
                        discount
                    );
            }


            if (discountInput) {
                discountInput.addEventListener(
                    'input',
                    updateInvoicePreview
                );


                updateInvoicePreview();
            }
        }
    );


    function submitInvoiceForm(
        button
    ) {
        const form =
            document.getElementById(
                'invoiceCreateForm'
            );


        if (!form) {
            return;
        }


        button.disabled =
            true;


        button.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>'
            + 'Đang lập hóa đơn...';


        form.submit();
    }
</script>

@endpush