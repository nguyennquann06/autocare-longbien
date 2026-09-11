@extends('layouts.app')


@section(
    'title',
    'Phiếu bảo dưỡng - AutoCare Long Biên'
)


@push('styles')

<style>
    .service-order-page {
        max-width: 1120px;
    }

    .order-hero {
        position: relative;
        overflow: hidden;
        padding: 31px;
        margin-bottom: 22px;
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

    .order-hero::before {
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

    .order-hero-inner {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .order-code {
        color: #bfdbfe;
        font-size: 11px;
        font-weight: 800;
    }

    .order-hero h1 {
        margin: 7px 0 0;
        color: white;
        font-size:
            clamp(2rem, 4vw, 3.2rem);
        font-weight: 900;
        letter-spacing: -.055em;
    }

    .order-card {
        margin-bottom: 22px;
        border:
            1px solid
            rgba(255, 255, 255, .88);
        border-radius: 21px;
        background:
            rgba(255, 255, 255, .92);
        box-shadow: var(--ac-shadow);
        backdrop-filter: blur(16px);
    }

    .order-card-body {
        padding: 26px;
    }

    .info-grid {
        display: grid;
        grid-template-columns:
            repeat(5, minmax(0, 1fr));
        gap: 12px;
    }

    .info-card {
        padding: 14px;
        border: 1px solid #e7edf4;
        border-radius: 14px;
        background: #f8fbff;
    }

    .info-label {
        color: #64748b;
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .info-value {
        margin-top: 5px;
        color: #0f172a;
        font-size: 12px;
        font-weight: 850;
    }

    .order-section {
        margin-top: 29px;
    }

    .order-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 15px;
        color: #0f172a;
        font-size: 17px;
        font-weight: 900;
    }

    .item-row {
        display: grid;
        grid-template-columns:
            minmax(0, 1fr)
            110px
            160px;
        gap: 15px;
        padding: 13px 0;
        border-bottom: 1px solid #edf1f6;
        font-size: 12px;
    }

    .item-price {
        color: #1d4ed8;
        text-align: right;
        font-weight: 900;
    }

    .order-note {
        padding: 15px;
        border: 1px solid #e4ebf3;
        border-radius: 14px;
        color: #475569;
        background: #f8fbff;
        font-size: 12px;
        line-height: 1.7;
    }

    .part-form {
        margin-top: 18px;
        padding: 20px;
        border: 1px solid #e5ecf4;
        border-radius: 16px;
        background: #f8fbff;
    }

    .order-totals {
        margin-top: 28px;
        padding: 23px;
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

    .invoice-panel {
        padding: 18px;
        border-radius: 15px;
        background:
            linear-gradient(
                135deg,
                #ecfdf5,
                #f0fdf4
            );
        border: 1px solid #bbf7d0;
        color: #065f46;
    }

    .staff-back {
        display: inline-flex;
        gap: 7px;
        margin-top: 20px;
        color: #64748b;
        text-decoration: none;
        font-size: 12px;
        font-weight: 750;
    }

    @media (max-width: 1100px) {
        .info-grid {
            grid-template-columns:
                repeat(3, 1fr);
        }
    }

    @media (max-width: 767px) {
        .info-grid {
            grid-template-columns:
                repeat(2, 1fr);
        }

        .item-row {
            grid-template-columns: 1fr;
        }

        .item-price {
            text-align: left;
        }
    }

    @media (max-width: 575px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@endpush


@section('content')

<div class="container service-order-page">

    @php
        $statusText = match (
            $serviceOrder->status
        ) {
            'RECEIVED' =>
                'Đã tiếp nhận',

            'IN_PROGRESS' =>
                'Đang thực hiện',

            'COMPLETED' =>
                'Hoàn thành',

            'CANCELLED' =>
                'Đã hủy',

            default =>
                $serviceOrder->status,
        };

        $statusClass = match (
            $serviceOrder->status
        ) {
            'RECEIVED' =>
                'status-confirmed',

            'IN_PROGRESS' =>
                'status-progress',

            'COMPLETED' =>
                'status-completed',

            'CANCELLED' =>
                'status-cancelled',

            default =>
                'status-confirmed',
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

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if (session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <section class="order-hero" data-reveal="zoom">

        <div class="order-hero-inner">

            <div>

                <div class="order-code">
                    <i class="bi bi-hash"></i>
                    {{ $serviceOrder->order_code }}
                </div>

                <h1>
                    Phiếu bảo dưỡng
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

    </section>


    <article class="order-card" data-reveal>

        <div class="order-card-body">

            <div class="info-grid">

                <div class="info-card">
                    <div class="info-label">Khách hàng</div>
                    <div class="info-value">
                        {{ $serviceOrder->customer->full_name }}
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-label">Xe</div>
                    <div class="info-value">
                        {{ $serviceOrder->vehicle->brand->name }}
                        {{ $serviceOrder->vehicle->vehicleModel->name }}
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-label">Biển số</div>
                    <div class="info-value">
                        {{ $serviceOrder->vehicle->license_plate }}
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-label">ODO</div>
                    <div class="info-value">
                        {{
                            number_format(
                                $serviceOrder->received_mileage
                            )
                        }} km
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-label">Kỹ thuật viên</div>
                    <div class="info-value">
                        {{
                            $serviceOrder->technician->name
                            ?? 'Chưa phân công'
                        }}
                    </div>
                </div>

            </div>


            <section class="order-section">

                <div class="order-title">
                    <i class="bi bi-tools text-primary"></i>
                    Dịch vụ
                </div>

                @foreach ($serviceOrder->items as $item)

                    <div class="item-row">

                        <strong>
                            {{ $item->service_name }}
                        </strong>

                        <span>
                            x {{ $item->quantity }}
                        </span>

                        <span class="item-price">
                            {{
                                number_format(
                                    $item->line_total,
                                    0,
                                    ',',
                                    '.'
                                )
                            }} đ
                        </span>

                    </div>

                @endforeach

            </section>


            <section class="order-section">

                <div class="order-title">
                    <i class="bi bi-box-seam text-primary"></i>
                    Phụ tùng
                </div>


                @forelse ($serviceOrder->parts as $part)

                    <div class="item-row">

                        <strong>
                            {{ $part->part_name }}
                        </strong>

                        <span>
                            {{ $part->quantity }}
                            {{ $part->unit }}
                        </span>

                        <span class="item-price">
                            {{
                                number_format(
                                    $part->line_total,
                                    0,
                                    ',',
                                    '.'
                                )
                            }} đ
                        </span>

                    </div>

                @empty

                    <div class="order-note">
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
                        class="part-form"
                    >

                        @csrf

                        <h5 class="fw-bold mb-3">
                            Xuất phụ tùng
                        </h5>

                        <div class="row g-3">

                            <div class="col-md-7">

                                <label
                                    for="part_id"
                                    class="form-label"
                                >
                                    Phụ tùng
                                </label>

                                <select
                                    id="part_id"
                                    name="part_id"
                                    class="form-select"
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

                            </div>


                            <div class="col-md-5">

                                <label
                                    for="quantity"
                                    class="form-label"
                                >
                                    Số lượng
                                </label>

                                <input
                                    type="number"
                                    id="quantity"
                                    name="quantity"
                                    class="form-control"
                                    min="1"
                                    value="1"
                                    required
                                >

                            </div>


                            <div class="col-12">

                                <label
                                    for="note"
                                    class="form-label"
                                >
                                    Ghi chú
                                </label>

                                <textarea
                                    id="note"
                                    name="note"
                                    class="form-control"
                                    rows="3"
                                    maxlength="1000"
                                ></textarea>

                            </div>

                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary mt-3"
                        >
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Xuất phụ tùng
                        </button>

                    </form>

                @endif

            </section>


            <div class="order-totals">

                <div class="total-row">
                    <span>Dịch vụ</span>
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
                    <span>Phụ tùng</span>
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
                    <span>Tổng cộng</span>
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


            @if ($serviceOrder->status === 'COMPLETED')

                <section class="order-section">

                    <div class="order-title">
                        <i class="bi bi-receipt text-success"></i>
                        Hóa đơn
                    </div>


                    @if ($serviceOrder->invoice)

                        <div class="invoice-panel">

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
                            class="btn btn-success mt-3"
                        >
                            Xem hóa đơn
                        </a>

                    @else

                        <div class="invoice-panel">
                            Phiếu đã hoàn thành.
                            Bạn có thể lập hóa đơn cho khách hàng.
                        </div>

                        <a
                            href="{{ route(
                                'staff.invoices.create',
                                $serviceOrder->id
                            ) }}"
                            class="btn btn-success mt-3"
                        >
                            Lập hóa đơn
                        </a>

                    @endif

                </section>

            @endif


            @if ($serviceOrder->vehicle_condition)

                <section class="order-section">

                    <div class="order-title">
                        Tình trạng xe
                    </div>

                    <div class="order-note">
                        {{ $serviceOrder->vehicle_condition }}
                    </div>

                </section>

            @endif


            @if ($serviceOrder->diagnosis)

                <section class="order-section">

                    <div class="order-title">
                        Chẩn đoán
                    </div>

                    <div class="order-note">
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
                    class="staff-back"
                >
                    <i class="bi bi-arrow-left"></i>
                    Quay lại lịch hẹn
                </a>

            @endif

        </div>

    </article>

</div>

@endsection