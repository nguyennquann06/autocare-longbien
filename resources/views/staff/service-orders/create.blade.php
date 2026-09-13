@extends('layouts.app')


@section(
    'title',
    'Tạo phiếu bảo dưỡng - AutoCare Long Biên'
)


@push('styles')

<style>
    .service-order-create-page {
        max-width: 1050px;
    }

    .staff-form-hero {
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

    .staff-form-hero::before {
        content: "";
        position: absolute;
        width: 340px;
        height: 340px;
        top: -200px;
        right: -100px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, .4),
                transparent 70%
            );
    }

    .staff-form-hero-content {
        position: relative;
        z-index: 2;
    }

    .staff-form-code {
        color: #bfdbfe;
        font-size: 11px;
        font-weight: 800;
    }

    .staff-form-hero h1 {
        margin: 7px 0 0;
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

    .staff-form-card {
        border:
            1px solid
            rgba(255, 255, 255, .88);
        border-radius: 21px;
        background:
            rgba(255, 255, 255, .92);
        box-shadow:
            var(--ac-shadow);
        backdrop-filter:
            blur(16px);
    }

    .staff-form-body {
        padding: 27px;
    }

    .info-grid {
        display: grid;
        grid-template-columns:
            repeat(
                4,
                minmax(0, 1fr)
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

    .info-icon {
        margin-bottom: 7px;
        color: #2563eb;
        font-size: 17px;
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

    .staff-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 25px 0 16px;
        color: #0f172a;
        font-size: 17px;
        font-weight: 900;
    }

    .service-line {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        padding: 13px 0;
        border-bottom:
            1px solid
            #edf1f6;
    }

    .service-price {
        color: #1d4ed8;
        font-weight: 900;
        white-space: nowrap;
    }

    .form-panel {
        margin-top: 25px;
        padding: 22px;
        border:
            1px solid
            #e5ecf4;
        border-radius: 17px;
        background: #f8fbff;
    }

    .staff-field-label {
        margin-bottom: 8px;
        color: #334155;
        font-size: 13px;
        font-weight: 850;
    }

    .staff-form-control {
        min-height: 48px;
    }

    .staff-form-control.is-invalid {
        border-color: #f87171;
        background-color: #fffafa;
        box-shadow:
            0 0 0 3px
            rgba(239, 68, 68, .07);
    }

    textarea.staff-form-control {
        min-height: auto;
    }

    .staff-field-hint {
        margin-top: 6px;
        color: #718096;
        font-size: 10px;
        line-height: 1.55;
    }

    .technician-empty-notice {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 18px;
        padding: 14px;
        border:
            1px solid
            #fde68a;
        border-radius: 13px;
        color: #854d0e;
        background:
            linear-gradient(
                135deg,
                #fffbeb,
                #fefce8
            );
        font-size: 12px;
        line-height: 1.6;
    }

    .technician-empty-notice i {
        margin-top: 1px;
        color: #d97706;
        font-size: 17px;
    }

    .submit-button {
        min-height: 48px;
        padding: 0 20px;
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
        box-shadow:
            0 10px 25px
            rgba(37, 99, 235, .22);
        transition:
            transform .2s ease,
            box-shadow .2s ease,
            opacity .2s ease;
    }

    .submit-button:hover:not(:disabled) {
        transform:
            translateY(-2px);
        box-shadow:
            0 14px 30px
            rgba(37, 99, 235, .28);
    }

    .submit-button:disabled {
        cursor: not-allowed;
        opacity: .55;
    }

    .staff-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 20px;
        color: #64748b;
        text-decoration: none;
        font-size: 12px;
        font-weight: 750;
    }

    @media (max-width: 900px) {
        .info-grid {
            grid-template-columns:
                repeat(
                    2,
                    1fr
                );
        }
    }

    @media (max-width: 575px) {
        .info-grid {
            grid-template-columns:
                1fr;
        }

        .staff-form-body {
            padding: 20px;
        }

        .form-panel {
            padding: 18px;
        }

        .submit-button {
            width: 100%;
        }
    }
</style>

@endpush


@section('content')

<div class="container service-order-create-page">

    <section
        class="staff-form-hero"
        data-reveal="zoom"
    >

        <div class="staff-form-hero-content">

            <div class="staff-form-code">

                <i class="bi bi-calendar-check me-1"></i>

                {{ $appointment->appointment_code }}

            </div>


            <h1>
                Tạo phiếu bảo dưỡng
            </h1>

        </div>

    </section>


    <article
        class="staff-form-card"
        data-reveal
    >

        <div class="staff-form-body">

            <div class="info-grid">

                <div class="info-card">

                    <div class="info-icon">
                        <i class="bi bi-person"></i>
                    </div>

                    <div class="info-label">
                        Khách hàng
                    </div>

                    <div class="info-value">
                        {{ $appointment->contact_name }}
                    </div>

                </div>


                <div class="info-card">

                    <div class="info-icon">
                        <i class="bi bi-car-front-fill"></i>
                    </div>

                    <div class="info-label">
                        Phương tiện
                    </div>

                    <div class="info-value">

                        {{ $appointment->vehicle->brand->name }}

                        {{ $appointment->vehicle->vehicleModel->name }}

                    </div>

                </div>


                <div class="info-card">

                    <div class="info-icon">
                        <i class="bi bi-credit-card-2-front"></i>
                    </div>

                    <div class="info-label">
                        Biển số
                    </div>

                    <div class="info-value">
                        {{ $appointment->vehicle->license_plate }}
                    </div>

                </div>


                <div class="info-card">

                    <div class="info-icon">
                        <i class="bi bi-speedometer2"></i>
                    </div>

                    <div class="info-label">
                        ODO hiện tại
                    </div>

                    <div class="info-value">

                        {{
                            number_format(
                                $appointment
                                    ->vehicle
                                    ->current_mileage,
                                0,
                                ',',
                                '.'
                            )
                        }}
                        km

                    </div>

                </div>

            </div>


            <div class="staff-section-title">

                <i class="bi bi-tools text-primary"></i>

                Dịch vụ khách đã đặt

            </div>


            @foreach (
                $appointment->services
                as $service
            )

                <div class="service-line">

                    <span class="fw-bold">

                        {{ $service->name }}

                    </span>


                    <span class="service-price">

                        {{
                            number_format(
                                $service
                                    ->pivot
                                    ->price,
                                0,
                                ',',
                                '.'
                            )
                        }}
                        đ

                    </span>

                </div>

            @endforeach


            <form
                id="createServiceOrderForm"
                method="POST"
                action="{{ route(
                    'staff.service-orders.store',
                    $appointment->id
                ) }}"
                novalidate
            >

                @csrf


                <div class="form-panel">

                    @if ($technicians->isEmpty())

                        <div class="technician-empty-notice">

                            <i class="bi bi-exclamation-triangle-fill"></i>

                            <div>

                                <strong>
                                    Chưa có kỹ thuật viên khả dụng.
                                </strong>

                                <div>
                                    Hệ thống cần có ít nhất một
                                    tài khoản mang vai trò
                                    Kỹ thuật viên trước khi tạo
                                    phiếu bảo dưỡng.
                                </div>

                            </div>

                        </div>

                    @endif


                    <div class="row g-3">

                        <div class="col-md-6">

                            <label
                                for="technician_id"
                                class="staff-field-label"
                            >

                                Kỹ thuật viên phụ trách *

                            </label>


                            <select
                                id="technician_id"
                                name="technician_id"
                                class="
                                    form-select
                                    staff-form-control
                                    @error('technician_id')
                                        is-invalid
                                    @enderror
                                "
                                aria-invalid="{{
                                    $errors->has(
                                        'technician_id'
                                    )
                                        ? 'true'
                                        : 'false'
                                }}"
                            >

                                <option value="">
                                    -- Chọn kỹ thuật viên --
                                </option>


                                @foreach (
                                    $technicians
                                    as $technician
                                )

                                    <option
                                        value="{{ $technician->id }}"
                                        {{
                                            old(
                                                'technician_id'
                                            )
                                            == $technician->id
                                                ? 'selected'
                                                : ''
                                        }}
                                    >

                                        {{ $technician->name }}

                                        -

                                        {{ $technician->email }}

                                    </option>

                                @endforeach

                            </select>


                            <div class="staff-field-hint">

                                Chỉ tài khoản có vai trò
                                Kỹ thuật viên mới được phân công.

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label
                                for="received_mileage"
                                class="staff-field-label"
                            >

                                ODO khi tiếp nhận xe *

                            </label>


                            <input
                                type="number"
                                id="received_mileage"
                                name="received_mileage"
                                class="
                                    form-control
                                    staff-form-control
                                    @error('received_mileage')
                                        is-invalid
                                    @enderror
                                "
                                min="{{
                                    $appointment
                                        ->vehicle
                                        ->current_mileage
                                }}"
                                max="5000000"
                                step="1"
                                inputmode="numeric"
                                value="{{ old(
                                    'received_mileage',
                                    $appointment
                                        ->vehicle
                                        ->current_mileage
                                ) }}"
                                aria-invalid="{{
                                    $errors->has(
                                        'received_mileage'
                                    )
                                        ? 'true'
                                        : 'false'
                                }}"
                            >


                            <div class="staff-field-hint">

                                ODO không được thấp hơn
                                {{
                                    number_format(
                                        $appointment
                                            ->vehicle
                                            ->current_mileage,
                                        0,
                                        ',',
                                        '.'
                                    )
                                }}
                                km và phải là số nguyên.

                            </div>

                        </div>


                        <div class="col-12">

                            <label
                                for="vehicle_condition"
                                class="staff-field-label"
                            >

                                Tình trạng xe khi tiếp nhận

                            </label>


                            <textarea
                                id="vehicle_condition"
                                name="vehicle_condition"
                                class="
                                    form-control
                                    staff-form-control
                                    @error('vehicle_condition')
                                        is-invalid
                                    @enderror
                                "
                                rows="4"
                                maxlength="2000"
                                placeholder="Ví dụ: Ngoại thất bình thường, khách phản ánh tiếng kêu khi phanh..."
                                aria-invalid="{{
                                    $errors->has(
                                        'vehicle_condition'
                                    )
                                        ? 'true'
                                        : 'false'
                                }}"
                            >{{ old('vehicle_condition') }}</textarea>


                            <div class="staff-field-hint">
                                Tối đa 2000 ký tự.
                            </div>

                        </div>


                        <div class="col-12">

                            <label
                                for="diagnosis"
                                class="staff-field-label"
                            >

                                Chẩn đoán ban đầu

                            </label>


                            <textarea
                                id="diagnosis"
                                name="diagnosis"
                                class="
                                    form-control
                                    staff-form-control
                                    @error('diagnosis')
                                        is-invalid
                                    @enderror
                                "
                                rows="4"
                                maxlength="2000"
                                placeholder="Kết quả kiểm tra ban đầu..."
                                aria-invalid="{{
                                    $errors->has(
                                        'diagnosis'
                                    )
                                        ? 'true'
                                        : 'false'
                                }}"
                            >{{ old('diagnosis') }}</textarea>


                            <div class="staff-field-hint">
                                Tối đa 2000 ký tự.
                            </div>

                        </div>


                        <div class="col-12">

                            <label
                                for="staff_note"
                                class="staff-field-label"
                            >

                                Ghi chú nhân viên

                            </label>


                            <textarea
                                id="staff_note"
                                name="staff_note"
                                class="
                                    form-control
                                    staff-form-control
                                    @error('staff_note')
                                        is-invalid
                                    @enderror
                                "
                                rows="3"
                                maxlength="1000"
                                placeholder="Ghi chú nội bộ nếu cần..."
                                aria-invalid="{{
                                    $errors->has(
                                        'staff_note'
                                    )
                                        ? 'true'
                                        : 'false'
                                }}"
                            >{{ old('staff_note') }}</textarea>


                            <div class="staff-field-hint">
                                Tối đa 1000 ký tự.
                            </div>

                        </div>

                    </div>


                    <button
                        type="button"
                        class="submit-button mt-4"
                        data-bs-toggle="modal"
                        data-bs-target="#createServiceOrderModal"
                        @disabled(
                            $technicians->isEmpty()
                        )
                    >

                        <i class="bi bi-plus-circle me-2"></i>

                        Tạo phiếu bảo dưỡng

                    </button>

                </div>

            </form>


            <a
                href="{{ route(
                    'staff.appointments.show',
                    $appointment->id
                ) }}"
                class="staff-back"
            >

                <i class="bi bi-arrow-left"></i>

                Quay lại lịch hẹn

            </a>

        </div>

    </article>

</div>


<div
    class="modal fade"
    id="createServiceOrderModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 rounded-4 shadow-lg">

            <div class="modal-body p-4 p-md-5 text-center">

                <div class="fs-1 text-primary mb-3">

                    <i class="bi bi-tools"></i>

                </div>


                <h3>
                    Tạo phiếu bảo dưỡng?
                </h3>


                <p class="text-secondary">

                    Xác nhận tiếp nhận xe và
                    tạo phiếu bảo dưỡng
                    cho lịch

                    <strong>
                        {{ $appointment->appointment_code }}
                    </strong>.

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
                        id="confirmCreateServiceOrderButton"
                        onclick="submitServiceOrderForm(this)"
                    >

                        Xác nhận tạo

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>
    function submitServiceOrderForm(
        button
    ) {
        const form =
            document.getElementById(
                'createServiceOrderForm'
            );


        if (!form) {
            return;
        }


        button.disabled =
            true;


        button.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>'
            + 'Đang tạo phiếu...';


        form.submit();
    }
</script>

@endpush