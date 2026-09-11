<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Chi tiết xe - AutoCare Long Biên
    </title>
</head>

<body>

    <h1>Chi tiết phương tiện</h1>


    <div
        style="
            border: 1px solid #cccccc;
            padding: 20px;
            max-width: 650px;
        "
    >

        <h2>

            {{ $vehicle->brand->name }}

            {{ $vehicle->vehicleModel->name }}

        </h2>


        <p>
            <strong>Biển số xe:</strong>

            {{ $vehicle->license_plate }}
        </p>


        <p>
            <strong>Hãng xe:</strong>

            {{ $vehicle->brand->name }}
        </p>


        <p>
            <strong>Dòng xe:</strong>

            {{ $vehicle->vehicleModel->name }}
        </p>


        <p>
            <strong>Loại xe:</strong>

            {{ $vehicle->vehicleModel->vehicle_type
                ?? 'Chưa cập nhật' }}
        </p>


        <p>
            <strong>Năm sản xuất:</strong>

            {{ $vehicle->manufacture_year
                ?? 'Chưa cập nhật' }}
        </p>


        <p>
            <strong>Màu xe:</strong>

            {{ $vehicle->color
                ?? 'Chưa cập nhật' }}
        </p>


        <p>
            <strong>Loại nhiên liệu:</strong>

            {{ $vehicle->fuel_type
                ?? 'Chưa cập nhật' }}
        </p>


        <p>
            <strong>Số km hiện tại:</strong>

            {{ number_format(
                $vehicle->current_mileage
            ) }} km
        </p>


        <p>
            <strong>Số VIN:</strong>

            {{ $vehicle->vin
                ?? 'Chưa cập nhật' }}
        </p>


        <p>
            <strong>Ghi chú:</strong>

            {{ $vehicle->note
                ?? 'Không có ghi chú' }}
        </p>


        <p>
            <strong>Ngày thêm xe:</strong>

            {{ $vehicle->created_at
                ->format('d/m/Y H:i') }}
        </p>

    </div>


    <br>


    <a href="#">
        Chỉnh sửa xe
    </a>


    <br><br>


    <a href="{{ route('vehicles.index') }}">
        ← Quay lại Xe của tôi
    </a>


    <br><br>


    <a href="{{ route('home') }}">
        ← Trang chủ
    </a>

</body>

</html>