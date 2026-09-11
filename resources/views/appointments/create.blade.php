<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Đặt lịch bảo dưỡng - AutoCare Long Biên
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
            max-width: 1000px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
        }

        .header a {
            color: white;
            text-decoration: none;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .form-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin-top: 0;
        }

        h2 {
            margin-top: 30px;
            margin-bottom: 18px;
        }

        .description {
            color: #6b7280;
            line-height: 1.6;
        }

        .success {
            background: #ecfdf5;
            border: 1px solid #10b981;
            color: #065f46;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .errors {
            background: #fef2f2;
            border: 1px solid #ef4444;
            color: #991b1b;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .form-group {
            margin-bottom: 20px;
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
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 15px;
        }

        textarea {
            resize: vertical;
        }

        .warning {
            background: #fff7ed;
            border: 1px solid #fdba74;
            color: #9a3412;
            padding: 18px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .warning a {
            color: #9a3412;
            font-weight: bold;
        }

        .service-category {
            margin-bottom: 30px;
        }

        .category-title {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }

        .service-option {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            border: 1px solid #e5e7eb;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .service-option input {
            width: auto;
            margin-top: 4px;
        }

        .service-option label {
            margin: 0;
            flex: 1;
            cursor: pointer;
        }

        .service-name {
            font-weight: bold;
        }

        .service-meta {
            color: #6b7280;
            margin-top: 6px;
            font-size: 14px;
        }

        .summary {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }

        .summary p {
            margin: 8px 0;
        }

        .submit-button {
            background: #111827;
            color: white;
            border: none;
            padding: 13px 22px;
            border-radius: 7px;
            cursor: pointer;
            font-size: 16px;
        }

        .submit-button:hover {
            background: #374151;
        }

        .back-link {
            display: inline-block;
            margin-top: 25px;
            text-decoration: none;
            color: #111827;
        }
    </style>
</head>

<body>

    <header class="header">

        <div class="header-inner">

            <div class="logo">
                AutoCare Long Biên
            </div>

            <a href="{{ route('home') }}">
                Trang chủ
            </a>

        </div>

    </header>


    <main class="container">

        <div class="form-card">

            <h1>
                Đặt lịch bảo dưỡng
            </h1>

            <p class="description">
                Chọn phương tiện, dịch vụ và thời gian
                mong muốn để gửi yêu cầu đặt lịch.
            </p>


            {{-- THÔNG BÁO THÀNH CÔNG --}}
            @if (session('success'))

                <div class="success">
                    {{ session('success') }}
                </div>

            @endif


            {{-- VALIDATION --}}
            @if ($errors->any())

                <div class="errors">

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


            {{-- KHÁCH HÀNG CHƯA CÓ XE --}}
            @if ($vehicles->isEmpty())

                <div class="warning">

                    Bạn chưa có phương tiện nào trong hệ thống.

                    <br><br>

                    <a href="{{ route('vehicles.create') }}">
                        + Thêm phương tiện
                    </a>

                </div>

            @else

                <form
                    method="POST"
                    action="{{ route('appointments.store') }}"
                >

                    @csrf


                    {{-- =====================
                        PHƯƠNG TIỆN
                    ====================== --}}
                    <h2>
                        1. Chọn phương tiện
                    </h2>

                    <div class="form-group">

                        <label for="vehicle_id">
                            Phương tiện *
                        </label>

                        <select
                            name="vehicle_id"
                            id="vehicle_id"
                            required
                        >

                            <option value="">
                                -- Chọn phương tiện --
                            </option>


                            @foreach ($vehicles as $vehicle)

                                <option
                                    value="{{ $vehicle->id }}"
                                    {{
                                        old('vehicle_id') == $vehicle->id
                                            ? 'selected'
                                            : ''
                                    }}
                                >

                                    {{ $vehicle->brand->name }}

                                    {{ $vehicle->vehicleModel->name }}

                                    -

                                    {{ $vehicle->license_plate }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- =====================
                        LIÊN HỆ
                    ====================== --}}
                    <h2>
                        2. Thông tin liên hệ
                    </h2>


                    <div class="form-group">

                        <label for="contact_name">
                            Người liên hệ *
                        </label>

                        <input
                            type="text"
                            name="contact_name"
                            id="contact_name"

                            value="{{ old(
                                'contact_name',
                                $user->customer->full_name
                            ) }}"

                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="contact_phone">
                            Số điện thoại *
                        </label>

                        <input
                            type="text"
                            name="contact_phone"
                            id="contact_phone"

                            value="{{ old(
                                'contact_phone',
                                $user->customer->phone
                            ) }}"

                            placeholder="Ví dụ: 0912345678"

                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="contact_email">
                            Email
                        </label>

                        <input
                            type="email"
                            name="contact_email"
                            id="contact_email"

                            value="{{ old(
                                'contact_email',
                                $user->customer->email
                                ?? $user->email
                            ) }}"
                        >

                    </div>


                    {{-- =====================
                        NGÀY GIỜ
                    ====================== --}}
                    <h2>
                        3. Thời gian mong muốn
                    </h2>


                    <div class="form-group">

                        <label for="appointment_date">
                            Ngày đặt lịch *
                        </label>

                        <input
                            type="date"
                            name="appointment_date"
                            id="appointment_date"

                            value="{{ old(
                                'appointment_date'
                            ) }}"

                            min="{{ date('Y-m-d') }}"

                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="appointment_time">
                            Giờ đặt lịch *
                        </label>

                        <input
                            type="time"
                            name="appointment_time"
                            id="appointment_time"

                            value="{{ old(
                                'appointment_time'
                            ) }}"

                            required
                        >

                    </div>


                    {{-- =====================
                        DỊCH VỤ
                    ====================== --}}
                    <h2>
                        4. Chọn dịch vụ
                    </h2>

                    <p class="description">
                        Có thể chọn nhiều dịch vụ
                        trong cùng một lịch hẹn.
                    </p>


                    @php

                        $oldServices = old(
                            'service_ids',
                            []
                        );

                    @endphp


                    @foreach ($categories as $category)

                        @if ($category->services->isNotEmpty())

                            <div class="service-category">

                                <h3 class="category-title">
                                    {{ $category->name }}
                                </h3>


                                @foreach ($category->services as $service)

                                    <div class="service-option">

                                        <input
                                            type="checkbox"

                                            class="service-checkbox"

                                            id="service_{{ $service->id }}"

                                            name="service_ids[]"

                                            value="{{ $service->id }}"

                                            data-price="{{ (float) $service->base_price }}"

                                            data-duration="{{ (int) (
                                                $service->estimated_duration_minutes
                                                ?? 0
                                            ) }}"

                                            {{
                                                in_array(
                                                    $service->id,
                                                    $oldServices
                                                )
                                                    ? 'checked'
                                                    : ''
                                            }}
                                        >


                                        <label
                                            for="service_{{ $service->id }}"
                                        >

                                            <div class="service-name">

                                                {{ $service->name }}

                                            </div>


                                            <div class="service-meta">

                                                {{
                                                    number_format(
                                                        $service->base_price,
                                                        0,
                                                        ',',
                                                        '.'
                                                    )
                                                }} đ


                                                @if (
                                                    $service
                                                        ->estimated_duration_minutes
                                                )

                                                    ·

                                                    {{
                                                        $service
                                                            ->estimated_duration_minutes
                                                    }} phút

                                                @endif

                                            </div>

                                        </label>

                                    </div>

                                @endforeach

                            </div>

                        @endif

                    @endforeach


                    {{-- =====================
                        TỔNG DỰ KIẾN
                    ====================== --}}
                    <div class="summary">

                        <h3>
                            Thông tin dự kiến
                        </h3>


                        <p>

                            <strong>
                                Dịch vụ đã chọn:
                            </strong>

                            <span id="selected-count">
                                0
                            </span>

                        </p>


                        <p>

                            <strong>
                                Tổng giá tham khảo:
                            </strong>

                            <span id="estimated-total">
                                0 đ
                            </span>

                        </p>


                        <p>

                            <strong>
                                Tổng thời gian:
                            </strong>

                            <span id="estimated-duration">
                                0 phút
                            </span>

                        </p>

                    </div>


                    {{-- =====================
                        GHI CHÚ
                    ====================== --}}
                    <h2>
                        5. Ghi chú
                    </h2>


                    <div class="form-group">

                        <label for="customer_note">
                            Tình trạng xe / yêu cầu thêm
                        </label>

                        <textarea
                            name="customer_note"
                            id="customer_note"
                            rows="5"
                            maxlength="1000"
                            placeholder="Ví dụ: Xe có tiếng kêu khi phanh..."
                        >{{ old('customer_note') }}</textarea>

                    </div>


                    <button
                        type="submit"
                        class="submit-button"
                    >
                        Xác nhận đặt lịch
                    </button>

                </form>

            @endif


            <a
                href="{{ route('home') }}"
                class="back-link"
            >
                ← Quay lại trang chủ
            </a>

        </div>

    </main>


    <script>

        const serviceCheckboxes =
            document.querySelectorAll(
                '.service-checkbox'
            );

        const selectedCount =
            document.getElementById(
                'selected-count'
            );

        const estimatedTotal =
            document.getElementById(
                'estimated-total'
            );

        const estimatedDuration =
            document.getElementById(
                'estimated-duration'
            );


        function updateSummary() {

            let count = 0;

            let total = 0;

            let duration = 0;


            serviceCheckboxes.forEach(
                function (checkbox) {

                    if (checkbox.checked) {

                        count++;

                        total += Number(
                            checkbox.dataset.price
                        );

                        duration += Number(
                            checkbox.dataset.duration
                        );

                    }

                }
            );


            if (selectedCount) {

                selectedCount.textContent =
                    count;

            }


            if (estimatedTotal) {

                estimatedTotal.textContent =
                    new Intl.NumberFormat(
                        'vi-VN'
                    ).format(total)
                    + ' đ';

            }


            if (estimatedDuration) {

                estimatedDuration.textContent =
                    duration
                    + ' phút';

            }

        }


        serviceCheckboxes.forEach(
            function (checkbox) {

                checkbox.addEventListener(
                    'change',
                    updateSummary
                );

            }
        );


        updateSummary();

    </script>

</body>

</html>