@extends('layouts.app')


@section(
    'title',
    'Nhập kho phụ tùng - AutoCare Long Biên'
)


@push('styles')

<style>
    .stock-in-page {
        max-width: 930px;
    }

    .stock-in-hero {
        position: relative;
        overflow: hidden;
        padding: 32px;
        margin-bottom: 24px;
        border-radius: 27px;
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

    .stock-in-hero::before {
        content: "";
        position: absolute;
        width: 340px;
        height: 340px;
        right: -100px;
        top: -200px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, .4),
                transparent 70%
            );
    }

    .stock-in-hero-content {
        position: relative;
        z-index: 2;
    }

    .stock-in-code {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #bfdbfe;
        font-size: 11px;
        font-weight: 800;
    }

    .stock-in-hero h1 {
        margin: 8px 0 0;
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

    .stock-in-hero p {
        max-width: 620px;
        margin: 10px 0 0;
        color: #cbd5e1;
        line-height: 1.7;
    }

    .stock-in-card {
        border:
            1px solid
            rgba(255, 255, 255, .88);
        border-radius: 21px;
        background:
            rgba(255, 255, 255, .94);
        box-shadow:
            var(--ac-shadow);
        backdrop-filter:
            blur(16px);
    }

    .stock-in-card-body {
        padding: 27px;
    }

    .part-information-grid {
        display: grid;
        grid-template-columns:
            repeat(
                3,
                minmax(0, 1fr)
            );
        gap: 12px;
    }

    .part-information-card {
        padding: 15px;
        border:
            1px solid
            #e7edf4;
        border-radius: 14px;
        background:
            linear-gradient(
                180deg,
                #f8fbff,
                #f5f8fc
            );
    }

    .part-information-icon {
        margin-bottom: 7px;
        color: #2563eb;
        font-size: 17px;
    }

    .part-information-label {
        color: #64748b;
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .part-information-value {
        margin-top: 5px;
        color: #0f172a;
        font-size: 13px;
        font-weight: 900;
    }

    .stock-status {
        margin-top: 20px;
        padding: 15px 17px;
        border:
            1px solid
            #bfdbfe;
        border-radius: 14px;
        color: #1e40af;
        background:
            #eff6ff;
        font-size: 12px;
        line-height: 1.65;
    }

    .stock-in-form-panel {
        margin-top: 25px;
        padding: 22px;
        border:
            1px solid
            #e5ecf4;
        border-radius: 17px;
        background: #f8fbff;
    }

    .stock-in-form-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 18px;
        color: #0f172a;
        font-size: 17px;
        font-weight: 900;
    }

    .stock-in-label {
        margin-bottom: 8px;
        color: #334155;
        font-size: 12px;
        font-weight: 850;
    }

    .stock-in-control {
        min-height: 48px;
    }

    textarea.stock-in-control {
        min-height: auto;
    }

    .stock-in-control.is-invalid {
        border-color: #f87171;
        background-color: #fffafa;
        box-shadow:
            0 0 0 3px
            rgba(239, 68, 68, .07);
    }

    .stock-in-hint {
        margin-top: 6px;
        color: #64748b;
        font-size: 10px;
        line-height: 1.55;
    }

    .stock-preview {
        display: none;
        margin-top: 20px;
        padding: 15px;
        border:
            1px solid
            #bfdbfe;
        border-radius: 14px;
        color: #1e3a8a;
        background:
            linear-gradient(
                135deg,
                #eff6ff,
                #ecfeff
            );
        font-size: 12px;
        line-height: 1.7;
    }

    .stock-preview.show {
        display: block;
    }

    .stock-preview strong {
        color: #0f172a;
    }

    .stock-in-submit {
        min-height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 21px;
        padding: 0 20px;
        border: none;
        border-radius: 12px;
        color: white;
        background:
            linear-gradient(
                135deg,
                #1683ff,
                #4f46e5
            );
        box-shadow:
            0 10px 25px
            rgba(37, 99, 235, .22);
        font-size: 12px;
        font-weight: 850;
        transition:
            transform .2s ease,
            box-shadow .2s ease;
    }

    .stock-in-submit:hover {
        transform:
            translateY(-2px);
        box-shadow:
            0 14px 30px
            rgba(37, 99, 235, .28);
    }

    .stock-in-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 20px;
        color: #64748b;
        text-decoration: none;
        font-size: 12px;
        font-weight: 750;
    }

    @media (max-width: 767px) {
        .part-information-grid {
            grid-template-columns:
                repeat(
                    2,
                    1fr
                );
        }
    }

    @media (max-width: 575px) {
        .part-information-grid {
            grid-template-columns:
                1fr;
        }

        .stock-in-card-body {
            padding: 20px;
        }

        .stock-in-form-panel {
            padding: 18px;
        }

        .stock-in-submit {
            width: 100%;
        }
    }
