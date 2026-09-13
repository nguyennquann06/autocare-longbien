@extends('layouts.app')


@section(
    'title',
    'Dashboard - AutoCare Long Biên'
)


@push('styles')

<style>
    .staff-dashboard {
        max-width: 1280px;
    }

    .staff-hero {
        position: relative;
        overflow: hidden;
        padding: 36px;
        margin-bottom: 26px;
        border-radius: 28px;
        color: white;
        background:
            linear-gradient(
                120deg,
                #06101e,
                #0b2f6b 52%,
                #1467df
            );
        box-shadow:
            0 28px 75px
            rgba(20, 103, 223, 0.23);
    }

    .staff-hero::before {
        content: "";
        position: absolute;
        width: 380px;
        height: 380px;
        top: -230px;
        right: -110px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, 0.42),
                transparent 70%
            );
    }

    .staff-hero-content {
        position: relative;
        z-index: 2;
    }

    .staff-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        margin-bottom: 16px;
        border:
            1px solid
            rgba(255, 255, 255, 0.16);
        border-radius: 999px;
        background:
            rgba(255, 255, 255, 0.08);
        color: #dbeafe;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .07em;
    }

    .staff-chip-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #67e8f9;
        box-shadow: 0 0 12px #67e8f9;
    }

    .staff-hero h1 {
        margin: 0;
        color: white;
        font-size:
            clamp(2rem, 4vw, 3.5rem);
        font-weight: 900;
        letter-spacing: -.055em;
    }

    .staff-hero p {
        max-width: 700px;
        margin: 12px 0 0;
        color: #cbd5e1;
        line-height: 1.8;
    }

    .staff-stat-grid {
        display: grid;
        grid-template-columns:
            repeat(4, minmax(0, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .staff-stat {
        position: relative;
        overflow: hidden;
        min-height: 150px;
        padding: 20px;
        border:
            1px solid
            rgba(255, 255, 255, .88);
        border-radius: 19px;
        background:
            rgba(255, 255, 255, .92);
        box-shadow: var(--ac-shadow);
        backdrop-filter: blur(16px);
        transition:
            transform .23s ease,
            box-shadow .23s ease;
    }

    .staff-stat:hover {
        transform: translateY(-5px);
        box-shadow: var(--ac-shadow-lg);
    }

    .staff-stat-icon {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
        border-radius: 14px;
        color: #2563eb;
        background:
            linear-gradient(
                135deg,
                #dbeafe,
                #ecfeff
            );
        font-size: 20px;
    }

    .staff-stat.warning .staff-stat-icon {
        color: #d97706;
        background: #fff7ed;
    }

    .staff-stat.success .staff-stat-icon {
        color: #059669;
        background: #ecfdf5;
    }

    .staff-stat.purple .staff-stat-icon {
        color: #7c3aed;
        background: #f5f3ff;
    }

    .staff-stat-label {
        color: #64748b;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .staff-stat-value {
        margin-top: 6px;
        color: #0f172a;
        font-size: 25px;
        font-weight: 900;
        letter-spacing: -.04em;
    }

    .staff-content-grid {
        display: grid;
        grid-template-columns:
            minmax(0, 1.4fr)
            minmax(300px, .6fr);
        gap: 22px;
    }

    .staff-panel {
        overflow: hidden;
        border:
            1px solid
            rgba(255, 255, 255, .88);
        border-radius: 21px;
        background:
            rgba(255, 255, 255, .92);
        box-shadow: var(--ac-shadow);
        backdrop-filter: blur(16px);
    }

    .staff-panel-body {
        padding: 25px;
    }

    .staff-panel-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        color: #0f172a;
        font-size: 18px;
        font-weight: 900;
    }

    .staff-panel-icon {
        width: 38px;
        height: 38px;
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

    .staff-record {
        padding: 15px 0;
        border-bottom: 1px solid #edf1f6;
    }

    .staff-record:last-child {
        border-bottom: none;
    }

    .staff-record-top {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        align-items: flex-start;
    }

    .staff-record-code {
        color: #0f172a;
        font-size: 13px;
        font-weight: 900;
    }

    .staff-record-name {
        margin-top: 4px;
        color: #334155;
        font-size: 13px;
        font-weight: 750;
    }

    .staff-record-meta {
        margin-top: 5px;
        color: #64748b;
        font-size: 11px;
        line-height: 1.7;
    }

    .staff-record-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-top: 9px;
        color: #2563eb;
        text-decoration: none;
        font-size: 11px;
        font-weight: 850;
    }

    .invoice-amount {
        margin-top: 6px;
        color: #059669;
        font-size: 14px;
        font-weight: 900;
    }

    .staff-empty {
        padding: 25px;
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
        color: #64748b;
        background: #f8fbff;
        text-align: center;
    }

    .quick-actions {
        margin-top: 24px;
    }

    .quick-grid {
        display: grid;
        grid-template-columns:
            repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .quick-card {
        min-height: 150px;
        padding: 20px;
        border-radius: 17px;
        color: white;
        text-decoration: none;
        background:
            linear-gradient(
                145deg,
                #07111f,
                #0d3476
            );
        box-shadow:
            0 18px 40px
            rgba(13, 52, 118, .18);
        transition:
            transform .23s ease,
            box-shadow .23s ease;
    }

    .quick-card:hover {
        color: white;
        transform: translateY(-5px);
        box-shadow:
            0 25px 55px
            rgba(13, 52, 118, .27);
    }

    .quick-icon {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        border-radius: 14px;
        color: #67e8f9;
        background:
            rgba(255, 255, 255, .09);
        font-size: 20px;
    }

    .quick-title {
        font-size: 13px;
        font-weight: 900;
    }

    .quick-text {
        margin-top: 7px;
        color: #bfdbfe;
        font-size: 10px;
        line-height: 1.6;
    }

    @media (max-width: 1199px) {
        .staff-stat-grid {
            grid-template-columns:
                repeat(3, 1fr);
        }

        .quick-grid {
            grid-template-columns:
                repeat(2, 1fr);
        }
    }

    @media (max-width: 991px) {
        .staff-content-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .staff-stat-grid {
            grid-template-columns:
                repeat(2, 1fr);
        }
    }

    @media (max-width: 575px) {
        .staff-stat-grid,
        .quick-grid {
            grid-template-columns: 1fr;
        }

        .staff-hero {
            padding: 25px;
        }
    }
</style>

@endpush


@section('content')

<div class="container staff-dashboard">

    <section class="staff-hero" data-reveal="zoom">

        <div class="staff-hero-content">

            <div class="staff-chip">
                <span class="staff-chip-dot"></span>
                Operations Center
            </div>

            <h1>
                Tổng quan hoạt động
            </h1>

            <p>
                Theo dõi lịch hẹn, phiếu bảo dưỡng,
                doanh thu, hóa đơn và kho phụ tùng
                trực tiếp từ dữ liệu vận hành
                của AutoCare Long Biên.
            </p>

        </div>

    </section>


    <section class="staff-stat-grid">

        <div class="staff-stat warning" data-reveal data-tilt>

            <div class="staff-stat-icon">
                <i class="bi bi-hourglass-split"></i>
            </div>

            <div class="staff-stat-label">
                Lịch chờ xác nhận
            </div>

            <div class="staff-stat-value">
                {{ $pendingAppointments }}
            </div>

        </div>


        <div class="staff-stat" data-reveal data-tilt>

            <div class="staff-stat-icon">
                <i class="bi bi-calendar2-day"></i>
            </div>

            <div class="staff-stat-label">
                Lịch hẹn hôm nay
            </div>

            <div class="staff-stat-value">
                {{ $todayAppointments }}
            </div>

        </div>


        <div class="staff-stat purple" data-reveal data-tilt>

            <div class="staff-stat-icon">
                <i class="bi bi-tools"></i>
            </div>

            <div class="staff-stat-label">
                Phiếu đang xử lý
            </div>

            <div class="staff-stat-value">
                {{ $activeServiceOrders }}
            </div>

        </div>


        <div class="staff-stat success" data-reveal data-tilt>

            <div class="staff-stat-icon">
                <i class="bi bi-check2-circle"></i>
            </div>

            <div class="staff-stat-label">
                Hoàn thành trong tháng
            </div>

            <div class="staff-stat-value">
                {{ $completedServiceOrdersThisMonth }}
            </div>

        </div>


        <div class="staff-stat success" data-reveal data-tilt>

            <div class="staff-stat-icon">
                <i class="bi bi-graph-up-arrow"></i>
            </div>

            <div class="staff-stat-label">
                Doanh thu tháng này
            </div>

            <div class="staff-stat-value">

                {{
                    number_format(
                        $monthlyRevenue,
                        0,
                        ',',
                        '.'
                    )
                }} đ

            </div>

        </div>


        <div class="staff-stat warning" data-reveal data-tilt>

            <div class="staff-stat-icon">
                <i class="bi bi-receipt"></i>
            </div>

            <div class="staff-stat-label">
                Hóa đơn chưa thanh toán
            </div>

            <div class="staff-stat-value">
                {{ $unpaidInvoices }}
            </div>

        </div>


        <div class="staff-stat warning" data-reveal data-tilt>

            <div class="staff-stat-icon">
                <i class="bi bi-box-seam"></i>
            </div>

            <div class="staff-stat-label">
                Phụ tùng cần chú ý
            </div>

            <div class="staff-stat-value">
                {{ $lowStockParts }}
            </div>

        </div>

    </section>


    <section class="staff-content-grid">

        <div class="staff-panel" data-reveal="left">

            <div class="staff-panel-body">

                <div class="staff-panel-title">

                    <span class="staff-panel-icon">
                        <i class="bi bi-calendar-event"></i>
                    </span>

                    Lịch hẹn mới nhất

                </div>


                @forelse ($recentAppointments as $appointment)

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


                    <div class="staff-record">

                        <div class="staff-record-top">

                            <div>

                                <div class="staff-record-code">
                                    {{ $appointment->appointment_code }}
                                </div>

                                <div class="staff-record-name">
                                    {{ $appointment->contact_name }}
                                </div>

                                <div class="staff-record-meta">

                                    {{ $appointment->vehicle->brand->name }}
                                    {{ $appointment->vehicle->vehicleModel->name }}
                                    -
                                    {{ $appointment->vehicle->license_plate }}

                                    <br>

                                    {{
                                        $appointment
                                            ->appointment_date
                                            ->format('d/m/Y')
                                    }}

                                    -

                                    {{
                                        substr(
                                            $appointment->appointment_time,
                                            0,
                                            5
                                        )
                                    }}

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


                        <a
                            href="{{ route(
                                'staff.appointments.show',
                                $appointment->id
                            ) }}"
                            class="staff-record-link"
                        >
                            Xem lịch hẹn
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </div>

                @empty

                    <div class="staff-empty">
                        Chưa có lịch hẹn.
                    </div>

                @endforelse

            </div>

        </div>


        <div class="staff-panel" data-reveal="right">

            <div class="staff-panel-body">

                <div class="staff-panel-title">

                    <span class="staff-panel-icon">
                        <i class="bi bi-wallet2"></i>
                    </span>

                    Thanh toán gần đây

                </div>


                @forelse ($recentPaidInvoices as $invoice)

                    <div class="staff-record">

                        <div class="staff-record-code">
                            {{ $invoice->invoice_code }}
                        </div>

                        <div class="staff-record-name">
                            {{ $invoice->customer->full_name }}
                        </div>

                        <div class="invoice-amount">

                            {{
                                number_format(
                                    $invoice->total_amount,
                                    0,
                                    ',',
                                    '.'
                                )
                            }} đ

                        </div>

                        <a
                            href="{{ route(
                                'staff.invoices.show',
                                $invoice->id
                            ) }}"
                            class="staff-record-link"
                        >
                            Xem hóa đơn
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </div>

                @empty

                    <div class="staff-empty">
                        Chưa có hóa đơn đã thanh toán.
                    </div>

                @endforelse

            </div>

        </div>

    </section>


    <section class="quick-actions">

        <div class="quick-grid">

            <a
                href="{{ route('staff.appointments.index') }}"
                class="quick-card"
            >
                <div class="quick-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>

                <div class="quick-title">
                    Quản lý lịch hẹn
                </div>

                <div class="quick-text">
                    Xác nhận và xử lý lịch
                    do khách hàng gửi.
                </div>
            </a>


            <a
                href="{{ route('staff.parts.index') }}"
                class="quick-card"
            >
                <div class="quick-icon">
                    <i class="bi bi-box-seam"></i>
                </div>

                <div class="quick-title">
                    Kho phụ tùng
                </div>

                <div class="quick-text">
                    Theo dõi tồn kho và
                    nhập thêm phụ tùng.
                </div>
            </a>


            <a
                href="{{ route('services.index') }}"
                class="quick-card"
            >
                <div class="quick-icon">
                    <i class="bi bi-tools"></i>
                </div>

                <div class="quick-title">
                    Danh mục dịch vụ
                </div>

                <div class="quick-text">
                    Tra cứu dịch vụ,
                    giá và chu kỳ bảo dưỡng.
                </div>
            </a>


            <a
                href="{{ route('home') }}"
                class="quick-card"
            >
                <div class="quick-icon">
                    <i class="bi bi-globe2"></i>
                </div>

                <div class="quick-title">
                    Website AutoCare
                </div>

                <div class="quick-text">
                    Trở về giao diện
                    website chính.
                </div>
            </a>

        </div>

    </section>

</div>

@endsection