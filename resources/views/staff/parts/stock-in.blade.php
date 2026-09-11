<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Nhập kho - AutoCare Long Biên
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
            max-width: 800px;
            margin: auto;
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
            max-width: 800px;
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

        .part-info {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 7px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .label {
            color: #6b7280;
        }

        .value {
            font-weight: bold;
            text-align: right;
        }

        .form-group {
            margin-top: 22px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 15px;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
            font-family: Arial, sans-serif;
        }

        .hint {
            color: #6b7280;
            font-size: 13px;
            margin-top: 6px;
        }

        .button {
            background: #111827;
            color: white;
            border: none;
            border-radius: 7px;
            padding: 13px 20px;
            margin-top: 25px;
            font-size: 15px;
            cursor: pointer;
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
                AutoCare - Nhập kho
            </div>

            <a href="{{ route('staff.parts.index') }}">
                Kho phụ tùng
            </a>

        </div>

    </header>


    <main class="container">

        <article class="card">

            <h1>
                Nhập kho phụ tùng
            </h1>


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


            <div class="part-info">

                <div class="info-row">

                    <span class="label">
                        Mã phụ tùng
                    </span>

                    <span class="value">
                        {{ $part->code }}
                    </span>

                </div>


                <div class="info-row">

                    <span class="label">
                        Tên phụ tùng
                    </span>

                    <span class="value">
                        {{ $part->name }}
                    </span>

                </div>


                <div class="info-row">

                    <span class="label">
                        Đơn vị tính
                    </span>

                    <span class="value">
                        {{ $part->unit }}
                    </span>

                </div>


                <div class="info-row">

                    <span class="label">
                        Tồn hiện tại
                    </span>

                    <span class="value">

                        {{
                            number_format(
                                $part->stock_quantity
                            )
                        }}

                        {{ $part->unit }}

                    </span>

                </div>


                <div class="info-row">

                    <span class="label">
                        Giá nhập gần nhất
                    </span>

                    <span class="value">

                        {{
                            number_format(
                                $part->cost_price,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </span>

                </div>

            </div>


            <form
                method="POST"
                action="{{ route(
                    'staff.parts.stock-in',
                    $part->id
                ) }}"
            >

                @csrf


                <div class="form-group">

                    <label for="quantity">
                        Số lượng nhập *
                    </label>

                    <input
                        type="number"
                        id="quantity"
                        name="quantity"
                        min="1"
                        step="1"
                        value="{{ old('quantity') }}"
                        required
                    >

                    <div class="hint">
                        Đơn vị:
                        {{ $part->unit }}
                    </div>

                </div>


                <div class="form-group">

                    <label for="unit_cost">
                        Giá nhập / đơn vị *
                    </label>

                    <input
                        type="number"
                        id="unit_cost"
                        name="unit_cost"
                        min="0.01"
                        step="0.01"
                        value="{{ old(
                            'unit_cost',
                            $part->cost_price
                        ) }}"
                        required
                    >

                    <div class="hint">
                        Giá này sẽ trở thành
                        giá nhập gần nhất của phụ tùng.
                    </div>

                </div>


                <div class="form-group">

                    <label for="note">
                        Ghi chú
                    </label>

                    <textarea
                        id="note"
                        name="note"
                        maxlength="1000"
                        placeholder="Ví dụ: Nhập hàng bổ sung từ nhà cung cấp..."
                    >{{ old('note') }}</textarea>

                </div>


                <button
                    type="submit"
                    class="button"
                    onclick="
                        return confirm(
                            'Xác nhận nhập số lượng phụ tùng này vào kho?'
                        );
                    "
                >
                    Xác nhận nhập kho
                </button>

            </form>


            <a
                href="{{ route('staff.parts.index') }}"
                class="back"
            >
                ← Quay lại kho phụ tùng
            </a>

        </article>

    </main>

</body>

</html>