</style>

@endpush


@section('content')

<div class="container stock-in-page">

    <section
        class="stock-in-hero"
        data-reveal="zoom"
    >

        <div class="stock-in-hero-content">

            <div class="stock-in-code">

                <i class="bi bi-box-seam"></i>

                {{ $part->code }}

            </div>

            <h1>
                Nhập kho phụ tùng
            </h1>

            <p>

                Cập nhật số lượng tồn kho và
                giá nhập gần nhất cho
                {{ $part->name }}.

            </p>

        </div>

    </section>


    <article
        class="stock-in-card"
        data-reveal
    >

        <div class="stock-in-card-body">

            <div class="part-information-grid">

                <div class="part-information-card">

                    <div class="part-information-icon">

                        <i class="bi bi-box"></i>

                    </div>

                    <div class="part-information-label">
                        Phụ tùng
                    </div>

                    <div class="part-information-value">
                        {{ $part->name }}
                    </div>

                </div>


                <div class="part-information-card">

                    <div class="part-information-icon">

                        <i class="bi bi-rulers"></i>

                    </div>

                    <div class="part-information-label">
                        Đơn vị tính
                    </div>

                    <div class="part-information-value">
                        {{ $part->unit }}
                    </div>

                </div>


                <div class="part-information-card">

                    <div class="part-information-icon">

                        <i class="bi bi-boxes"></i>

                    </div>

                    <div class="part-information-label">
                        Tồn hiện tại
                    </div>

                    <div class="part-information-value">

                        {{
                            number_format(
                                $part->stock_quantity,
                                0,
                                ',',
                                '.'
                            )
                        }}

                        {{ $part->unit }}

                    </div>

                </div>


                <div class="part-information-card">

                    <div class="part-information-icon">

                        <i class="bi bi-exclamation-triangle"></i>

                    </div>

                    <div class="part-information-label">
                        Tồn tối thiểu
                    </div>

                    <div class="part-information-value">

                        {{
                            number_format(
                                $part->minimum_stock,
                                0,
                                ',',
                                '.'
                            )
                        }}

                        {{ $part->unit }}

                    </div>

                </div>


                <div class="part-information-card">

                    <div class="part-information-icon">

                        <i class="bi bi-cash"></i>

                    </div>

                    <div class="part-information-label">
                        Giá nhập gần nhất
                    </div>

                    <div class="part-information-value">

                        {{
                            number_format(
                                $part->cost_price,
                                0,
                                ',',
                                '.'
                            )
                        }}
                        đ

                    </div>

                </div>


                <div class="part-information-card">

                    <div class="part-information-icon">

                        <i class="bi bi-tag"></i>

                    </div>

                    <div class="part-information-label">
                        Giá bán
                    </div>

                    <div class="part-information-value">

                        {{
                            number_format(
                                $part->selling_price,
                                0,
                                ',',
                                '.'
                            )
                        }}
                        đ

                    </div>

                </div>

            </div>


            <div class="stock-status">

                <i class="bi bi-info-circle-fill me-1"></i>

                Sau khi nhập kho thành công,
                số lượng tồn sẽ được cộng thêm và
                giá nhập mới sẽ trở thành
                <strong>giá nhập gần nhất</strong>
                của phụ tùng.

            </div>


            <form
                id="stockInForm"
                method="POST"
                action="{{ route(
                    'staff.parts.stock-in',
                    $part->id
                ) }}"
                novalidate
            >

                @csrf


                <div class="stock-in-form-panel">

                    <div class="stock-in-form-title">

                        <i class="bi bi-box-arrow-in-down text-primary"></i>

                        Thông tin nhập kho

                    </div>


                    <div class="row g-3">

                        <div class="col-md-6">

                            <label
                                for="quantity"
                                class="stock-in-label"
                            >

                                Số lượng nhập *

                            </label>


                            <input
                                type="number"
                                id="quantity"
                                name="quantity"
                                class="
                                    form-control
                                    stock-in-control
                                    @error('quantity')
                                        is-invalid
                                    @enderror
                                "
                                min="1"
                                step="1"
                                inputmode="numeric"
                                value="{{ old('quantity') }}"
                                aria-invalid="{{
                                    $errors->has(
                                        'quantity'
                                    )
                                        ? 'true'
                                        : 'false'
                                }}"
                            >


                            <div class="stock-in-hint">

                                Nhập số nguyên từ 1 trở lên.

                                Đơn vị:
                                <strong>
                                    {{ $part->unit }}
                                </strong>.

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label
                                for="unit_cost"
                                class="stock-in-label"
                            >

                                Giá nhập / đơn vị *

                            </label>


                            <input
                                type="number"
                                id="unit_cost"
                                name="unit_cost"
                                class="
                                    form-control
                                    stock-in-control
                                    @error('unit_cost')
                                        is-invalid
                                    @enderror
                                "
                                min="0.01"
                                step="0.01"
                                inputmode="decimal"
                                value="{{ old(
                                    'unit_cost',
                                    $part->cost_price
                                ) }}"
                                aria-invalid="{{
                                    $errors->has(
                                        'unit_cost'
                                    )
                                        ? 'true'
                                        : 'false'
                                }}"
                            >


                            <div class="stock-in-hint">

                                Phải lớn hơn 0.

                                Giá này sẽ được lưu
                                là giá nhập gần nhất.

                            </div>

                        </div>


                        <div class="col-12">

                            <label
                                for="note"
                                class="stock-in-label"
                            >

                                Ghi chú

                            </label>


                            <textarea
                                id="note"
                                name="note"
                                class="
                                    form-control
                                    stock-in-control
                                    @error('note')
                                        is-invalid
                                    @enderror
                                "
                                rows="4"
                                maxlength="1000"
                                placeholder="Ví dụ: Nhập hàng bổ sung từ nhà cung cấp..."
                                aria-invalid="{{
                                    $errors->has(
                                        'note'
                                    )
                                        ? 'true'
                                        : 'false'
                                }}"
                            >{{ old('note') }}</textarea>


                            <div class="stock-in-hint">

                                Tối đa 1000 ký tự.

                            </div>

                        </div>

                    </div>


                    <div
                        id="stockPreview"
                        class="stock-preview"
                    >

                        Tồn hiện tại:

                        <strong>
                            {{
                                number_format(
                                    $part->stock_quantity,
                                    0,
                                    ',',
                                    '.'
                                )
                            }}
                            {{ $part->unit }}
                        </strong>

                        →

                        Tồn sau nhập:

                        <strong id="stockAfterPreview"></strong>

                    </div>


                    <button
                        type="button"
                        class="stock-in-submit"
                        data-bs-toggle="modal"
                        data-bs-target="#confirmStockInModal"
                    >

                        <i class="bi bi-box-arrow-in-down"></i>

                        Xác nhận nhập kho

                    </button>

                </div>

            </form>


            <a
                href="{{ route(
                    'staff.parts.index'
                ) }}"
                class="stock-in-back"
            >

                <i class="bi bi-arrow-left"></i>

                Quay lại kho phụ tùng

            </a>

        </div>

    </article>

