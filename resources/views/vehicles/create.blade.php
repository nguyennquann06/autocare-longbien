@extends('layouts.app')


@section(
    'title',
    'Thêm xe - AutoCare Long Biên'
)


@push('styles')

<style>
    .vehicle-form-page {
        max-width: 980px;
    }

    .vehicle-form-hero {
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

    .vehicle-form-hero::before {
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

    .vehicle-form-hero::after {
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        left: 40%;
        bottom: -215px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(124, 58, 237, 0.34),
                transparent 70%
            );
    }

    .vehicle-form-hero-content {
        position: relative;
        z-index: 2;
    }

    .vehicle-form-chip {
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

    .vehicle-form-chip i {
        color: #67e8f9;
    }

    .vehicle-form-hero h1 {
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

    .vehicle-form-hero p {
        max-width: 650px;
        margin: 11px 0 0;
        color: #cbd5e1;
        line-height: 1.75;
    }

    .vehicle-form-card {
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

    .vehicle-form-body {
        padding: 28px;
    }

    .vehicle-form-section {
        margin-bottom: 28px;
    }

    .vehicle-form-section:last-child {
        margin-bottom: 0;
    }

    .vehicle-form-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
        color: #0f172a;
        font-size: 17px;
        font-weight: 900;
    }

    .vehicle-form-section-icon {
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

    .vehicle-form-panel {
        padding: 21px;
        border: 1px solid #e5ecf4;
        border-radius: 16px;
        background:
            linear-gradient(
                135deg,
                #f8fbff,
                #f7faff
            );
    }

    .vehicle-form-label {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 7px;
        color: #334155;
        font-size: 12px;
        font-weight: 850;
    }

    .vehicle-form-label i {
        color: #2563eb;
    }

    .vehicle-form-control {
        min-height: 48px;
    }

    .vehicle-form-control.is-invalid,
    .vehicle-note-control.is-invalid {
        border-color: #f87171;
        background-color: #fffafa;
        box-shadow:
            0 0 0 3px
            rgba(239, 68, 68, 0.07);
    }

    .vehicle-field-hint {
        margin-top: 6px;
        color: #7c8da4;
        font-size: 10px;
        line-height: 1.5;
    }

    .vehicle-submit {
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

    .vehicle-submit:hover {
        transform: translateY(-2px);
        box-shadow:
            0 14px 30px
            rgba(37, 99, 235, 0.28);
    }

    .vehicle-back {
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
        .vehicle-form-hero {
            padding: 25px;
        }

        .vehicle-form-body {
            padding: 20px;
        }

        .vehicle-form-panel {
            padding: 17px;
        }
    }
</style>

@endpush


@section('content')

<div class="container vehicle-form-page">

    <section
        class="vehicle-form-hero"
        data-reveal="zoom"
    >

        <div class="vehicle-form-hero-content">

            <div class="vehicle-form-chip">

                <i class="bi bi-plus-circle"></i>

                New Vehicle

            </div>


            <h1>
                Thêm phương tiện
            </h1>


            <p>

                Đăng ký thông tin xe để sử dụng
                các chức năng đặt lịch,
                theo dõi lịch sử bảo dưỡng
                và nhận gợi ý chăm sóc phù hợp.

            </p>

        </div>

    </section>


    <article
        class="vehicle-form-card"
        data-reveal
    >

        <div class="vehicle-form-body">

            <form
                method="POST"
                action="{{ route('vehicles.store') }}"
                novalidate
            >

                @csrf


                <section class="vehicle-form-section">

                    <div class="vehicle-form-section-title">

                        <span class="vehicle-form-section-icon">

                            <i class="bi bi-car-front-fill"></i>

                        </span>

                        Thông tin xe

                    </div>


                    <div class="vehicle-form-panel">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label
                                    for="brand_id"
                                    class="vehicle-form-label"
                                >

                                    <i class="bi bi-building"></i>

                                    Hãng xe *

                                </label>


                                <select
                                    name="brand_id"
                                    id="brand_id"
                                    class="
                                        form-select
                                        vehicle-form-control
                                        @error('brand_id')
                                            is-invalid
                                        @enderror
                                    "
                                    aria-invalid="{{
                                        $errors->has('brand_id')
                                            ? 'true'
                                            : 'false'
                                    }}"
                                >

                                    <option value="">
                                        -- Chọn hãng xe --
                                    </option>


                                    @foreach ($brands as $brand)

                                        <option
                                            value="{{ $brand->id }}"
                                            {{
                                                old('brand_id')
                                                == $brand->id
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
                                    class="vehicle-form-label"
                                >

                                    <i class="bi bi-car-front"></i>

                                    Dòng xe *

                                </label>


                                <select
                                    name="model_id"
                                    id="model_id"
                                    class="
                                        form-select
                                        vehicle-form-control
                                        @error('model_id')
                                            is-invalid
                                        @enderror
                                    "
                                    disabled
                                    aria-invalid="{{
                                        $errors->has('model_id')
                                            ? 'true'
                                            : 'false'
                                    }}"
                                >

                                    <option value="">
                                        -- Vui lòng chọn hãng xe trước --
                                    </option>

                                </select>

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="license_plate"
                                    class="vehicle-form-label"
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
                                        vehicle-form-control
                                        @error('license_plate')
                                            is-invalid
                                        @enderror
                                    "
                                    value="{{ old('license_plate') }}"
                                    placeholder="Ví dụ: 30H-123.45"
                                    maxlength="12"
                                    autocomplete="off"
                                    aria-invalid="{{
                                        $errors->has('license_plate')
                                            ? 'true'
                                            : 'false'
                                    }}"
                                >


                                <div class="vehicle-field-hint">
                                    Có thể nhập 30H-123.45 hoặc 30H12345;
                                    hệ thống sẽ tự chuẩn hóa.
                                </div>

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="vin"
                                    class="vehicle-form-label"
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
                                        vehicle-form-control
                                        @error('vin')
                                            is-invalid
                                        @enderror
                                    "
                                    value="{{ old('vin') }}"
                                    placeholder="Ví dụ: KMHCT41D0HU123456"
                                    maxlength="17"
                                    autocomplete="off"
                                    aria-invalid="{{
                                        $errors->has('vin')
                                            ? 'true'
                                            : 'false'
                                    }}"
                                >


                                <div class="vehicle-field-hint">
                                    VIN gồm đúng 17 ký tự và không sử dụng
                                    I, O hoặc Q.
                                </div>

                            </div>


                            <div class="col-md-4">

                                <label
                                    for="manufacture_year"
                                    class="vehicle-form-label"
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
                                        vehicle-form-control
                                        @error('manufacture_year')
                                            is-invalid
                                        @enderror
                                    "
                                    value="{{ old('manufacture_year') }}"
                                    min="1980"
                                    max="{{ now()->year + 1 }}"
                                    step="1"
                                    placeholder="Ví dụ: 2022"
                                    aria-invalid="{{
                                        $errors->has('manufacture_year')
                                            ? 'true'
                                            : 'false'
                                    }}"
                                >

                            </div>


                            <div class="col-md-4">

                                <label
                                    for="color"
                                    class="vehicle-form-label"
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
                                        vehicle-form-control
                                        @error('color')
                                            is-invalid
                                        @enderror
                                    "
                                    value="{{ old('color') }}"
                                    maxlength="50"
                                    placeholder="Ví dụ: Trắng"
                                    aria-invalid="{{
                                        $errors->has('color')
                                            ? 'true'
                                            : 'false'
                                    }}"
                                >

                            </div>


                            <div class="col-md-4">

                                <label
                                    for="fuel_type"
                                    class="vehicle-form-label"
                                >

                                    <i class="bi bi-fuel-pump"></i>

                                    Loại nhiên liệu

                                </label>


                                <select
                                    name="fuel_type"
                                    id="fuel_type"
                                    class="
                                        form-select
                                        vehicle-form-control
                                        @error('fuel_type')
                                            is-invalid
                                        @enderror
                                    "
                                    aria-invalid="{{
                                        $errors->has('fuel_type')
                                            ? 'true'
                                            : 'false'
                                    }}"
                                >

                                    <option value="">
                                        -- Chọn loại nhiên liệu --
                                    </option>

                                    <option
                                        value="Xăng"
                                        {{
                                            old('fuel_type') === 'Xăng'
                                                ? 'selected'
                                                : ''
                                        }}
                                    >
                                        Xăng
                                    </option>

                                    <option
                                        value="Dầu"
                                        {{
                                            old('fuel_type') === 'Dầu'
                                                ? 'selected'
                                                : ''
                                        }}
                                    >
                                        Dầu
                                    </option>

                                    <option
                                        value="Điện"
                                        {{
                                            old('fuel_type') === 'Điện'
                                                ? 'selected'
                                                : ''
                                        }}
                                    >
                                        Điện
                                    </option>

                                    <option
                                        value="Hybrid"
                                        {{
                                            old('fuel_type') === 'Hybrid'
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
                                    class="vehicle-form-label"
                                >

                                    <i class="bi bi-speedometer2"></i>

                                    ODO hiện tại *

                                </label>


                                <div class="input-group">

                                    <input
                                        type="number"
                                        id="current_mileage"
                                        name="current_mileage"
                                        class="
                                            form-control
                                            vehicle-form-control
                                            @error('current_mileage')
                                                is-invalid
                                            @enderror
                                        "
                                        value="{{ old(
                                            'current_mileage',
                                            0
                                        ) }}"
                                        min="0"
                                        max="5000000"
                                        step="1"
                                        inputmode="numeric"
                                        aria-invalid="{{
                                            $errors->has('current_mileage')
                                                ? 'true'
                                                : 'false'
                                        }}"
                                    >

                                    <span class="input-group-text">
                                        km
                                    </span>

                                </div>


                                <div class="vehicle-field-hint">
                                    Nhập số km thực tế đang hiển thị
                                    trên đồng hồ ODO của xe.
                                </div>

                            </div>

                        </div>

                    </div>

                </section>


                <section class="vehicle-form-section">

                    <div class="vehicle-form-section-title">

                        <span class="vehicle-form-section-icon">

                            <i class="bi bi-chat-left-text"></i>

                        </span>

                        Thông tin bổ sung

                    </div>


                    <div class="vehicle-form-panel">

                        <label
                            for="note"
                            class="vehicle-form-label"
                        >

                            <i class="bi bi-pencil"></i>

                            Ghi chú

                        </label>


                        <textarea
                            id="note"
                            name="note"
                            class="
                                form-control
                                vehicle-note-control
                                @error('note')
                                    is-invalid
                                @enderror
                            "
                            rows="5"
                            maxlength="1000"
                            placeholder="Thông tin bổ sung về phương tiện..."
                            aria-invalid="{{
                                $errors->has('note')
                                    ? 'true'
                                    : 'false'
                            }}"
                        >{{ old('note') }}</textarea>


                        <div class="vehicle-field-hint">
                            Tối đa 1000 ký tự.
                        </div>

                    </div>

                </section>


                <button
                    type="submit"
                    class="vehicle-submit"
                >

                    <i class="bi bi-plus-circle me-2"></i>

                    Thêm phương tiện

                </button>

            </form>


            <a
                href="{{ route('vehicles.index') }}"
                class="vehicle-back"
            >

                <i class="bi bi-arrow-left"></i>

                Quay lại Xe của tôi

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

    const oldBrandId =
        @json(old('brand_id'));

    const oldModelId =
        @json(old('model_id'));


    async function loadModels(
        brandId,
        selectedModelId = null
    ) {
        modelSelect.innerHTML =
            '';


        if (!brandId) {
            modelSelect.disabled =
                true;

            modelSelect.innerHTML = `
                <option value="">
                    -- Vui lòng chọn hãng xe trước --
                </option>
            `;

            return;
        }


        modelSelect.disabled =
            true;

        modelSelect.innerHTML = `
            <option value="">
                Đang tải dòng xe...
            </option>
        `;


        try {
            const response =
                await fetch(
                    `/vehicle-models/${brandId}`
                );


            if (!response.ok) {
                throw new Error(
                    'Không thể tải danh sách dòng xe.'
                );
            }


            const models =
                await response.json();


            modelSelect.innerHTML = `
                <option value="">
                    -- Chọn dòng xe --
                </option>
            `;


            if (
                models.length === 0
            ) {
                modelSelect.innerHTML = `
                    <option value="">
                        Chưa có dòng xe
                    </option>
                `;

                modelSelect.disabled =
                    true;

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
                        selectedModelId
                        &&
                        String(
                            selectedModelId
                        )
                        ===
                        String(
                            model.id
                        )
                    ) {
                        option.selected =
                            true;
                    }


                    modelSelect
                        .appendChild(
                            option
                        );
                }
            );


            modelSelect.disabled =
                false;
        }
        catch (error) {
            console.error(
                error
            );


            modelSelect.innerHTML = `
                <option value="">
                    Không thể tải dữ liệu
                </option>
            `;


            modelSelect.disabled =
                true;
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


    if (oldBrandId) {
        loadModels(
            oldBrandId,
            oldModelId
        );
    }


    const licensePlateInput =
        document.getElementById(
            'license_plate'
        );


    const vinInput =
        document.getElementById(
            'vin'
        );


    licensePlateInput
        ?.addEventListener(
            'input',
            function () {
                this.value =
                    this.value
                        .toUpperCase();
            }
        );


    vinInput
        ?.addEventListener(
            'input',
            function () {
                this.value =
                    this.value
                        .toUpperCase()
                        .replace(
                            /\s+/g,
                            ''
                        );
            }
        );
</script>

@endpush