@extends('layouts.app')


@section(
    'title',
    'Chi tiết bảo dưỡng - AutoCare Long Biên'
)


@push('styles')

<style>
    .maintenance-detail-page {
        max-width: 1120px;
    }

    .maintenance-detail-hero {
        position: relative;
        overflow: hidden;

        padding: 33px;
        margin-bottom: 24px;

        border-radius: 27px;

        color: white;

        background:
            linear-gradient(
                120deg,
                #06101e 0%,
                #0c3474 52%,
                #1677ff 100%
            );

        box-shadow:
            0 26px 75px
            rgba(22, 119, 255, 0.23);
    }

    .maintenance-detail-hero::before {
        content: "";

        position: absolute;

        width: 350px;
        height: 350px;

        top: -210px;
        right: -100px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, 0.42),
                transparent 70%
            );
    }

    .maintenance-detail-hero::after {
        content: "";

        position: absolute;

        width: 250px;
        height: 250px;

        left: 40%;
        bottom: -210px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(124, 58, 237, 0.35),
                transparent 70%
            );
    }

    .maintenance-detail-hero-inner {
        position: relative;
        z-index: 2;

        display: flex;

        justify-content: space-between;
        align-items: flex-start;

        gap: 22px;

        flex-wrap: wrap;
    }

    .maintenance-order-code {
        display: inline-flex;

        align-items: center;

        gap: 7px;

        margin-bottom: 9px;

        color: #bfdbfe;

        font-size: 12px;
        font-weight: 800;
    }

    .maintenance-detail-hero h1 {
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

    .maintenance-detail-hero p {
        max-width: 640px;

        margin: 10px 0 0;

        color: #cbd5e1;

        line-height: 1.75;
    }

    .maintenance-detail-layout {
        display: grid;

        grid-template-columns:
            minmax(0, 1.35fr)
            minmax(300px, 0.65fr);

        gap: 22px;

        align-items: start;
    }

    .maintenance-card {
        overflow: hidden;

        border:
            1px solid
            rgba(255, 255, 255, 0.88);

        border-radius: 21px;

        background:
            rgba(255, 255, 255, 0.92);

        box-shadow:
            var(--ac-shadow);

        backdrop-filter:
            blur(16px);
    }

    .maintenance-card-body {
        padding: 26px;
    }

    .maintenance-section {
        padding: 25px 0;

        border-bottom:
            1px solid #edf1f6;
    }

    .maintenance-section:first-child {
        padding-top: 0;
    }

    .maintenance-section:last-child {
        padding-bottom: 0;

        border-bottom: none;
    }

    .maintenance-section-title {
        display: flex;

        align-items: center;

        gap: 11px;

        margin-bottom: 18px;

        color: #0f172a;

        font-size: 17px;
        font-weight: 900;
    }

    .maintenance-section-icon {
        width: 38px;
        height: 38px;

        display: flex;

        align-items: center;
        justify-content: center;

        flex: 0 0 auto;

        border-radius: 12px;

        color: #2563eb;

        background:
            linear-gradient(
                135deg,
                #dbeafe,
                #ecfeff
            );
    }

    .maintenance-info-grid {
        display: grid;

        grid-template-columns:
            repeat(
                2,
                minmax(0, 1fr)
            );

        gap: 12px;
    }

    .maintenance-info-box {
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

        transition:
            transform 0.2s ease,
            border-color 0.2s ease;
    }

    .maintenance-info-box:hover {
        transform:
            translateY(-2px);

        border-color: #bfdbfe;
    }

    .maintenance-info-icon {
        margin-bottom: 8px;

        color: #2563eb;

        font-size: 17px;
    }

    .maintenance-info-label {
        color: #64748b;

        font-size: 9px;
        font-weight: 800;

        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .maintenance-info-value {
        margin-top: 5px;

        color: #0f172a;

        font-size: 12px;
        font-weight: 850;
    }

    .maintenance-note {
        position: relative;

        padding: 17px;

        border:
            1px solid #e5ecf4;

        border-radius: 15px;

        color: #475569;

        background:
            linear-gradient(
                135deg,
                #f8fbff,
                #f7faff
            );

        line-height: 1.75;

        font-size: 13px;
    }

    .maintenance-service-item {
        position: relative;

        padding: 17px 0;

        border-bottom:
            1px solid #edf1f6;
    }

    .maintenance-service-item:first-child {
        padding-top: 0;
    }

    .maintenance-service-item:last-child {
        padding-bottom: 0;

        border-bottom: none;
    }

    .maintenance-service-top {
        display: flex;

        justify-content: space-between;
        align-items: flex-start;

        gap: 20px;
    }

    .maintenance-service-name {
        color: #0f172a;

        font-size: 14px;
        font-weight: 900;
    }

    .maintenance-service-price {
        color: #1d4ed8;

        white-space: nowrap;

        font-size: 14px;
        font-weight: 900;
    }

    .maintenance-service-status {
        display: inline-flex;

        align-items: center;

        gap: 6px;

        margin-top: 8px;

        padding: 5px 9px;

        border-radius: 999px;

        color: #047857;

        background: #ecfdf5;

        font-size: 10px;
        font-weight: 850;
    }

    .maintenance-tech-note {
        margin-top: 11px;

        padding: 13px;

        border-left:
            3px solid #60a5fa;

        border-radius:
            0 11px 11px 0;

        color: #475569;

        background: #f8fbff;

        font-size: 12px;

        line-height: 1.7;
    }

    .maintenance-summary {
        position: sticky;

        top: 100px;

        overflow: hidden;

        padding: 24px;

        border-radius: 21px;

        color: white;

        background:
            linear-gradient(
                145deg,
                #06101e,
                #0d2f69 56%,
                #155bd1
            );

        box-shadow:
            0 25px 60px
            rgba(13, 47, 105, 0.26);
    }

    .maintenance-summary::before {
        content: "";

        position: absolute;

        width: 230px;
        height: 230px;

        top: -140px;
        right: -110px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(34, 211, 238, 0.32),
                transparent 70%
            );
    }

    .maintenance-summary-content {
        position: relative;
        z-index: 2;
    }

    .maintenance-summary-icon {
        width: 49px;
        height: 49px;

        display: flex;

        align-items: center;
        justify-content: center;

        margin-bottom: 18px;

        border-radius: 15px;

        color: #67e8f9;

        background:
            rgba(255, 255, 255, 0.09);

        font-size: 21px;
    }

    .maintenance-summary-title {
        margin-bottom: 20px;

        color: white;

        font-size: 18px;
        font-weight: 900;
    }

    .maintenance-summary-row {
        display: flex;

        justify-content: space-between;

        gap: 16px;

        padding: 12px 0;

        border-bottom:
            1px solid
            rgba(255, 255, 255, 0.10);
    }

    .maintenance-summary-label {
        color: #bfdbfe;

        font-size: 11px;
    }

    .maintenance-summary-value {
        color: white;

        text-align: right;

        font-size: 12px;
        font-weight: 850;
    }

    .maintenance-summary-total {
        margin-top: 9px;

        padding-top: 17px;

        border-top:
            1px solid
            rgba(255, 255, 255, 0.17);

        font-size: 18px;
        font-weight: 900;
    }

    .maintenance-completed-mark {
        display: flex;

        align-items: center;

        gap: 10px;

        margin-top: 19px;

        padding: 13px;

        border:
            1px solid
            rgba(16, 185, 129, 0.30);

        border-radius: 13px;

        color: #d1fae5;

        background:
            rgba(16, 185, 129, 0.12);

        font-size: 11px;

        line-height: 1.5;
    }

    .maintenance-completed-mark i {
        color: #6ee7b7;

        font-size: 18px;
    }

    .maintenance-back {
        display: inline-flex;

        align-items: center;

        gap: 7px;

        margin-top: 22px;

        color: #64748b;

        text-decoration: none;

        font-size: 13px;
        font-weight: 750;
    }

    .maintenance-back:hover {
        color: #2563eb;
    }

    @media (max-width: 991px) {
        .maintenance-detail-layout {
            grid-template-columns: 1fr;
        }

        .maintenance-summary {
            position: static;
        }
    }

    @media (max-width: 575px) {
        .maintenance-info-grid {
            grid-template-columns: 1fr;
        }

        .maintenance-detail-hero {
            padding: 24px;
        }

        .maintenance-card-body {
            padding: 20px;
        }

        .maintenance-service-top {
            flex-direction: column;

            gap: 7px;
        }
    }
</style>

@endpush


@section('content')

<div class="container maintenance-detail-page">

    <section
        class="maintenance-detail-hero"
        data-reveal="zoom"
    >

        <div class="maintenance-detail-hero-inner">

            <div>

                <div class="maintenance-order-code">

                    <i class="bi bi-hash"></i>

                    {{ $serviceOrder->order_code }}

                </div>


                <h1>
                    Chi tiết bảo dưỡng
                </h1>


                <p>

                    Hồ sơ bảo dưỡng đã hoàn thành,
                    bao gồm tình trạng xe,
                    các hạng mục kỹ thuật
                    và chi phí thực tế.

                </p>

            </div>


            <span
                class="
                    status-badge
                    status-completed
                "
            >
                Hoàn thành
            </span>

        </div>

    </section>


    <div class="maintenance-detail-layout">

        <article
            class="maintenance-card"
            data-reveal="left"
        >

            <div class="maintenance-card-body">

                {{-- =====================================
                    GENERAL INFORMATION
                ====================================== --}}
                <section class="maintenance-section">

                    <div class="maintenance-section-title">

                        <span class="maintenance-section-icon">

                            <i class="bi bi-car-front-fill"></i>

                        </span>

                        Thông tin bảo dưỡng

                    </div>


                    <div class="maintenance-info-grid">

                        <div class="maintenance-info-box">

                            <div class="maintenance-info-icon">

                                <i class="bi bi-car-front-fill"></i>

                            </div>


                            <div class="maintenance-info-label">
                                Phương tiện
                            </div>


                            <div class="maintenance-info-value">

                                {{ $serviceOrder->vehicle->brand->name }}

                                {{ $serviceOrder->vehicle->vehicleModel->name }}

                            </div>

                        </div>


                        <div class="maintenance-info-box">

                            <div class="maintenance-info-icon">

                                <i class="bi bi-credit-card-2-front"></i>

                            </div>


                            <div class="maintenance-info-label">
                                Biển số
                            </div>


                            <div class="maintenance-info-value">
                                {{ $serviceOrder->vehicle->license_plate }}
                            </div>

                        </div>


                        <div class="maintenance-info-box">

                            <div class="maintenance-info-icon">

                                <i class="bi bi-speedometer2"></i>

                            </div>


                            <div class="maintenance-info-label">
                                ODO bảo dưỡng
                            </div>


                            <div class="maintenance-info-value">

                                {{
                                    number_format(
                                        $serviceOrder->received_mileage
                                    )
                                }} km

                            </div>

                        </div>


                        <div class="maintenance-info-box">

                            <div class="maintenance-info-icon">

                                <i class="bi bi-person-gear"></i>

                            </div>


                            <div class="maintenance-info-label">
                                Kỹ thuật viên
                            </div>


                            <div class="maintenance-info-value">

                                {{
                                    $serviceOrder
                                        ->technician
                                        ->name
                                    ?? 'Không xác định'
                                }}

                            </div>

                        </div>


                        <div class="maintenance-info-box">

                            <div class="maintenance-info-icon">

                                <i class="bi bi-box-arrow-in-down"></i>

                            </div>


                            <div class="maintenance-info-label">
                                Tiếp nhận
                            </div>


                            <div class="maintenance-info-value">

                                {{
                                    $serviceOrder->received_at
                                        ? $serviceOrder
                                            ->received_at
                                            ->format('d/m/Y H:i')
                                        : 'Chưa cập nhật'
                                }}

                            </div>

                        </div>


                        <div class="maintenance-info-box">

                            <div class="maintenance-info-icon">

                                <i class="bi bi-check-circle"></i>

                            </div>


                            <div class="maintenance-info-label">
                                Hoàn thành
                            </div>


                            <div class="maintenance-info-value">

                                {{
                                    $serviceOrder->completed_at
                                        ? $serviceOrder
                                            ->completed_at
                                            ->format('d/m/Y H:i')
                                        : 'Chưa cập nhật'
                                }}

                            </div>

                        </div>

                    </div>

                </section>


                {{-- =====================================
                    VEHICLE CONDITION
                ====================================== --}}
                @if ($serviceOrder->vehicle_condition)

                    <section class="maintenance-section">

                        <div class="maintenance-section-title">

                            <span class="maintenance-section-icon">

                                <i class="bi bi-clipboard2-pulse"></i>

                            </span>

                            Tình trạng xe khi tiếp nhận

                        </div>


                        <div class="maintenance-note">

                            {{ $serviceOrder->vehicle_condition }}

                        </div>

                    </section>

                @endif


                {{-- =====================================
                    DIAGNOSIS
                ====================================== --}}
                @if ($serviceOrder->diagnosis)

                    <section class="maintenance-section">

                        <div class="maintenance-section-title">

                            <span class="maintenance-section-icon">

                                <i class="bi bi-search-heart"></i>

                            </span>

                            Chẩn đoán

                        </div>


                        <div class="maintenance-note">

                            {{ $serviceOrder->diagnosis }}

                        </div>

                    </section>

                @endif


                {{-- =====================================
                    SERVICES
                ====================================== --}}
                <section class="maintenance-section">

                    <div class="maintenance-section-title">

                        <span class="maintenance-section-icon">

                            <i class="bi bi-tools"></i>

                        </span>

                        Các hạng mục đã thực hiện

                    </div>


                    @foreach ($serviceOrder->items as $item)

                        <div class="maintenance-service-item">

                            <div class="maintenance-service-top">

                                <div>

                                    <div class="maintenance-service-name">

                                        {{ $item->service_name }}

                                    </div>


                                    <div class="maintenance-service-status">

                                        <i class="bi bi-check-circle-fill"></i>

                                        Đã hoàn thành

                                    </div>

                                </div>


                                <div class="maintenance-service-price">

                                    {{
                                        number_format(
                                            $item->line_total,
                                            0,
                                            ',',
                                            '.'
                                        )
                                    }} đ

                                </div>

                            </div>


                            @if ($item->technician_note)

                                <div class="maintenance-tech-note">

                                    <strong>

                                        <i class="bi bi-wrench-adjustable me-1"></i>

                                        Ghi chú kỹ thuật:

                                    </strong>

                                    {{ $item->technician_note }}

                                </div>

                            @endif

                        </div>

                    @endforeach

                </section>


                {{-- =====================================
                    TECHNICIAN CONCLUSION
                ====================================== --}}
                @if ($serviceOrder->technician_note)

                    <section class="maintenance-section">

                        <div class="maintenance-section-title">

                            <span class="maintenance-section-icon">

                                <i class="bi bi-chat-square-check"></i>

                            </span>

                            Kết luận của kỹ thuật viên

                        </div>


                        <div class="maintenance-note">

                            {{ $serviceOrder->technician_note }}

                        </div>

                    </section>

                @endif

            </div>

        </article>


        {{-- =========================================
            SUMMARY
        ========================================== --}}
        <aside
            class="maintenance-summary"
            data-reveal="right"
        >

            <div class="maintenance-summary-content">

                <div class="maintenance-summary-icon">

                    <i class="bi bi-receipt-cutoff"></i>

                </div>


                <div class="maintenance-summary-title">
                    Tổng kết bảo dưỡng
                </div>


                <div class="maintenance-summary-row">

                    <span class="maintenance-summary-label">
                        Số hạng mục
                    </span>


                    <span class="maintenance-summary-value">

                        {{
                            $serviceOrder
                                ->items
                                ->count()
                        }}

                    </span>

                </div>


                <div class="maintenance-summary-row">

                    <span class="maintenance-summary-label">
                        ODO
                    </span>


                    <span class="maintenance-summary-value">

                        {{
                            number_format(
                                $serviceOrder->received_mileage
                            )
                        }} km

                    </span>

                </div>


                <div class="maintenance-summary-row">

                    <span class="maintenance-summary-label">
                        Dịch vụ
                    </span>


                    <span class="maintenance-summary-value">

                        {{
                            number_format(
                                $serviceOrder->service_total,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </span>

                </div>


                <div class="maintenance-summary-row">

                    <span class="maintenance-summary-label">
                        Phụ tùng
                    </span>


                    <span class="maintenance-summary-value">

                        {{
                            number_format(
                                $serviceOrder->parts_total,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </span>

                </div>


                <div
                    class="
                        maintenance-summary-row
                        maintenance-summary-total
                    "
                >

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


                <div class="maintenance-completed-mark">

                    <i class="bi bi-patch-check-fill"></i>

                    <span>
                        Phiếu bảo dưỡng này
                        đã được hoàn thành
                        và lưu vào lịch sử xe.
                    </span>

                </div>

            </div>

        </aside>

    </div>


    <a
        href="{{ route('maintenance-history.index') }}"
        class="maintenance-back"
    >

        <i class="bi bi-arrow-left"></i>

        Quay lại lịch sử bảo dưỡng

    </a>

</div>

@endsection