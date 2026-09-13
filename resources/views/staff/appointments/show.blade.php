@extends('layouts.app')


@section(
    'title',
    'Xử lý lịch hẹn - AutoCare Long Biên'
)


@push('styles')

<style>
    .staff-appointment-detail {
        max-width: 1100px;
    }

    .staff-detail-hero {
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

    .staff-detail-hero::before {
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

    .staff-detail-hero-inner {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .staff-code {
        color: #bfdbfe;
        font-size: 11px;
        font-weight: 800;
    }

    .staff-detail-hero h1 {
        margin: 7px 0 0;
        color: white;
        font-size:
            clamp(2rem, 4vw, 3.2rem);
        font-weight: 900;
        letter-spacing: -.055em;
    }

    .staff-detail-layout {
        display: grid;
        grid-template-columns:
            minmax(0, 1.4fr)
            minmax(290px, .6fr);
        gap: 22px;
        align-items: start;
    }

    .staff-card {
        border:
            1px solid
            rgba(255, 255, 255, .88);
        border-radius: 21px;
        background:
            rgba(255, 255, 255, .92);
        box-shadow: var(--ac-shadow);
        backdrop-filter: blur(16px);
    }

    .staff-card-body {
        padding: 26px;
    }

    .staff-section {
        padding: 24px 0;
        border-bottom: 1px solid #edf1f6;
    }

    .staff-section:first-child {
        padding-top: 0;
    }

    .staff-section:last-child {
        padding-bottom: 0;
        border-bottom: none;
    }

    .staff-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 17px;
        color: #0f172a;
        font-size: 17px;
        font-weight: 900;
    }

    .staff-section-icon {
        width: 37px;
        height: 37px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        color: #2563eb;
        background:
            linear-gradient(
                135deg,
                #dbeafe,
                #ecfeff
            );
    }

    .staff-info-grid {
        display: grid;
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .staff-info {
        padding: 14px;
        border: 1px solid #e7edf4;
        border-radius: 14px;
        background: #f8fbff;
    }

    .staff-info-label {
        color: #64748b;
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .staff-info-value {
        margin-top: 5px;
        color: #0f172a;
        font-size: 12px;
        font-weight: 850;
    }

    .service-line {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        padding: 13px 0;
        border-bottom: 1px solid #edf1f6;
    }

    .service-line:last-child {
        border-bottom: none;
    }

    .service-name {
        color: #0f172a;
        font-size: 13px;
        font-weight: 850;
    }

    .service-price {
        color: #1d4ed8;
        white-space: nowrap;
        font-size: 13px;
        font-weight: 900;
    }

    .staff-note {
        padding: 15px;
        border: 1px solid #e4ebf3;
        border-radius: 14px;
        color: #475569;
        background: #f8fbff;
        line-height: 1.7;
        font-size: 12px;
    }

    .staff-form-box {
        padding: 18px;
        border: 1px solid #e5ecf4;
        border-radius: 15px;
        background: #f8fbff;
    }

    .workflow-card {
        position: sticky;
        top: 100px;
        overflow: hidden;
        padding: 23px;
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
            rgba(13, 47, 105, .26);
    }

    .workflow-title {
        margin-bottom: 18px;
        font-size: 17px;
        font-weight: 900;
    }

    .workflow-box {
        padding: 14px;
        margin-bottom: 12px;
        border:
            1px solid
            rgba(255, 255, 255, .1);
        border-radius: 13px;
        background:
            rgba(255, 255, 255, .07);
        color: #dbeafe;
        font-size: 11px;
        line-height: 1.65;
    }

    .workflow-button {
        width: 100%;
        min-height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        margin-top: 10px;
        border: none;
        border-radius: 12px;
        color: #07111f;
        text-decoration: none;
        background:
            linear-gradient(
                135deg,
                #67e8f9,
                #bfdbfe
            );
        font-size: 12px;
        font-weight: 900;
    }

    .workflow-button.green {
        color: white;
        background:
            linear-gradient(
                135deg,
                #10b981,
                #047857
            );
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

    @media (max-width: 991px) {
        .staff-detail-layout {
            grid-template-columns: 1fr;
        }

        .workflow-card {
            position: static;
        }
    }

    @media (max-width: 575px) {
        .staff-info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@endpush


@section('content')

<div class="container staff-appointment-detail">

    @php
        $statusText = match (
            $appointment->status
        ) {
            'PENDING' =>
                'Chờ xác nhận',

            'CONFIRMED' =>
                'Đã xác nhận',

            'IN_PROGRESS' =>
                'Đang thực hiện',

            'COMPLETED' =>
                'Hoàn thành',

            'CANCELLED' =>
                'Đã hủy',

            default =>
                $appointment->status,
        };

        $statusClass = match (
            $appointment->status
        ) {
            'PENDING' =>
                'status-pending',

            'CONFIRMED' =>
                'status-confirmed',

            'IN_PROGRESS' =>
                'status-progress',

            'COMPLETED' =>
                'status-completed',

            'CANCELLED' =>
                'status-cancelled',

            default =>
                'status-pending',
        };
    @endphp


    <section class="staff-detail-hero" data-reveal="zoom">

        <div class="staff-detail-hero-inner">

            <div>

                <div class="staff-code">
                    <i class="bi bi-hash"></i>
                    {{ $appointment->appointment_code }}
                </div>

                <h1>
                    Xử lý lịch hẹn
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


    <div class="staff-detail-layout">

        <article class="staff-card" data-reveal="left">

            <div class="staff-card-body">

                <section class="staff-section">

                    <div class="staff-section-title">

                        <span class="staff-section-icon">
                            <i class="bi bi-person-vcard"></i>
                        </span>

                        Thông tin lịch hẹn

                    </div>


                    <div class="staff-info-grid">

                        <div class="staff-info">
                            <div class="staff-info-label">
                                Khách hàng
                            </div>
                            <div class="staff-info-value">
                                {{ $appointment->contact_name }}
                            </div>
                        </div>

                        <div class="staff-info">
                            <div class="staff-info-label">
                                Điện thoại
                            </div>
                            <div class="staff-info-value">
                                {{ $appointment->contact_phone }}
                            </div>
                        </div>

                        <div class="staff-info">
                            <div class="staff-info-label">
                                Phương tiện
                            </div>
                            <div class="staff-info-value">

                                {{ $appointment->vehicle->brand->name }}

                                {{ $appointment->vehicle->vehicleModel->name }}

                            </div>
                        </div>

                        <div class="staff-info">
                            <div class="staff-info-label">
                                Biển số
                            </div>
                            <div class="staff-info-value">
                                {{ $appointment->vehicle->license_plate }}
                            </div>
                        </div>

                        <div class="staff-info">
                            <div class="staff-info-label">
                                Ngày hẹn
                            </div>
                            <div class="staff-info-value">

                                {{
                                    $appointment
                                        ->appointment_date
                                        ->format('d/m/Y')
                                }}

                            </div>
                        </div>

                        <div class="staff-info">
                            <div class="staff-info-label">
                                Giờ
                            </div>
                            <div class="staff-info-value">

                                {{
                                    substr(
                                        $appointment
                                            ->appointment_time,
                                        0,
                                        5
                                    )
                                }}

                            </div>
                        </div>

                    </div>

                </section>


                <section class="staff-section">

                    <div class="staff-section-title">

                        <span class="staff-section-icon">
                            <i class="bi bi-tools"></i>
                        </span>

                        Dịch vụ khách đã chọn

                    </div>


                    @foreach ($appointment->services as $service)

                        <div class="service-line">

                            <span class="service-name">
                                {{ $service->name }}
                            </span>

                            <span class="service-price">

                                {{
                                    number_format(
                                        $service->pivot->price,
                                        0,
                                        ',',
                                        '.'
                                    )
                                }} đ

                            </span>

                        </div>

                    @endforeach

                </section>


                @if ($appointment->customer_note)

                    <section class="staff-section">

                        <div class="staff-section-title">

                            <span class="staff-section-icon">
                                <i class="bi bi-chat-left-text"></i>
                            </span>

                            Ghi chú khách hàng

                        </div>

                        <div class="staff-note">
                            {{ $appointment->customer_note }}
                        </div>

                    </section>

                @endif


                @if ($appointment->status === 'PENDING')

                    <section class="staff-section">

                        <div class="staff-section-title">

                            <span class="staff-section-icon">
                                <i class="bi bi-check2-square"></i>
                            </span>

                            Xác nhận lịch hẹn

                        </div>


                        <form
                            method="POST"
                            action="{{ route(
                                'staff.appointments.updateStatus',
                                $appointment->id
                            ) }}"
                            class="staff-form-box"
                        >

                            @csrf
                            @method('PATCH')

                            <input
                                type="hidden"
                                name="status"
                                value="CONFIRMED"
                            >


                            <label
                                for="staff_note"
                                class="form-label fw-bold"
                            >
                                Ghi chú nhân viên
                            </label>

                            <textarea
                                id="staff_note"
                                name="staff_note"
                                class="form-control"
                                rows="4"
                                maxlength="1000"
                            >{{ old(
                                'staff_note',
                                $appointment->staff_note
                            ) }}</textarea>


                            <button
                                type="submit"
                                class="btn btn-primary mt-3"
                            >
                                <i class="bi bi-check-circle me-2"></i>
                                Xác nhận lịch hẹn
                            </button>

                        </form>

                    </section>

                @endif


                @if ($appointment->staff_note)

                    <section class="staff-section">

                        <div class="staff-section-title">

                            <span class="staff-section-icon">
                                <i class="bi bi-chat-square-text"></i>
                            </span>

                            Ghi chú nhân viên

                        </div>

                        <div class="staff-note">
                            {{ $appointment->staff_note }}
                        </div>

                    </section>

                @endif

            </div>

        </article>


        <aside class="workflow-card" data-reveal="right">

            <div class="workflow-title">
                <i class="bi bi-diagram-3 me-2"></i>
                Quy trình xử lý
            </div>


            @if ($appointment->serviceOrder)

                <div class="workflow-box">

                    Phiếu bảo dưỡng:

                    <strong>
                        {{ $appointment->serviceOrder->order_code }}
                    </strong>

                    <br>

                    Trạng thái:

                    <strong>

                        {{
                            match (
                                $appointment
                                    ->serviceOrder
                                    ->status
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
                                    $appointment
                                        ->serviceOrder
                                        ->status,
                            }
                        }}

                    </strong>

                </div>


                <a
                    href="{{ route(
                        'staff.service-orders.show',
                        $appointment->serviceOrder->id
                    ) }}"
                    class="workflow-button"
                >
                    <i class="bi bi-tools"></i>
                    Xem phiếu bảo dưỡng
                </a>


                @if (
                    $appointment
                        ->serviceOrder
                        ->status
                    === 'COMPLETED'
                )

                    @if (
                        $appointment
                            ->serviceOrder
                            ->invoice
                    )

                        <a
                            href="{{ route(
                                'staff.invoices.show',
                                $appointment
                                    ->serviceOrder
                                    ->invoice
                                    ->id
                            ) }}"
                            class="workflow-button green"
                        >
                            <i class="bi bi-receipt"></i>
                            Xem hóa đơn
                        </a>

                    @else

                        <a
                            href="{{ route(
                                'staff.invoices.create',
                                $appointment
                                    ->serviceOrder
                                    ->id
                            ) }}"
                            class="workflow-button green"
                        >
                            <i class="bi bi-receipt-cutoff"></i>
                            Lập hóa đơn
                        </a>

                    @endif

                @endif


            @elseif (
                $appointment->status
                === 'CONFIRMED'
            )

                <div class="workflow-box">

                    Lịch đã được xác nhận.

                    <br><br>

                    Tiếp nhận xe và tạo
                    phiếu bảo dưỡng để
                    bắt đầu quy trình kỹ thuật.

                </div>


                <a
                    href="{{ route(
                        'staff.service-orders.create',
                        $appointment->id
                    ) }}"
                    class="workflow-button"
                >
                    <i class="bi bi-plus-circle"></i>
                    Tạo phiếu bảo dưỡng
                </a>

            @elseif (
                $appointment->status
                === 'CANCELLED'
            )

                <div class="workflow-box">
                    <i class="bi bi-x-circle me-1"></i>
                    Lịch hẹn đã bị khách hàng hủy.
                </div>

            @else

                <div class="workflow-box">
                    Chưa có thao tác tiếp theo.
                </div>

            @endif

        </aside>

    </div>


    <a
        href="{{ route('staff.appointments.index') }}"
        class="staff-back"
    >
        <i class="bi bi-arrow-left"></i>
        Quay lại danh sách lịch
    </a>

</div>

@endsection