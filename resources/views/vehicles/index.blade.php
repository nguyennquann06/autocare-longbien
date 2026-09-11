<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Xe của tôi - AutoCare Long Biên
    </title>
</head>

<body>

    <h1>Xe của tôi</h1>


    {{-- Thông báo thành công --}}
    @if (session('success'))

        <div
            style="
                color: green;
                margin-bottom: 15px;
            "
        >
            {{ session('success') }}
        </div>

    @endif


    {{-- Nút thêm xe --}}
    <p>

        <a href="{{ route('vehicles.create') }}">
            + Thêm phương tiện
        </a>

    </p>


    {{-- Chưa có xe --}}
    @if ($vehicles->isEmpty())

        <p>
            Bạn chưa có phương tiện nào trong hệ thống.
        </p>

    @else

        {{-- Danh sách xe --}}
        @foreach ($vehicles as $vehicle)

            <div
                style="
                    border: 1px solid #cccccc;
                    padding: 20px;
                    margin-bottom: 20px;
                    max-width: 650px;
                "
            >

                <h2>
                    {{ $vehicle->brand->name }}
                    {{ $vehicle->vehicleModel->name }}
                </h2>


                <p>
                    <strong>Biển số:</strong>

                    {{ $vehicle->license_plate }}
                </p>


                <p>
                    <strong>Loại xe:</strong>

                    {{
                        $vehicle->vehicleModel->vehicle_type
                        ?? 'Chưa cập nhật'
                    }}
                </p>


                <p>
                    <strong>Năm sản xuất:</strong>

                    {{
                        $vehicle->manufacture_year
                        ?? 'Chưa cập nhật'
                    }}
                </p>


                <p>
                    <strong>Màu xe:</strong>

                    {{
                        $vehicle->color
                        ?? 'Chưa cập nhật'
                    }}
                </p>


                <p>
                    <strong>Nhiên liệu:</strong>

                    {{
                        $vehicle->fuel_type
                        ?? 'Chưa cập nhật'
                    }}
                </p>


                <p>
                    <strong>Số km hiện tại:</strong>

                    {{
                        number_format(
                            $vehicle->current_mileage
                        )
                    }} km
                </p>


                {{-- Các thao tác --}}
                <div
                    style="
                        display: flex;
                        gap: 12px;
                        align-items: center;
                    "
                >

                    {{-- Xem chi tiết --}}
                    <a
                        href="{{ route(
                            'vehicles.show',
                            $vehicle->id
                        ) }}"
                    >
                        Xem chi tiết
                    </a>


                    {{-- Chỉnh sửa --}}
                    <a
                        href="{{ route(
                            'vehicles.edit',
                            $vehicle->id
                        ) }}"
                    >
                        Chỉnh sửa
                    </a>


                    {{-- Xóa --}}
                    <form
                        method="POST"

                        action="{{ route(
                            'vehicles.destroy',
                            $vehicle->id
                        ) }}"

                        style="margin: 0;"

                        onsubmit="
                            return confirm(
                                'Bạn có chắc chắn muốn xóa xe biển số {{ $vehicle->license_plate }} không?'
                            );
                        "
                    >

                        @csrf

                        @method('DELETE')


                        <button
                            type="submit"

                            style="
                                color: red;
                                cursor: pointer;
                            "
                        >
                            Xóa
                        </button>

                    </form>

                </div>

            </div>

        @endforeach

    @endif


    <p>

        <a href="{{ route('home') }}">
            ← Quay lại trang chủ
        </a>

    </p>

</body>

</html>