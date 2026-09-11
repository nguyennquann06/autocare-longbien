@extends('layouts.app')


@section(
    'title',
    'Chi tiết xe - AutoCare Long Biên'
)


@push('styles')

<style>
    .vehicle-detail-page {
        max-width: 1080px;
    }

    .vehicle-detail-hero {
        position: relative;
        overflow: hidden;
        padding: 35px;
        margin-bottom: 24px;
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

    .vehicle-detail-hero::before {
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

    .vehicle-detail-hero::after {
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

    .vehicle-detail-hero-inner {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 22px;
        flex-wrap: wrap;
    }

    .vehicle-detail-chip {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 10px;
        color: #bfdbfe;
        font-size: 11px;
        font-weight: 800;
    }

    .vehicle-detail-hero h1 {
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

    .vehicle-detail-model {
        margin-top: 10px;
        color: #dbeafe;
        font-size: 15px;
        font-weight: 750;
    }

    .vehicle-detail-plate {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 15px;
        border:
            1px solid
            rgba(255, 255, 255, 0.15);
        border-radius: 13px;
        color: white;
        background:
            rgba(255, 255, 255, 0.08);
        font-size: 15px;
        font-weight: 900;
        letter-spacing: 0.05em;
    }

    .vehicle-detail-card {
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

    .vehicle-detail-body {
        padding: 27px;
    }

    .vehicle-detail-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 19px;
        color: #0f172a;
        font-size: 17px;
        font-weight: 900;
    }

    .vehicle-detail-title-icon {
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

    .vehicle-detail-grid {
        display: grid;
        grid-template-columns:
            repeat(
                5,
                minmax(0, 1fr)
            );
        gap: 12px;
    }

    .vehicle-detail-info {
        min-height: 115px;
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

    .vehicle-detail-info:hover {
        transform: translateY(-2px);
        border-color: #bfdbfe;
    }

    .vehicle-detail-info-icon {
        margin-bottom: 8px;
        color: #2563eb;
        font-size: 17px;
    }

    .vehicle-detail-info-label {
        color: #64748b;
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .vehicle-detail-info-value {
        margin-top: 5px;
        color: #0f172a;
        font-size: 12px;
        font-weight: 850;
        word-break: break-word;
    }

    .vehicle-note {
        margin-top: 24px;
        padding: 17px;
        border:
            1px solid #e4ebf3;
        border-radius: 15px;
        color: #475569;
        background:
            linear-gradient(
                135deg,
                #f8fbff,
                #f7faff
            );
        font-size: 12px;
        line-height: 1.75;
    }

    .vehicle-note-title {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 8px;
        color: #0f172a;
        font-weight: 900;
    }

    .vehicle-detail-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 24px;
    }

    .vehicle-edit-button,
    .vehicle-delete-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 43px;
        padding: 10px 15px;
        border-radius: 11px;
        font-size: 12px;
        font-weight: 850;
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .vehicle-edit-button {
        color: white;
        text-decoration: none;
        border: none;
        background:
            linear-gradient(
                135deg,
                #1683ff,
                #4f46e5
            );
        box-shadow:
            0 8px 22px
            rgba(37, 99, 235, 0.20);
    }

    .vehicle-delete-button {
        border: 1px solid #fecaca;
        color: #b91c1c;
        background: #fff5f5;
    }

    .vehicle-edit-button:hover,
    .vehicle-delete-button:hover {
        transform: translateY(-2px);
    }

    .vehicle-edit-button:hover {
        color: white;
    }

    .vehicle-detail-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 20px;
        color: #64748b;
        text-decoration: none;
        font-size: 12px;
        font-weight: 750;
    }

    @media (max-width: 1199px) {
        .vehicle-detail-grid {
            grid-template-columns:
                repeat(
                    3,
                    minmax(0, 1fr)
                );
        }
    }

    @media (max-width: 767px) {
        .vehicle-detail-grid {
            grid-template-columns:
                repeat(
                    2,
                    minmax(0, 1fr)
                );
        }
    }

    @media (max-width: 575px) {
        .vehicle-detail-grid {
            grid-template-columns: 1fr;
        }

        .vehicle-detail-hero {
            padding: 25px;
        }

        .vehicle-detail-body {
            padding: 20px;
        }
    }
</style>

@endpush


@section('content')

<div class="container vehicle-detail-page">

    @if (session('success'))

        <div
            class="alert alert-success"
            data-reveal
        >

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

        </div>

    @endif


    <section
        class="vehicle-detail-hero"
        data-reveal="zoom"
    >

        <div class="vehicle-detail-hero-inner">

            <div>

                <div class="vehicle-detail-chip">

                    <i class="bi bi-car-front-fill"></i>

                    Vehicle Profile

                </div>


                <h1>
                    Chi tiết phương tiện
                </h1>


                <div class="vehicle-detail-model">

                    {{ $vehicle->brand->name }}

                    {{ $vehicle->vehicleModel->name }}

                </div>

            </div>


            <div class="vehicle-detail-plate">

                <i class="bi bi-credit-card-2-front"></i>

                {{ $vehicle->license_plate }}

            </div>

        </div>

    </section>


    <article
        class="vehicle-detail-card"
        data-reveal
    >

        <div class="vehicle-detail-body">

            <div class="vehicle-detail-title">

                <span class="vehicle-detail-title-icon">

                    <i class="bi bi-info-circle"></i>

                </span>

                Thông tin phương tiện

            </div>


            <div class="vehicle-detail-grid">

                <div class="vehicle-detail-info">

                    <div class="vehicle-detail-info-icon">
                        <i class="bi bi-credit-card-2-front"></i>
                    </div>

                    <div class="vehicle-detail-info-label">
                        Biển số xe
                    </div>

                    <div class="vehicle-detail-info-value">
                        {{ $vehicle->license_plate }}
                    </div>

                </div>


                <div class="vehicle-detail-info">

                    <div class="vehicle-detail-info-icon">
                        <i class="bi bi-building"></i>
                    </div>

                    <div class="vehicle-detail-info-label">
                        Hãng xe
                    </div>

                    <div class="vehicle-detail-info-value">
                        {{ $vehicle->brand->name }}
                    </div>

                </div>


                <div class="vehicle-detail-info">

                    <div class="vehicle-detail-info-icon">
                        <i class="bi bi-car-front"></i>
                    </div>

                    <div class="vehicle-detail-info-label">
                        Dòng xe
                    </div>

                    <div class="vehicle-detail-info-value">
                        {{ $vehicle->vehicleModel->name }}
                    </div>

                </div>


                <div class="vehicle-detail-info">

                    <div class="vehicle-detail-info-icon">
                        <i class="bi bi-grid"></i>
                    </div>

                    <div class="vehicle-detail-info-label">
                        Loại xe
                    </div>

                    <div class="vehicle-detail-info-value">

                        {{
                            $vehicle
                                ->vehicleModel
                                ->vehicle_type
                            ?? 'Chưa cập nhật'
                        }}

                    </div>

                </div>


                <div class="vehicle-detail-info">

                    <div class="vehicle-detail-info-icon">
                        <i class="bi bi-calendar3"></i>
                    </div>

                    <div class="vehicle-detail-info-label">
                        Năm sản xuất
                    </div>

                    <div class="vehicle-detail-info-value">

                        {{
                            $vehicle->manufacture_year
                            ?? 'Chưa cập nhật'
                        }}

                    </div>

                </div>


                <div class="vehicle-detail-info">

                    <div class="vehicle-detail-info-icon">
                        <i class="bi bi-palette"></i>
                    </div>

                    <div class="vehicle-detail-info-label">
                        Màu xe
                    </div>

                    <div class="vehicle-detail-info-value">

                        {{
                            $vehicle->color
                            ?? 'Chưa cập nhật'
                        }}

                    </div>

                </div>


                <div class="vehicle-detail-info">

                    <div class="vehicle-detail-info-icon">
                        <i class="bi bi-fuel-pump"></i>
                    </div>

                    <div class="vehicle-detail-info-label">
                        Nhiên liệu
                    </div>

                    <div class="vehicle-detail-info-value">

                        {{
                            $vehicle->fuel_type
                            ?? 'Chưa cập nhật'
                        }}

                    </div>

                </div>


                <div class="vehicle-detail-info">

                    <div class="vehicle-detail-info-icon">
                        <i class="bi bi-speedometer2"></i>
                    </div>

                    <div class="vehicle-detail-info-label">
                        ODO hiện tại
                    </div>

                    <div class="vehicle-detail-info-value">

                        {{
                            number_format(
                                $vehicle->current_mileage
                            )
                        }} km

                    </div>

                </div>


                <div class="vehicle-detail-info">

                    <div class="vehicle-detail-info-icon">
                        <i class="bi bi-upc-scan"></i>
                    </div>

                    <div class="vehicle-detail-info-label">
                        Số VIN
                    </div>

                    <div class="vehicle-detail-info-value">

                        {{
                            $vehicle->vin
                            ?? 'Chưa cập nhật'
                        }}

                    </div>

                </div>


                <div class="vehicle-detail-info">

                    <div class="vehicle-detail-info-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <div class="vehicle-detail-info-label">
                        Ngày thêm xe
                    </div>

                    <div class="vehicle-detail-info-value">

                        {{
                            $vehicle
                                ->created_at
                                ->format('d/m/Y H:i')
                        }}

                    </div>

                </div>

            </div>


            <div class="vehicle-note">

                <div class="vehicle-note-title">

                    <i class="bi bi-chat-left-text"></i>

                    Ghi chú

                </div>


                {{
                    $vehicle->note
                    ?? 'Không có ghi chú'
                }}

            </div>


            <div class="vehicle-detail-actions">

                <a
                    href="{{ route(
                        'vehicles.edit',
                        $vehicle->id
                    ) }}"
                    class="vehicle-edit-button"
                >

                    <i class="bi bi-pencil-square"></i>

                    Chỉnh sửa xe

                </a>


                <button
                    type="button"
                    class="vehicle-delete-button"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteVehicleModal"
                >

                    <i class="bi bi-trash3"></i>

                    Xóa phương tiện

                </button>

            </div>


            <a
                href="{{ route('vehicles.index') }}"
                class="vehicle-detail-back"
            >

                <i class="bi bi-arrow-left"></i>

                Quay lại Xe của tôi

            </a>

        </div>

    </article>

</div>


<div
    class="modal fade"
    id="deleteVehicleModal"
    tabindex="-1"
    aria-labelledby="deleteVehicleModalLabel"
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
                    id="deleteVehicleModalLabel"
                    class="mb-3"
                >
                    Xóa phương tiện?
                </h3>


                <p class="text-secondary">

                    Bạn có chắc chắn muốn xóa

                    <strong>
                        {{ $vehicle->brand->name }}
                        {{ $vehicle->vehicleModel->name }}
                        -
                        {{ $vehicle->license_plate }}
                    </strong>

                    khỏi hệ thống không?

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

                    Nếu phương tiện đã có dữ liệu
                    lịch hẹn hoặc bảo dưỡng,
                    hệ thống có thể không cho phép xóa.

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

@endsection