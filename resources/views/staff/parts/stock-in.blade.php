@extends('layouts.app')


@section(
    'title',
    'Nhập kho - AutoCare Long Biên'
)


@push('styles')

<style>
    .stock-in-page {
        max-width: 880px;
    }

    .stock-hero {
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

    .stock-hero::before {
        content: "";
        position: absolute;
        width: 330px;
        height: 330px;
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

    .stock-hero-content {
        position: relative;
        z-index: 2;
    }

    .stock-hero h1 {
        margin: 0;
        color: white;
        font-size:
            clamp(2rem, 4vw, 3.2rem);
        font-weight: 900;
        letter-spacing: -.055em;
    }

    .stock-card {
        border:
            1px solid
            rgba(255, 255, 255, .88);
        border-radius: 21px;
        background:
            rgba(255, 255, 255, .92);
        box-shadow: var(--ac-shadow);
        backdrop-filter: blur(16px);
    }

    .stock-card-body {
        padding: 27px;
    }

    .part-info {
        padding: 20px;
        margin-bottom: 24px;
        border: 1px solid #e5ecf4;
        border-radius: 16px;
        background: #f8fbff;
    }

    .part-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 9px 0;
        border-bottom: 1px solid #e7edf4;
        font-size: 12px;
    }

    .part-row:last-child {
        border-bottom: none;
    }

    .part-label {
        color: #64748b;
    }

    .part-value {
        color: #0f172a;
        text-align: right;
        font-weight: 850;
    }

    .form-box {
        padding: 20px;
        border: 1px solid #e5ecf4;
        border-radius: 16px;
        background: #f8fbff;
    }

    .stock-submit {
        min-height: 47px;
        padding: 0 19px;
        border: none;
        border-radius: 13px;
        color: white;
        background:
            linear-gradient(
                135deg,
                #1683ff,
                #4f46e5
            );
        font-weight: 850;
    }

    .stock-back {
        display: inline-flex;
        gap: 7px;
        margin-top: 20px;
        color: #64748b;
        text-decoration: none;
        font-size: 12px;
        font-weight: 750;
    }
</style>

@endpush


@section('content')

<div class="container stock-in-page">

    <section class="stock-hero" data-reveal="zoom">

        <div class="stock-hero-content">

            <h1>
                Nhập kho phụ tùng
            </h1>

        </div>

    </section>


    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>
                Vui lòng kiểm tra lại:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <article class="stock-card" data-reveal>

        <div class="stock-card-body">

            <div class="part-info">

                <div class="part-row">
                    <span class="part-label">Mã phụ tùng</span>
                    <span class="part-value">
                        {{ $part->code }}
                    </span>
                </div>

                <div class="part-row">
                    <span class="part-label">Tên phụ tùng</span>
                    <span class="part-value">
                        {{ $part->name }}
                    </span>
                </div>

                <div class="part-row">
                    <span class="part-label">Đơn vị tính</span>
                    <span class="part-value">
                        {{ $part->unit }}
                    </span>
                </div>

                <div class="part-row">
                    <span class="part-label">Tồn hiện tại</span>
                    <span class="part-value">
                        {{
                            number_format(
                                $part->stock_quantity
                            )
                        }}
                        {{ $part->unit }}
                    </span>
                </div>

                <div class="part-row">
                    <span class="part-label">Tồn tối thiểu</span>
                    <span class="part-value">
                        {{
                            number_format(
                                $part->minimum_stock
                            )
                        }}
                        {{ $part->unit }}
                    </span>
                </div>

                <div class="part-row">
                    <span class="part-label">
                        Giá nhập gần nhất
                    </span>

                    <span class="part-value">
                        {{
                            number_format(
                                $part->cost_price,
                                0,
                                ',',
                                '.'
                            )
                        }} đ
                    </span>
                </div>

            </div>


            <form
                id="stockInForm"
                method="POST"
                action="{{ route(
                    'staff.parts.stock-in',
                    $part->id
                ) }}"
                class="form-box"
            >

                @csrf


                <div class="mb-3">

                    <label
                        for="quantity"
                        class="form-label fw-bold"
                    >
                        Số lượng nhập *
                    </label>

                    <input
                        type="number"
                        id="quantity"
                        name="quantity"
                        class="form-control"
                        min="1"
                        step="1"
                        value="{{ old('quantity') }}"
                        required
                    >

                    <div class="form-text">
                        Đơn vị:
                        {{ $part->unit }}
                    </div>

                </div>


                <div class="mb-3">

                    <label
                        for="unit_cost"
                        class="form-label fw-bold"
                    >
                        Giá nhập / đơn vị *
                    </label>

                    <input
                        type="number"
                        id="unit_cost"
                        name="unit_cost"
                        class="form-control"
                        min="0.01"
                        step="0.01"
                        value="{{ old(
                            'unit_cost',
                            $part->cost_price
                        ) }}"
                        required
                    >

                    <div class="form-text">
                        Giá này sẽ trở thành
                        giá nhập gần nhất của phụ tùng.
                    </div>

                </div>


                <div class="mb-3">

                    <label
                        for="note"
                        class="form-label fw-bold"
                    >
                        Ghi chú
                    </label>

                    <textarea
                        id="note"
                        name="note"
                        class="form-control"
                        rows="4"
                        maxlength="1000"
                        placeholder="Ví dụ: Nhập hàng bổ sung từ nhà cung cấp..."
                    >{{ old('note') }}</textarea>

                </div>


                <button
                    type="button"
                    class="stock-submit"
                    data-bs-toggle="modal"
                    data-bs-target="#stockInModal"
                >
                    <i class="bi bi-box-arrow-in-down me-2"></i>
                    Xác nhận nhập kho
                </button>

            </form>


            <a
                href="{{ route('staff.parts.index') }}"
                class="stock-back"
            >
                <i class="bi bi-arrow-left"></i>
                Quay lại kho phụ tùng
            </a>

        </div>

    </article>

</div>


<div
    class="modal fade"
    id="stockInModal"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 rounded-4 shadow-lg">

            <div class="modal-body p-4 p-md-5 text-center">

                <div class="fs-1 text-primary mb-3">
                    <i class="bi bi-box-arrow-in-down"></i>
                </div>

                <h3>
                    Xác nhận nhập kho?
                </h3>

                <p class="text-secondary">
                    Số lượng nhập sẽ được cộng
                    trực tiếp vào tồn kho của
                    <strong>{{ $part->name }}</strong>.
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
                        class="btn btn-primary"
                        onclick="
                            document
                                .getElementById(
                                    'stockInForm'
                                )
                                .submit();
                        "
                    >
                        Xác nhận nhập
                    </button>

                </div>

            </div>

        </div>

    </div>
</div>

@endsection