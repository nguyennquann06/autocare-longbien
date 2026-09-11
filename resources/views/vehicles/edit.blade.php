@extends('layouts.app')


@section(
    'title',
    'Chỉnh sửa xe - AutoCare Long Biên'
)


@push('styles')

<style>
    .vehicle-edit-page {
        max-width: 980px;
    }

    .vehicle-edit-hero {
        position: relative;
        overflow: hidden;
        padding: 34px;
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

    .vehicle-edit-hero::before {
        content: "";
        position: absolute;
        width: 370px;
        height: 370px;
        top: -220px;
        right: -100px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, 0.42),
                transparent 70%
            );
    }

    .vehicle-edit-hero-content {
        position: relative;
        z-index: 2;
    }

    .vehicle-edit-code {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 10px;
        color: #bfdbfe;
        font-size: 11px;
        font-weight: 800;
    }

    .vehicle-edit-hero h1 {
        margin: 0;
        color: white;
        font-size:
            clamp(
                2rem,
                4vw,
                3.4rem
            );
        font-weight: 900;
        letter-spacing: -0.055em;
    }

    .vehicle-edit-hero p {
        max-width: 650px;
        margin: 11px 0 0;
        color: #cbd5e1;
        line-height: 1.75;
    }

    .vehicle-edit-card {
        overflow: hidden;
        border:
            1px solid
            rgba(255, 255, 255, 0.88);
        border-radius: 21px;
        background:
            rgba(255, 255, 255, 0.92);
        box-shadow:
            var(--ac-shadow);
        backdrop-filter: blur(16px);
    }

    .vehicle-edit-body {
        padding: 28px;
    }

    .vehicle-edit-section {
        margin-bottom: 28px;
    }

    .vehicle-edit-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
        color: #0f172a;
        font-size: 17px;
        font-weight: 900;
    }

    .vehicle-edit-section-icon {
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

    .vehicle-edit-panel {
        padding: 21px;
        border:
            1px solid #e5ecf4;
        border-radius: 16px;
        background:
            linear-gradient(
                135deg,
                #f8fbff,
                #f7faff
            );
    }

    .vehicle-edit-label {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 7px;
        color: #334155;
        font-size: 12px;
        font-weight: 850;
    }

    .vehicle-edit-label i {
        color: #2563eb;
    }

    .vehicle-edit-control {
        min-height: 48px;
    }

    .vehicle-save-button {
        min-height: 49px;
        padding: 0 22px;
        border: none;
        border-radius: 13px;
        color: white;
        background:
            linear-gradient(
                135deg,
                #1683ff,
                #4f46e5
            );
        box-shadow:
            0 10px 25px
            rgba(37, 99, 235, 0.22);
        font-size: 12px;
        font-weight: 900;
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .vehicle-save-button:hover {
        transform: translateY(-2px);
        box-shadow:
            0 14px 30px
            rgba(37, 99, 235, 0.28);
    }

    .vehicle-edit-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 20px;
        color: #64748b;
        text-decoration: none;
        font-size: 12px;
        font-weight: 750;
    }

    @media (max-width: 575px) {
        .vehicle-edit-hero {
            padding: 25px;
        }

        .vehicle-edit-body {
            padding: 20px;
        }

        .vehicle-edit-panel {
            padding: 17px;
        }
    }
</style>

@endpush


@section('content')

<div class="container vehicle-edit-page">

    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>
                <i class="bi bi-exclamation-triangle-fill me-1"></i>

                Vui lòng kiểm tra lại thông tin:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <section
        class="vehicle-edit-hero"
        data-reveal="zoom"
    >

        <div class="vehicle-edit-hero-content">

            <div class="vehicle-edit-code">

                <i class="bi bi-credit-card-2-front"></i>

                {{ $vehicle->license_plate }}

            </div>


            <h1>
                Chỉnh sửa phương tiện
            </h1>


            <p>

                Cập nhật thông tin xe và ODO
                để hệ thống có dữ liệu chính xác
                phục vụ lịch bảo dưỡng
                và gợi ý chăm sóc tiếp theo.

            </p>

        </div>

    </section>


    <article
        class="vehicle-edit-card"
        data-reveal
    >

        <div class="vehicle-edit-body">

            <form
                method="POST"
                action="{{ route(
                    'vehicles.update',
                    $vehicle->id
                ) }}"
            >

                @csrf
                @method('PUT')


                <section class="vehicle-edit-section">

                    <div class="vehicle-edit-section-title">

                        <span class="vehicle-edit-section-icon">
                            <i class="bi bi-car-front-fill"></i>
                        </span>

                        Thông tin phương tiện

                    </div>


                    <div class="vehicle-edit-panel">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label
                                    for="brand_id"
                                    class="vehicle-edit-label"
                                >

                                    <i class="bi bi-building"></i>

                                    Hãng xe *

                                </label>


                                <select
                                    name="brand_id"
                                    id="brand_id"
                                    class="
                                        form-select
                                        vehicle-edit-control
                                    "
                                    required
                                >

                                    <option value="">
                                        -- Chọn hãng xe --
                                    </option>


                                    @foreach ($brands as $brand)

                                        <option
                                            value="{{ $brand->id }}"
                                            {{
                                                old(
                                                    'brand_id',
                                                    $vehicle->brand_id
                                                ) == $brand->id
                                                    ? 'selected'
                                                    : ''
                                            }}
                                        >
                                            {{ $brand->name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="model_id"
                                    class="vehicle-edit-label"
                                >

                                    <i class="bi bi-car-front"></i>

                                    Dòng xe *

                                </label>


                                <select
                                    name="model_id"
                                    id="model_id"
                                    class="
                                        form-select
                                        vehicle-edit-control
                                    "
                                    required
                                >

                                    <option value="">
                                        Đang tải...
                                    </option>

                                </select>

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="license_plate"
                                    class="vehicle-edit-label"
                                >

                                    <i class="bi bi-credit-card-2-front"></i>

                                    Biển số xe *

                                </label>


                                <input
                                    type="text"
                                    id="license_plate"
                                    name="license_plate"
                                    class="
                                        form-control
                                        vehicle-edit-control
                                    "
                                    value="{{ old(
                                        'license_plate',
                                        $vehicle->license_plate
                                    ) }}"
                                    required
                                >

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="vin"
                                    class="vehicle-edit-label"
                                >

                                    <i class="bi bi-upc-scan"></i>

                                    Số VIN

                                </label>


                                <input
                                    type="text"
                                    id="vin"
                                    name="vin"
                                    class="
                                        form-control
                                        vehicle-edit-control
                                    "
                                    value="{{ old(
                                        'vin',
                                        $vehicle->vin
                                    ) }}"
                                >

                            </div>


                            <div class="col-md-4">

                                <label
                                    for="manufacture_year"
                                    class="vehicle-edit-label"
                                >

                                    <i class="bi bi-calendar3"></i>

                                    Năm sản xuất

                                </label>


                                <input
                                    type="number"
                                    id="manufacture_year"
                                    name="manufacture_year"
                                    class="
                                        form-control
                                        vehicle-edit-control
                                    "
                                    value="{{ old(
                                        'manufacture_year',
                                        $vehicle->manufacture_year
                                    ) }}"
                                    min="1980"
                                    max="{{ date('Y') + 1 }}"
                                >

                            </div>


                            <div class="col-md-4">

                                <label
                                    for="color"
                                    class="vehicle-edit-label"
                                >

                                    <i class="bi bi-palette"></i>

                                    Màu xe

                                </label>


                                <input
                                    type="text"
                                    id="color"
                                    name="color"
                                    class="
                                        form-control
                                        vehicle-edit-control
                                    "
                                    value="{{ old(
                                        'color',
                                        $vehicle->color
                                    ) }}"
                                >

                            </div>


                            <div class="col-md-4">

                                @php
                                    $fuelType = old(
                                        'fuel_type',
                                        $vehicle->fuel_type
                                    );
                                @endphp


                                <label
                                    for="fuel_type"
                                    class="vehicle-edit-label"
                                >

                                    <i class="bi bi-fuel-pump"></i>

                                    Loại nhiên liệu

                                </label>


                                <select
                                    name="fuel_type"
                                    id="fuel_type"
                                    class="
                                        form-select
                                        vehicle-edit-control
                                    "
                                >

                                    <option value="">
                                        -- Chọn loại nhiên liệu --
                                    </option>

                                    <option
                                        value="Xăng"
                                        {{
                                            $fuelType === 'Xăng'
                                                ? 'selected'
                                                : ''
                                        }}
                                    >
                                        Xăng
                                    </option>

                                    <option
                                        value="Dầu"
                                        {{
                                            $fuelType === 'Dầu'
                                                ? 'selected'
                                                : ''
                                        }}
                                    >
                                        Dầu
                                    </option>

                                    <option
                                        value="Điện"
                                        {{
                                            $fuelType === 'Điện'
                                                ? 'selected'
                                                : ''
                                        }}
                                    >
                                        Điện
                                    </option>

                                    <option
                                        value="Hybrid"
                                        {{
                                            $fuelType === 'Hybrid'
                                                ? 'selected'
                                                : ''
                                        }}
                                    >
                                        Hybrid
                                    </option>

                                </select>

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="current_mileage"
                                    class="vehicle-edit-label"
                                >

                                    <i class="bi bi-speedometer2"></i>

                                    Số km hiện tại *

                                </label>


                                <div class="input-group">

                                    <input
                                        type="number"
                                        id="current_mileage"
                                        name="current_mileage"
                                        class="
                                            form-control
                                            vehicle-edit-control
                                        "
                                        value="{{ old(
                                            'current_mileage',
                                            $vehicle->current_mileage
                                        ) }}"
                                        min="0"
                                        placeholder="Ví dụ: 35000"
                                        required
                                    >

                                    <span class="input-group-text">
                                        km
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>


                <section class="vehicle-edit-section">

                    <div class="vehicle-edit-section-title">

                        <span class="vehicle-edit-section-icon">
                            <i class="bi bi-chat-left-text"></i>
                        </span>

                        Ghi chú

                    </div>


                    <div class="vehicle-edit-panel">

                        <textarea
                            id="note"
                            name="note"
                            class="form-control"
                            rows="5"
                            maxlength="2000"
                        >{{ old(
                            'note',
                            $vehicle->note
                        ) }}</textarea>

                    </div>

                </section>


                <button
                    type="submit"
                    class="vehicle-save-button"
                >

                    <i class="bi bi-floppy me-2"></i>

                    Lưu thay đổi

                </button>

            </form>


            <a
                href="{{ route(
                    'vehicles.show',
                    $vehicle->id
                ) }}"
                class="vehicle-edit-back"
            >

                <i class="bi bi-arrow-left"></i>

                Quay lại chi tiết xe

            </a>

        </div>

    </article>

