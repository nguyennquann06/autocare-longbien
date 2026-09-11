<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Quản lý kho - AutoCare Long Biên
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            color: #1f2937;
        }

        .header {
            background: #111827;
            color: white;
            padding: 20px 30px;
        }

        .header-inner {
            max-width: 1250px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
        }

        .header-links {
            display: flex;
            gap: 20px;
        }

        .header a {
            color: white;
            text-decoration: none;
        }

        .container {
            max-width: 1250px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .page-description {
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .success {
            background: #ecfdf5;
            color: #065f46;
            padding: 15px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .error {
            background: #fef2f2;
            color: #991b1b;
            padding: 15px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(210px, 1fr));
            gap: 18px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: white;
            padding: 22px;
            border-radius: 12px;
            box-shadow:
                0 2px 10px rgba(0, 0, 0, 0.06);
        }

        .summary-label {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .summary-value {
            font-size: 26px;
            font-weight: bold;
        }

        .warning-value {
            color: #b45309;
        }

        .table-wrapper {
            background: white;
            border-radius: 12px;
            overflow-x: auto;
            box-shadow:
                0 2px 10px rgba(0, 0, 0, 0.06);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1100px;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background: #f9fafb;
            font-size: 13px;
            color: #4b5563;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .part-name {
            font-weight: bold;
        }

        .part-code {
            color: #6b7280;
            font-size: 13px;
            margin-top: 4px;
        }

        .stock {
            font-weight: bold;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            white-space: nowrap;
        }

        .good {
            background: #ecfdf5;
            color: #047857;
        }

        .low {
            background: #fff7ed;
            color: #c2410c;
        }

        .out {
            background: #fef2f2;
            color: #b91c1c;
        }

        .inactive {
            background: #f3f4f6;
            color: #6b7280;
        }

        .action-button {
            display: inline-block;
            background: #111827;
            color: white;
            text-decoration: none;
            padding: 8px 13px;
            border-radius: 6px;
            font-size: 13px;
            white-space: nowrap;
        }

        .disabled-button {
            background: #9ca3af;
            pointer-events: none;
        }

        .empty {
            background: white;
            padding: 50px;
            text-align: center;
            border-radius: 12px;
        }

        .notice {
            background: #eff6ff;
            color: #1e40af;
            padding: 15px 18px;
            border-radius: 8px;
            margin-bottom: 25px;
            line-height: 1.6;
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
                AutoCare - Quản lý kho
            </div>


            <div class="header-links">

                <a href="{{ route('staff.appointments.index') }}">
                    Lịch hẹn
                </a>

                <a href="{{ route('home') }}">
                    Trang chủ
                </a>

            </div>

        </div>

    </header>


    <main class="container">

        <h1>
            Kho phụ tùng
        </h1>


        <p class="page-description">
            Theo dõi danh mục phụ tùng,
            giá nhập, giá bán và số lượng tồn kho
            phục vụ quá trình bảo dưỡng ô tô.
        </p>


        @if (session('success'))

            <div class="success">
                {{ session('success') }}
            </div>

        @endif


        @if (session('error'))

            <div class="error">
                {{ session('error') }}
            </div>

        @endif


        <div class="summary-grid">

            <div class="summary-card">

                <div class="summary-label">
                    Số loại phụ tùng
                </div>

                <div class="summary-value">
                    {{ $totalParts }}
                </div>

            </div>


            <div class="summary-card">

                <div class="summary-label">
                    Tổng số lượng tồn
                </div>

                <div class="summary-value">
                    {{ number_format($totalStockQuantity) }}
                </div>

            </div>


            <div class="summary-card">

                <div class="summary-label">
                    Phụ tùng cần chú ý
                </div>

                <div
                    class="summary-value
                    {{ $lowStockCount > 0 ? 'warning-value' : '' }}"
                >
                    {{ $lowStockCount }}
                </div>

            </div>


            <div class="summary-card">

                <div class="summary-label">
                    Giá trị tồn theo giá nhập
                </div>

                <div class="summary-value">

                    {{
                        number_format(
                            $inventoryCostValue,
                            0,
                            ',',
                            '.'
                        )
                    }} đ

                </div>

            </div>

        </div>


        <div class="notice">

            <strong>
                Quy tắc cảnh báo:
            </strong>

            phụ tùng được coi là sắp hết khi
            số lượng tồn nhỏ hơn hoặc bằng
            mức tồn tối thiểu.

        </div>


        @if ($parts->isEmpty())

            <div class="empty">

                <h2>
                    Kho chưa có phụ tùng
                </h2>

                <p>
                    Hãy seed hoặc thêm dữ liệu phụ tùng
                    trước khi sử dụng chức năng này.
                </p>

            </div>

        @else

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>
                                Phụ tùng
                            </th>

                            <th>
                                Nhóm
                            </th>

                            <th>
                                ĐVT
                            </th>

                            <th>
                                Giá nhập
                            </th>

                            <th>
                                Giá bán
                            </th>

                            <th>
                                Tồn hiện tại
                            </th>

                            <th>
                                Tồn tối thiểu
                            </th>

                            <th>
                                Trạng thái
                            </th>

                            <th>
                                Thao tác
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach ($parts as $part)

                            @php

                                if (!$part->is_active) {

                                    $stockText =
                                        'Ngừng sử dụng';

                                    $stockClass =
                                        'inactive';

                                } elseif (
                                    $part->stock_quantity === 0
                                ) {

                                    $stockText =
                                        'Hết hàng';

                                    $stockClass =
                                        'out';

                                } elseif (
                                    $part->stock_quantity <=
                                    $part->minimum_stock
                                ) {

                                    $stockText =
                                        'Sắp hết';

                                    $stockClass =
                                        'low';

                                } else {

                                    $stockText =
                                        'Đủ hàng';

                                    $stockClass =
                                        'good';
                                }

                            @endphp


                            <tr>

                                <td>

                                    <div class="part-name">
                                        {{ $part->name }}
                                    </div>

                                    <div class="part-code">
                                        {{ $part->code }}
                                    </div>

                                </td>


                                <td>
                                    {{ $part->category ?? 'Khác' }}
                                </td>


                                <td>
                                    {{ $part->unit }}
                                </td>


                                <td>

                                    {{
                                        number_format(
                                            $part->cost_price,
                                            0,
                                            ',',
                                            '.'
                                        )
                                    }} đ

                                </td>


                                <td>

                                    {{
                                        number_format(
                                            $part->selling_price,
                                            0,
                                            ',',
                                            '.'
                                        )
                                    }} đ

                                </td>


                                <td class="stock">

                                    {{
                                        number_format(
                                            $part->stock_quantity
                                        )
                                    }}

                                    {{ $part->unit }}

                                </td>


                                <td>

                                    {{
                                        number_format(
                                            $part->minimum_stock
                                        )
                                    }}

                                    {{ $part->unit }}

                                </td>


                                <td>

                                    <span
                                        class="badge {{ $stockClass }}"
                                    >
                                        {{ $stockText }}
                                    </span>

                                </td>


                                <td>

                                    @if ($part->is_active)

                                        <a
                                            href="{{ route(
                                                'staff.parts.stock-in.form',
                                                $part->id
                                            ) }}"
                                            class="action-button"
                                        >
                                            Nhập kho
                                        </a>

                                    @else

                                        <span
                                            class="action-button disabled-button"
                                        >
                                            Ngừng sử dụng
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif


        <a
            href="{{ route('staff.appointments.index') }}"
            class="back"
        >
            ← Quay lại quản lý lịch hẹn
        </a>

    </main>

</body>

</html>