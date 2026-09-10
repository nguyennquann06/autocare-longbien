<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Thêm xe - AutoCare Long Biên</title>
</head>

<body>

    <h1>Thêm phương tiện</h1>


    {{-- Thông báo thành công --}}
    @if (session('success'))
        <div style="color: green; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif


    {{-- Hiển thị lỗi --}}
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


    <form method="POST"
          action="{{ route('vehicles.store') }}">

        @csrf


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
                        {{ old('brand_id') == $brand->id ? 'selected' : '' }}
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
                disabled
            >
                <option value="">
                    -- Vui lòng chọn hãng xe trước --
                </option>
            </select>

        </div>


        {{-- Biển số xe --}}
        <div style="margin-bottom: 15px;">

            <label for="license_plate">
                Biển số xe
            </label>

            <br>

            <input
                type="text"
                id="license_plate"
                name="license_plate"
                value="{{ old('license_plate') }}"
                placeholder="Ví dụ: 30H-123.45"
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
                value="{{ old('vin') }}"
                placeholder="Có thể để trống"
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
                value="{{ old('manufacture_year') }}"
                min="1980"
                max="{{ date('Y') + 1 }}"
                placeholder="Ví dụ: 2022"
            >

        </div>


        {{-- Màu xe --}}
        <div style="margin-bottom: 15px;">

            <label for="color">
                Màu xe
            </label>

            <br>

            <input
                type="text"
                id="color"
                name="color"
                value="{{ old('color') }}"
                placeholder="Ví dụ: Trắng"
            >

        </div>


        {{-- Loại nhiên liệu --}}
        <div style="margin-bottom: 15px;">

            <label for="fuel_type">
                Loại nhiên liệu
            </label>

            <br>

            <select
                name="fuel_type"
                id="fuel_type"
            >
                <option value="">
                    -- Chọn loại nhiên liệu --
                </option>

                <option
                    value="Xăng"
                    {{ old('fuel_type') == 'Xăng' ? 'selected' : '' }}
                >
                    Xăng
                </option>

                <option
                    value="Dầu"
                    {{ old('fuel_type') == 'Dầu' ? 'selected' : '' }}
                >
                    Dầu
                </option>

                <option
                    value="Điện"
                    {{ old('fuel_type') == 'Điện' ? 'selected' : '' }}
                >
                    Điện
                </option>

                <option
                    value="Hybrid"
                    {{ old('fuel_type') == 'Hybrid' ? 'selected' : '' }}
                >
                    Hybrid
                </option>

            </select>

        </div>


        {{-- Số km hiện tại --}}
        <div style="margin-bottom: 15px;">

            <label for="current_mileage">
                Số km hiện tại
            </label>

            <br>

            <input
                type="number"
                id="current_mileage"
                name="current_mileage"
                value="{{ old('current_mileage', 0) }}"
                min="0"
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
                placeholder="Thông tin bổ sung về phương tiện..."
            >{{ old('note') }}</textarea>

        </div>


        <button type="submit">
            Thêm xe
        </button>

    </form>


    <br>

    <p>
        <a href="{{ route('home') }}">
            ← Quay lại trang chủ
        </a>
    </p>


    <script>
        const brandSelect = document.getElementById('brand_id');
        const modelSelect = document.getElementById('model_id');

        const oldBrandId = @json(old('brand_id'));
        const oldModelId = @json(old('model_id'));


        /**
         * Tải danh sách dòng xe theo hãng.
         */
        async function loadModels(brandId, selectedModelId = null) {

            modelSelect.innerHTML = '';

            if (!brandId) {

                modelSelect.disabled = true;

                modelSelect.innerHTML = `
                    <option value="">
                        -- Vui lòng chọn hãng xe trước --
                    </option>
                `;

                return;
            }


            modelSelect.disabled = true;

            modelSelect.innerHTML = `
                <option value="">
                    Đang tải dòng xe...
                </option>
            `;


            try {

                const response = await fetch(
                    `/vehicle-models/${brandId}`
                );

                if (!response.ok) {
                    throw new Error('Không thể tải danh sách dòng xe.');
                }

                const models = await response.json();


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


                models.forEach(function (model) {

                    const option = document.createElement('option');

                    option.value = model.id;

                    option.textContent = model.vehicle_type
                        ? `${model.name} - ${model.vehicle_type}`
                        : model.name;


                    if (
                        selectedModelId &&
                        String(selectedModelId) === String(model.id)
                    ) {
                        option.selected = true;
                    }


                    modelSelect.appendChild(option);
                });


                modelSelect.disabled = false;

            } catch (error) {

                console.error(error);

                modelSelect.innerHTML = `
                    <option value="">
                        Không thể tải dữ liệu
                    </option>
                `;

                modelSelect.disabled = true;
            }
        }


        /**
         * Khi thay đổi hãng xe.
         */
        brandSelect.addEventListener('change', function () {

            loadModels(this.value);

        });


        /**
         * Nếu form validate lỗi và Laravel redirect quay lại,
         * tự tải lại dòng xe đã chọn trước đó.
         */
        if (oldBrandId) {

            loadModels(
                oldBrandId,
                oldModelId
            );

        }
    </script>

</body>

</html>