@extends('layouts.app')


@section(
    'title',
    'Xe của tôi - AutoCare Long Biên'
)


@push('styles')

<style>
    .vehicles-page {
        max-width: 1200px;
    }

    .vehicles-hero {
        position: relative;
        overflow: hidden;
        padding: 35px;
        margin-bottom: 27px;
        border-radius: 28px;
        color: white;
        background:
            linear-gradient(
                120deg,
                #06101e 0%,
                #0b2f6b 52%,
                #1467df 100%
            );
        box-shadow:
            0 28px 75px
            rgba(20, 103, 223, 0.23);
    }

    .vehicles-hero::before {
        content: "";
        position: absolute;
        width: 380px;
        height: 380px;
        top: -225px;
        right: -110px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, 0.42),
                transparent 70%
            );
    }

    .vehicles-hero::after {
        content: "";
        position: absolute;
        width: 270px;
        height: 270px;
        left: 38%;
        bottom: -220px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(124, 58, 237, 0.34),
                transparent 70%
            );
    }

    .vehicles-hero-inner {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 24px;
        flex-wrap: wrap;
    }

    .vehicles-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        margin-bottom: 16px;
        border:
            1px solid
            rgba(255, 255, 255, 0.16);
        border-radius: 999px;
        color: #dbeafe;
        background:
            rgba(255, 255, 255, 0.08);
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.07em;
    }

    .vehicles-chip-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #67e8f9;
        box-shadow:
            0 0 12px #67e8f9;
    }

    .vehicles-hero h1 {
        margin: 0;
        color: white;
        font-size:
            clamp(
                2rem,
                4vw,
                3.5rem
            );
        font-weight: 900;
        letter-spacing: -0.055em;
    }

    .vehicles-hero p {
        max-width: 650px;
        margin: 12px 0 0;
        color: #cbd5e1;
        line-height: 1.8;
    }

    .vehicle-add-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 17px;
        border-radius: 13px;
        color: #07111f;
        text-decoration: none;
        background:
            linear-gradient(
                135deg,
                #67e8f9,
                #bfdbfe
            );
        box-shadow:
            0 12px 30px
            rgba(103, 232, 249, 0.20);
        font-size: 12px;
        font-weight: 900;
        transition:
            transform 0.22s ease,
            box-shadow 0.22s ease;
    }

    .vehicle-add-button:hover {
        color: #07111f;
        transform: translateY(-3px);
        box-shadow:
            0 18px 38px
            rgba(103, 232, 249, 0.28);
    }

    .vehicles-summary {
        display: grid;
        grid-template-columns:
            repeat(
                3,
                minmax(0, 1fr)
            );
        gap: 15px;
        margin-bottom: 24px;
    }

    .vehicles-summary-card {
        padding: 19px;
        border:
            1px solid
            rgba(255, 255, 255, 0.88);
        border-radius: 18px;
        background:
            rgba(255, 255, 255, 0.92);
        box-shadow:
            var(--ac-shadow);
        backdrop-filter: blur(16px);
    }

    .vehicles-summary-icon {
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

    .vehicles-summary-label {
        color: #64748b;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .vehicles-summary-value {
        margin-top: 5px;
        color: #0f172a;
        font-size: 23px;
        font-weight: 900;
    }

    .vehicle-card {
        position: relative;
        overflow: hidden;
        margin-bottom: 18px;
        padding: 24px;
        border:
            1px solid
            rgba(255, 255, 255, 0.88);
        border-radius: 20px;
        background:
            rgba(255, 255, 255, 0.92);
        box-shadow:
            var(--ac-shadow);
        backdrop-filter:
            blur(16px);
        transition:
            transform 0.23s ease,
            box-shadow 0.23s ease,
            border-color 0.23s ease;
    }

    .vehicle-card:hover {
        transform: translateY(-5px);
        border-color:
            rgba(147, 197, 253, 0.65);
        box-shadow:
            var(--ac-shadow-lg);
    }

    .vehicle-card::after {
        content: "";
        position: absolute;
        width: 160px;
        height: 160px;
        top: -105px;
        right: -105px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(59, 130, 246, 0.13),
                transparent 70%
            );
    }

    .vehicle-card-top {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        flex-wrap: wrap;
    }

    .vehicle-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 13px;
        border-radius: 15px;
        color: #2563eb;
        background:
            linear-gradient(
                135deg,
                #dbeafe,
                #ecfeff
            );
        font-size: 22px;
    }

    .vehicle-name {
        color: #0f172a;
        font-size: 21px;
        font-weight: 900;
        letter-spacing: -0.03em;
    }

    .vehicle-plate {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 6px;
        color: #64748b;
        font-size: 12px;
    }

    .vehicle-info-grid {
        position: relative;
        z-index: 2;
        display: grid;
        grid-template-columns:
            repeat(
                6,
                minmax(0, 1fr)
            );
        gap: 12px;
        margin-top: 20px;
    }

    .vehicle-info-box {
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

    .vehicle-info-icon {
        margin-bottom: 7px;
        color: #2563eb;
        font-size: 17px;
    }

    .vehicle-info-label {
        color: #64748b;
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .vehicle-info-value {
        margin-top: 5px;
        color: #0f172a;
        font-size: 12px;
        font-weight: 850;
    }

    .vehicle-actions {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 9px;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .vehicle-action-primary,
    .vehicle-action-secondary,
    .vehicle-action-danger {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        min-height: 40px;
        padding: 9px 13px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 11px;
        font-weight: 850;
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .vehicle-action-primary {
        color: white;
        background:
            linear-gradient(
                135deg,
                #1683ff,
                #4f46e5
            );
        box-shadow:
            0 8px 20px
            rgba(37, 99, 235, 0.18);
    }

    .vehicle-action-secondary {
        color: #334155;
        border: 1px solid #d9e2ed;
        background: white;
    }

    .vehicle-action-danger {
        border: 1px solid #fecaca;
        color: #b91c1c;
        background: #fff5f5;
        cursor: pointer;
    }

    .vehicle-action-primary:hover,
    .vehicle-action-secondary:hover,
    .vehicle-action-danger:hover {
        transform: translateY(-2px);
    }

    .vehicles-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 8px;
        color: #64748b;
        text-decoration: none;
        font-size: 12px;
        font-weight: 750;
    }

    @media (max-width: 1199px) {
        .vehicle-info-grid {
            grid-template-columns:
                repeat(
                    3,
                    minmax(0, 1fr)
                );
        }
    }

    @media (max-width: 767px) {
        .vehicles-summary {
            grid-template-columns: 1fr;
        }

        .vehicle-info-grid {
            grid-template-columns:
                repeat(
                    2,
                    minmax(0, 1fr)
                );
        }
    }

    @media (max-width: 575px) {
        .vehicle-info-grid {
            grid-template-columns: 1fr;
        }

        .vehicles-hero {
            padding: 25px;
        }

        .vehicle-card {
            padding: 19px;
        }
    }
</style>

@endpush


@section('content')

<div class="container vehicles-page">

    @php
        $totalVehicles = $vehicles->count();

        $totalMileage = $vehicles->sum(
            'current_mileage'
        );

        $averageMileage =
            $totalVehicles > 0
                ? round(
                    $totalMileage
                    /
                    $totalVehicles
                )
                : 0;
    @endphp


    <section
        class="vehicles-hero"
        data-reveal="zoom"
    >

        <div class="vehicles-hero-inner">

            <div>

                <div class="vehicles-chip">

                    <span class="vehicles-chip-dot"></span>

                    My Garage

                </div>


                <h1>
                    Xe của tôi
                </h1>


                <p>

                    Quản lý toàn bộ phương tiện
                    đã đăng ký, cập nhật ODO
                    và chuẩn bị dữ liệu cho
                    lịch bảo dưỡng của bạn.

                </p>

            </div>


            <a
                href="{{ route('vehicles.create') }}"
                class="vehicle-add-button"
            >

                <i class="bi bi-plus-lg"></i>

                Thêm phương tiện

            </a>

        </div>

    </section>


    <section class="vehicles-summary">

        <div
            class="vehicles-summary-card"
            data-reveal
            data-tilt
        >

            <div class="vehicles-summary-icon">
                <i class="bi bi-car-front-fill"></i>
            </div>

            <div class="vehicles-summary-label">
                Tổng phương tiện
            </div>

            <div class="vehicles-summary-value">
                {{ $totalVehicles }}
            </div>

        </div>


        <div
            class="vehicles-summary-card"
            data-reveal
            data-tilt
        >

            <div
                class="vehicles-summary-icon"
                style="
                    color: #7c3aed;
                    background: #f5f3ff;
                "
            >
                <i class="bi bi-speedometer2"></i>
            </div>

            <div class="vehicles-summary-label">
                Tổng ODO
            </div>

            <div class="vehicles-summary-value">
                {{ number_format($totalMileage) }} km
            </div>

        </div>


        <div
            class="vehicles-summary-card"
            data-reveal
            data-tilt
        >

            <div
                class="vehicles-summary-icon"
                style="
                    color: #059669;
                    background: #ecfdf5;
                "
            >
                <i class="bi bi-bar-chart"></i>
            </div>

            <div class="vehicles-summary-label">
                ODO trung bình
            </div>

            <div class="vehicles-summary-value">
                {{ number_format($averageMileage) }} km
            </div>

        </div>

    </section>


    @if ($vehicles->isEmpty())

        <div
            class="empty-state"
            data-reveal="zoom"
        >

            <div class="empty-state-icon">

                <i class="bi bi-car-front"></i>

            </div>


            <h3>
                Bạn chưa có phương tiện
            </h3>


            <p>
                Hãy thêm xe để bắt đầu
                sử dụng chức năng đặt lịch
                và theo dõi bảo dưỡng.
            </p>


            <a
                href="{{ route('vehicles.create') }}"
                class="btn btn-primary px-4"
            >

                <i class="bi bi-plus-lg me-2"></i>

                Thêm phương tiện

            </a>

        </div>

    @else

        @foreach ($vehicles as $vehicle)

            <article
                class="vehicle-card"
                data-reveal
            >

                <div class="vehicle-card-top">

                    <div>

                        <div class="vehicle-icon">

                            <i class="bi bi-car-front-fill"></i>

                        </div>


                        <div class="vehicle-name">

                            {{ $vehicle->brand->name }}

                            {{ $vehicle->vehicleModel->name }}

                        </div>


                        <div class="vehicle-plate">

                            <i class="bi bi-credit-card-2-front"></i>

                            {{ $vehicle->license_plate }}

                        </div>

                    </div>

                </div>


                <div class="vehicle-info-grid">

                    <div class="vehicle-info-box">

                        <div class="vehicle-info-icon">
                            <i class="bi bi-car-front"></i>
                        </div>

                        <div class="vehicle-info-label">
                            Loại xe
                        </div>

                        <div class="vehicle-info-value">

                            {{
                                $vehicle
                                    ->vehicleModel
                                    ->vehicle_type
                                ?? 'Chưa cập nhật'
                            }}

                        </div>

                    </div>


                    <div class="vehicle-info-box">

                        <div class="vehicle-info-icon">
                            <i class="bi bi-calendar3"></i>
                        </div>

                        <div class="vehicle-info-label">
                            Năm sản xuất
                        </div>

                        <div class="vehicle-info-value">

                            {{
                                $vehicle->manufacture_year
                                ?? 'Chưa cập nhật'
                            }}

                        </div>

                    </div>


                    <div class="vehicle-info-box">

                        <div class="vehicle-info-icon">
                            <i class="bi bi-palette"></i>
                        </div>

                        <div class="vehicle-info-label">
                            Màu xe
                        </div>

                        <div class="vehicle-info-value">

                            {{
                                $vehicle->color
                                ?? 'Chưa cập nhật'
                            }}

                        </div>

                    </div>


                    <div class="vehicle-info-box">

                        <div class="vehicle-info-icon">
                            <i class="bi bi-fuel-pump"></i>
                        </div>

                        <div class="vehicle-info-label">
                            Nhiên liệu
                        </div>

                        <div class="vehicle-info-value">

                            {{
                                $vehicle->fuel_type
                                ?? 'Chưa cập nhật'
                            }}

                        </div>

                    </div>


                    <div class="vehicle-info-box">

                        <div class="vehicle-info-icon">
                            <i class="bi bi-speedometer2"></i>
                        </div>

                        <div class="vehicle-info-label">
                            ODO hiện tại
                        </div>

                        <div class="vehicle-info-value">

                            {{
                                number_format(
                                    $vehicle->current_mileage
                                )
                            }} km

                        </div>

                    </div>


                    <div class="vehicle-info-box">

                        <div class="vehicle-info-icon">
                            <i class="bi bi-upc-scan"></i>
                        </div>

                        <div class="vehicle-info-label">
                            VIN
                        </div>

                        <div class="vehicle-info-value">

                            {{
                                $vehicle->vin
                                ?? 'Chưa cập nhật'
                            }}

                        </div>

                    </div>

                </div>


                <div class="vehicle-actions">

                    <a
                        href="{{ route(
                            'vehicles.show',
                            $vehicle->id
                        ) }}"
                        class="vehicle-action-primary"
                    >

                        <i class="bi bi-eye"></i>

                        Xem chi tiết

                    </a>


                    <a
                        href="{{ route(
                            'vehicles.edit',
                            $vehicle->id
                        ) }}"
                        class="vehicle-action-secondary"
                    >

                        <i class="bi bi-pencil-square"></i>

                        Chỉnh sửa

                    </a>


                    <button
                        type="button"
                        class="vehicle-action-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteVehicleModal{{ $vehicle->id }}"
                    >

                        <i class="bi bi-trash3"></i>

                        Xóa

                    </button>

                </div>

            </article>


            <div
                class="modal fade"
                id="deleteVehicleModal{{ $vehicle->id }}"
                tabindex="-1"
                aria-labelledby="deleteVehicleModalLabel{{ $vehicle->id }}"
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

                        <div
                            class="
                                modal-body
                                p-4
                                p-md-5
                                text-center
                            "
                        >

                            <div
                                class="
                                    fs-1
                                    text-danger
                                    mb-3
                                "
                            >
                                <i class="bi bi-trash3-fill"></i>
                            </div>


                            <h3
                                id="deleteVehicleModalLabel{{ $vehicle->id }}"
                                class="mb-3"
                            >
                                Xóa phương tiện?
                            </h3>


                            <p class="text-secondary">

                                Bạn có chắc chắn muốn xóa xe

                                <strong>
                                    {{ $vehicle->license_plate }}
                                </strong>

                                khỏi tài khoản không?

                            </p>


                            <div
                                class="
                                    alert
                                    alert-warning
                                    text-start
                                    small
                                "
                            >

                                <i class="bi bi-exclamation-triangle me-1"></i>

                                Chỉ nên xóa khi phương tiện
                                không còn được sử dụng trong hệ thống.

                            </div>


                            <div
                                class="
                                    d-grid
                                    d-sm-flex
                                    justify-content-center
                                    gap-2
                                    mt-4
                                "
                            >

                                <button
                                    type="button"
                                    class="btn btn-light px-4"
                                    data-bs-dismiss="modal"
                                >
                                    Giữ phương tiện
                                </button>


                                <form
                                    method="POST"
                                    action="{{ route(
                                        'vehicles.destroy',
                                        $vehicle->id
                                    ) }}"
                                >

                                    @csrf
                                    @method('DELETE')


                                    <button
                                        type="submit"
                                        class="btn btn-danger px-4"
                                    >

                                        <i class="bi bi-trash3 me-1"></i>

                                        Xác nhận xóa

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        @endforeach

    @endif


    <a
        href="{{ route('customer.dashboard') }}"
        class="vehicles-back"
    >

        <i class="bi bi-arrow-left"></i>

        Quay lại Tổng quan

    </a>

</div>

@endsection