<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Tạo phiếu bảo dưỡng - AutoCare Long Biên
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6f8;
            color: #1f2937;
        }

        .header {
            background: #111827;
            color: white;
            padding: 20px 30px;
        }

        .header-inner {
            max-width: 950px;
            margin: auto;
            display: flex;
            justify-content: space-between;
        }

        .header a {
            color: white;
            text-decoration: none;
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
        }

        .container {
            max-width: 950px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow:
                0 2px 10px rgba(0, 0, 0, 0.07);
        }

        .errors {
            background: #fef2f2;
            color: #991b1b;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-grid {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 25px 0;
        }

        .info-box {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
        }

        .label {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .value {
            font-weight: bold;
        }

        .service-item {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            border-bottom: 1px solid #e5e7eb;
            padding: 12px 0;
        }

        .form-group {
            margin-top: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 15px;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .button {
            margin-top: 25px;
            background: #111827;
            color: white;
            border: none;
            border-radius: 7px;
            padding: 13px 20px;
            cursor: pointer;
            font-size: 16px;
        }

        .back {
            display: inline-block;
            margin-top: 25px;
            color: #111827;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <header class="header">

        <div class="header-inner">

            <div class="logo">
                AutoCare - Nhân viên
            </div>

            <a
                href="{{ route(
                    'staff.appointments.show',
                    $appointment->id
                ) }}"
            >
                Quay lại lịch hẹn
            </a>

        </div>

    </header>


    <main class="container">

        <div class="card">

            <h1>
                Tạo phiếu bảo dưỡng
            </h1>

            <p>
                Mã lịch hẹn:
                <strong>
                    {{ $appointment->appointment_code }}
                </strong>
            </p>


            @if ($errors->any())

                <div class="errors">

                    <strong>
                        Vui lòng kiểm tra lại:
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


            <div class="info-grid">

                <div class="info-box">

                    <div class="label">
                        Khách hàng
                    </div>

                    <div class="value">
                        {{ $appointment->contact_name }}
                    </div>

                </div>


                <div class="info-box">

                    <div class="label">
                        Phương tiện
                    </div>

                    <div class="value">

                        {{ $appointment->vehicle->brand->name }}

                        {{ $appointment->vehicle->vehicleModel->name }}

                    </div>

                </div>


                <div class="info-box">

                    <div class="label">
                        Biển số
                    </div>

                    <div class="value">
                        {{ $appointment->vehicle->license_plate }}
                    </div>

                </div>


                <div class="info-box">

                    <div class="label">
                        ODO hiện tại
                    </div>

                    <div class="value">

                        {{
                            number_format(
                                $appointment
                                    ->vehicle
                                    ->current_mileage
                            )
                        }} km

                    </div>

                </div>

            </div>


            <h2>
                Dịch vụ khách đã đặt
            </h2>


            @foreach ($appointment->services as $service)

                <div class="service-item">

                    <span>
                        {{ $service->name }}
                    </span>

                    <strong>

                        {{
                            number_format(
                                $service->pivot->price,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </strong>

                </div>

            @endforeach


            <form
                method="POST"
                action="{{ route(
                    'staff.service-orders.store',
                    $appointment->id
                ) }}"
            >

                @csrf


                <div class="form-group">

                    <label for="technician_id">
                        Kỹ thuật viên phụ trách *
                    </label>

                    <select
                        id="technician_id"
                        name="technician_id"
                        required
                    >

                        <option value="">
                            -- Chọn kỹ thuật viên --
                        </option>


                        @foreach ($technicians as $technician)

                            <option
                                value="{{ $technician->id }}"
                                {{
                                    old('technician_id')
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

                </div>


                <div class="form-group">

                    <label for="received_mileage">
                        ODO khi tiếp nhận xe *
                    </label>

                    <input
                        type="number"
                        id="received_mileage"
                        name="received_mileage"
                        min="{{ $appointment->vehicle->current_mileage }}"
                        value="{{ old(
                            'received_mileage',
                            $appointment->vehicle->current_mileage
                        ) }}"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="vehicle_condition">
                        Tình trạng xe khi tiếp nhận
                    </label>

                    <textarea
                        id="vehicle_condition"
                        name="vehicle_condition"
                        maxlength="2000"
                        placeholder="Ví dụ: Ngoại thất bình thường, khách phản ánh tiếng kêu khi phanh..."
                    >{{ old('vehicle_condition') }}</textarea>

                </div>


                <div class="form-group">

                    <label for="diagnosis">
                        Chẩn đoán ban đầu
                    </label>

                    <textarea
                        id="diagnosis"
                        name="diagnosis"
                        maxlength="2000"
                        placeholder="Kết quả kiểm tra ban đầu..."
                    >{{ old('diagnosis') }}</textarea>

                </div>


                <div class="form-group">

                    <label for="staff_note">
                        Ghi chú nhân viên
                    </label>

                    <textarea
                        id="staff_note"
                        name="staff_note"
                        maxlength="1000"
                    >{{ old('staff_note') }}</textarea>

                </div>


                <button
                    type="submit"
                    class="button"
                    onclick="
                        return confirm(
                            'Xác nhận tạo phiếu bảo dưỡng cho xe này?'
                        );
                    "
                >
                    Tạo phiếu bảo dưỡng
                </button>

            </form>


            <a
                href="{{ route(
                    'staff.appointments.show',
                    $appointment->id
                ) }}"
                class="back"
            >
                ← Quay lại lịch hẹn
            </a>

        </div>

    </main>

</body>

</html>