</div>


<div
    class="modal fade"
    id="confirmStockInModal"
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

                <div class="fs-1 text-primary mb-3">

                    <i class="bi bi-box-arrow-in-down"></i>

                </div>


                <h3>
                    Xác nhận nhập kho?
                </h3>


                <p class="text-secondary">

                    Hệ thống sẽ cộng số lượng
                    vào tồn kho của

                    <strong>
                        {{ $part->name }}
                    </strong>

                    và cập nhật giá nhập gần nhất.

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
                        id="confirmStockInButton"
                        class="btn btn-primary"
                        onclick="submitStockInForm(this)"
                    >

                        Nhập kho

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
            const quantityInput =
                document.getElementById(
                    'quantity'
                );

            const stockPreview =
                document.getElementById(
                    'stockPreview'
                );

            const stockAfterPreview =
                document.getElementById(
                    'stockAfterPreview'
                );


            const currentStock =
                Number.parseInt(
                    '{{ (int) $part->stock_quantity }}',
                    10
                );


            function updateStockPreview() {
                if (
                    !quantityInput
                    ||
                    !stockPreview
                    ||
                    !stockAfterPreview
                ) {
                    return;
                }


                const quantity =
                    Number.parseInt(
                        quantityInput.value,
                        10
                    );


                if (
                    Number.isNaN(quantity)
                    ||
                    quantity < 1
                ) {
                    stockPreview.classList.remove(
                        'show'
                    );

                    stockAfterPreview.textContent =
                        '';

                    return;
                }


                const stockAfter =
                    currentStock
                    +
                    quantity;


                stockAfterPreview.textContent =
                    stockAfter.toLocaleString(
                        'vi-VN'
                    )
                    +
                    ' {{ $part->unit }}';


                stockPreview.classList.add(
                    'show'
                );
            }


            if (quantityInput) {
                quantityInput.addEventListener(
                    'input',
                    updateStockPreview
                );

                updateStockPreview();
            }
        }
    );


    function submitStockInForm(
        button
    ) {
        const form =
            document.getElementById(
                'stockInForm'
            );


        if (!form) {
            return;
        }


        button.disabled =
            true;


        button.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>'
            + 'Đang nhập kho...';


        form.submit();
    }
</script>

@endpush