</div>

@endsection


@push('scripts')

<script>
    const brandSelect =
        document.getElementById(
            'brand_id'
        );

    const modelSelect =
        document.getElementById(
            'model_id'
        );


    const currentModelId =
        @json(
            old(
                'model_id',
                $vehicle->model_id
            )
        );


    async function loadModels(
        brandId,
        selectedModelId = null
    ) {
        modelSelect.disabled = true;

        modelSelect.innerHTML = `
            <option value="">
                Đang tải dòng xe...
            </option>
        `;


        if (!brandId) {
            modelSelect.innerHTML = `
                <option value="">
                    -- Chọn hãng xe trước --
                </option>
            `;

            return;
        }


        try {
            const response = await fetch(
                `/vehicle-models/${brandId}`
            );


            if (!response.ok) {
                throw new Error(
                    'Không thể tải dòng xe.'
                );
            }


            const models =
                await response.json();


            modelSelect.innerHTML = `
                <option value="">
                    -- Chọn dòng xe --
                </option>
            `;


            if (models.length === 0) {
                modelSelect.innerHTML = `
                    <option value="">
                        Chưa có dòng xe
                    </option>
                `;

                modelSelect.disabled = true;

                return;
            }


            models.forEach(
                function (model) {
                    const option =
                        document.createElement(
                            'option'
                        );


                    option.value =
                        model.id;


                    option.textContent =
                        model.vehicle_type
                            ? `${model.name} - ${model.vehicle_type}`
                            : model.name;


                    if (
                        String(model.id)
                        ===
                        String(selectedModelId)
                    ) {
                        option.selected = true;
                    }


                    modelSelect.appendChild(
                        option
                    );
                }
            );


            modelSelect.disabled = false;
        }
        catch (error) {
            console.error(error);

            modelSelect.innerHTML = `
                <option value="">
                    Không thể tải dữ liệu
                </option>
            `;

            modelSelect.disabled = true;
        }
    }


    brandSelect.addEventListener(
        'change',
        function () {
            loadModels(
                this.value
            );
        }
    );


    loadModels(
        brandSelect.value,
        currentModelId
    );
</script>

@endpush