<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Chỉnh sửa xe - AutoCare Long Biên
    </title>

</head>

<body>

    <h1>Chỉnh sửa phương tiện</h1>


    {{-- Validation errors --}}
    @if ($errors->any())

        <div style="color: red; margin-bottom: 15px;">

            <strong>
                Vui lòng kiểm tra lại thông tin:
            </strong>

            <ul>

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'vehicles.update',
            $vehicle->id
        ) }}"
    >

        @csrf

        @method('PUT')


        {{-- Hãng xe --}}
        <div style="margin-bottom: 15px;">

            <label for="brand_id">
                Hãng xe
            </label>

            <br>

            <select
                name="brand_id"
                id="brand_id"
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


        {{-- Dòng xe --}}
        <div style="margin-bottom: 15px;">

            <label for="model_id">
                Dòng xe
            </label>

            <br>

            <select
                name="model_id"
                id="model_id"
                required
            >

                <option value="">
                    Đang tải...
                </option>

            </select>

        </div>


        {{-- Biển số --}}
        <div style="margin-bottom: 15px;">

            <label for="license_plate">
                Biển số xe
            </label>

            <br>

            <input
                type="text"
                id="license_plate"
                name="license_plate"

                value="{{ old(
                    'license_plate',
                    $vehicle->license_plate
                ) }}"

                required
            >

        </div>


        {{-- VIN --}}
        <div style="margin-bottom: 15px;">

            <label for="vin">
                Số VIN
            </label>

            <br>

            <input
                type="text"
                id="vin"
                name="vin"

                value="{{ old(
                    'vin',
                    $vehicle->vin
                ) }}"
            >

        </div>


        {{-- Năm sản xuất --}}
        <div style="margin-bottom: 15px;">

            <label for="manufacture_year">
                Năm sản xuất
            </label>

            <br>

            <input
                type="number"
                id="manufacture_year"
                name="manufacture_year"

                value="{{ old(
                    'manufacture_year',
                    $vehicle->manufacture_year
                ) }}"

                min="1980"

                max="{{ date('Y') + 1 }}"
            >

        </div>


        {{-- Màu --}}
        <div style="margin-bottom: 15px;">

            <label for="color">
                Màu xe
            </label>

            <br>

            <input
                type="text"
                id="color"
                name="color"

                value="{{ old(
                    'color',
                    $vehicle->color
                ) }}"
            >

        </div>


        {{-- Nhiên liệu --}}
        <div style="margin-bottom: 15px;">

            <label for="fuel_type">
                Loại nhiên liệu
            </label>

            <br>

            @php

                $fuelType = old(
                    'fuel_type',
                    $vehicle->fuel_type
                );

            @endphp


            <select
                name="fuel_type"
                id="fuel_type"
            >

                <option value="">
                    -- Chọn loại nhiên liệu --
                </option>

                <option
                    value="Xăng"
                    {{ $fuelType === 'Xăng'
                        ? 'selected'
                        : '' }}
                >
                    Xăng
                </option>

                <option
                    value="Dầu"
                    {{ $fuelType === 'Dầu'
                        ? 'selected'
                        : '' }}
                >
                    Dầu
                </option>

                <option
                    value="Điện"
                    {{ $fuelType === 'Điện'
                        ? 'selected'
                        : '' }}
                >
                    Điện
                </option>

                <option
                    value="Hybrid"
                    {{ $fuelType === 'Hybrid'
                        ? 'selected'
                        : '' }}
                >
                    Hybrid
                </option>

            </select>

        </div>


        {{-- ODO --}}
        <div style="margin-bottom: 15px;">

            <label for="current_mileage">
                Số km hiện tại
            </label>

            <br>

            <input
                type="number"
                id="current_mileage"
                name="current_mileage"

                value="{{ old(
                    'current_mileage',
                    $vehicle->current_mileage
                ) }}"

                min="0"

                placeholder="Ví dụ: 35000"

                required
            >

        </div>


        {{-- Ghi chú --}}
        <div style="margin-bottom: 15px;">

            <label for="note">
                Ghi chú
            </label>

            <br>

            <textarea
                id="note"
                name="note"
                rows="4"
                cols="40"
            >{{ old(
                'note',
                $vehicle->note
            ) }}</textarea>

        </div>


        <button type="submit">
            Lưu thay đổi
        </button>

    </form>


    <br>


    <a href="{{ route(
        'vehicles.show',
        $vehicle->id
    ) }}">
        ← Quay lại chi tiết xe
    </a>


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


                models.forEach(function (model) {

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
                        String(model.id) ===
                        String(selectedModelId)
                    ) {

                        option.selected = true;

                    }


                    modelSelect.appendChild(
                        option
                    );

                });


                modelSelect.disabled = false;

            }
            catch (error) {

                console.error(error);

                modelSelect.innerHTML = `
                    <option value="">
                        Không thể tải dữ liệu
                    </option>
                `;

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

</body>

</html>