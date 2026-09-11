<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Thực hiện bảo dưỡng - AutoCare Long Biên
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
            margin: auto;
            display: flex;
            justify-content: space-between;
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
            max-width: 1000px;
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

        .success {
            background: #ecfdf5;
            color: #065f46;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .error {
            background: #fef2f2;
            color: #991b1b;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .status {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 20px;
            font-weight: bold;
        }

        .received {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .progress {
            background: #f5f3ff;
            color: #6d28d9;
        }

        .completed {
            background: #ecfdf5;
            color: #047857;
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

        .section {
            margin-top: 30px;
        }

        .note {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            line-height: 1.6;
        }

        .item {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
        }

        .item-top {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
        }

        .item-name {
            font-size: 18px;
            font-weight: bold;
        }

        .item-status {
            font-size: 13px;
            font-weight: bold;
        }

        textarea {
            width: 100%;
            min-height: 90px;
            margin-top: 12px;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            resize: vertical;
        }

        .button {
            margin-top: 15px;
            padding: 11px 17px;
            border: none;
            border-radius: 6px;
            background: #111827;
            color: white;
            cursor: pointer;
        }

        .start-button {
            background: #1d4ed8;
        }

        .complete-button {
            background: #047857;
        }

        .finish-box {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
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
                AutoCare - Kỹ thuật viên
            </div>

            <a
                href="{{ route(
                    'technician.service-orders.index'
                ) }}"
            >
                Công việc của tôi
            </a>

        </div>

    </header>


    <main class="container">

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


        <article class="card">

            @php

                $statusText = match (
                    $serviceOrder->status
                ) {
                    'RECEIVED' =>
                        'Đã tiếp nhận',

                    'IN_PROGRESS' =>
                        'Đang thực hiện',

                    'COMPLETED' =>
                        'Hoàn thành',

                    'CANCELLED' =>
                        'Đã hủy',

                    default =>
                        $serviceOrder->status,
                };


                $statusClass = match (
                    $serviceOrder->status
                ) {
                    'RECEIVED' =>
                        'received',

                    'IN_PROGRESS' =>
                        'progress',

                    'COMPLETED' =>
                        'completed',

                    default =>
                        'received',
                };

            @endphp


            <p>
                Mã phiếu:

                <strong>
                    {{ $serviceOrder->order_code }}
                </strong>
            </p>


            <h1>
                Thực hiện bảo dưỡng
            </h1>


            <span class="status {{ $statusClass }}">
                {{ $statusText }}
            </span>


            <div class="info-grid">

                <div class="info-box">

                    <div class="label">
                        Khách hàng
                    </div>

                    <div class="value">
                        {{ $serviceOrder->customer->full_name }}
                    </div>

                </div>


                <div class="info-box">

                    <div class="label">
                        Xe
                    </div>

                    <div class="value">

                        {{ $serviceOrder->vehicle->brand->name }}

                        {{ $serviceOrder->vehicle->vehicleModel->name }}

                    </div>

                </div>


                <div class="info-box">

                    <div class="label">
                        Biển số
                    </div>

                    <div class="value">
                        {{ $serviceOrder->vehicle->license_plate }}
                    </div>

                </div>


                <div class="info-box">

                    <div class="label">
                        ODO
                    </div>

                    <div class="value">

                        {{
                            number_format(
                                $serviceOrder->received_mileage
                            )
                        }} km

                    </div>

                </div>

            </div>


            @if ($serviceOrder->vehicle_condition)

                <section class="section">

                    <h2>
                        Tình trạng xe
                    </h2>

                    <div class="note">
                        {{ $serviceOrder->vehicle_condition }}
                    </div>

                </section>

            @endif


            @if ($serviceOrder->diagnosis)

                <section class="section">

                    <h2>
                        Chẩn đoán ban đầu
                    </h2>

                    <div class="note">
                        {{ $serviceOrder->diagnosis }}
                    </div>

                </section>

            @endif


            {{-- Bắt đầu Service Order --}}
            @if ($serviceOrder->status === 'RECEIVED')

                <section class="section">

                    <form
                        method="POST"
                        action="{{ route(
                            'technician.service-orders.start',
                            $serviceOrder->id
                        ) }}"
                        onsubmit="
                            return confirm(
                                'Bắt đầu thực hiện phiếu bảo dưỡng này?'
                            );
                        "
                    >

                        @csrf
                        @method('PATCH')


                        <button
                            type="submit"
                            class="button start-button"
                        >
                            Bắt đầu bảo dưỡng
                        </button>

                    </form>

                </section>

            @endif


            <section class="section">

                <h2>
                    Hạng mục công việc
                </h2>


                @foreach ($serviceOrder->items as $item)

                    <div class="item">

                        <div class="item-top">

                            <div>

                                <div class="item-name">
                                    {{ $item->service_name }}
                                </div>

                                <div>
                                    {{
                                        number_format(
                                            $item->line_total,
                                            0,
                                            ',',
                                            '.'
                                        )
                                    }} đ
                                </div>

                            </div>


                            <div class="item-status">

                                {{
                                    match ($item->status) {
                                        'PENDING' =>
                                            'Chưa thực hiện',

                                        'IN_PROGRESS' =>
                                            'Đang thực hiện',

                                        'COMPLETED' =>
                                            'Hoàn thành',

                                        'CANCELLED' =>
                                            'Đã hủy',

                                        default =>
                                            $item->status,
                                    }
                                }}

                            </div>

                        </div>


                        @if (
                            $serviceOrder->status === 'IN_PROGRESS' &&
                            $item->status !== 'COMPLETED'
                        )

                            <form
                                method="POST"
                                action="{{ route(
                                    'technician.service-orders.items.update',
                                    [
                                        $serviceOrder->id,
                                        $item->id,
                                    ]
                                ) }}"
                            >

                                @csrf
                                @method('PATCH')


                                @if ($item->status === 'PENDING')

                                    <input
                                        type="hidden"
                                        name="status"
                                        value="IN_PROGRESS"
                                    >

                                @elseif ($item->status === 'IN_PROGRESS')

                                    <input
                                        type="hidden"
                                        name="status"
                                        value="COMPLETED"
                                    >

                                @endif


                                <textarea
                                    name="technician_note"
                                    maxlength="1000"
                                    placeholder="Ghi chú kỹ thuật cho hạng mục..."
                                >{{ $item->technician_note }}</textarea>


                                <button
                                    type="submit"
                                    class="button"
                                >

                                    @if ($item->status === 'PENDING')

                                        Bắt đầu hạng mục

                                    @else

                                        Hoàn thành hạng mục

                                    @endif

                                </button>

                            </form>

                        @endif


                        @if (
                            $item->status === 'COMPLETED' &&
                            $item->technician_note
                        )

                            <div class="note" style="margin-top: 12px;">

                                <strong>Ghi chú:</strong>

                                {{ $item->technician_note }}

                            </div>

                        @endif

                    </div>

                @endforeach

            </section>


            @if ($serviceOrder->status === 'IN_PROGRESS')

                @php

                    $allItemsCompleted =
                        $serviceOrder
                            ->items
                            ->every(
                                fn ($item) =>
                                    $item->status === 'COMPLETED'
                            );

                @endphp


                <section class="section">

                    <h2>
                        Hoàn tất phiếu
                    </h2>


                    @if ($allItemsCompleted)

                        <form
                            method="POST"
                            action="{{ route(
                                'technician.service-orders.complete',
                                $serviceOrder->id
                            ) }}"
                            class="finish-box"
                            onsubmit="
                                return confirm(
                                    'Xác nhận hoàn thành toàn bộ phiếu bảo dưỡng?'
                                );
                            "
                        >

                            @csrf
                            @method('PATCH')


                            <label for="technician_note">

                                <strong>
                                    Ghi chú tổng kết kỹ thuật
                                </strong>

                            </label>


                            <textarea
                                id="technician_note"
                                name="technician_note"
                                maxlength="2000"
                                placeholder="Ví dụ: Đã hoàn thành toàn bộ hạng mục, xe vận hành ổn định..."
                            >{{ $serviceOrder->technician_note }}</textarea>


                            <button
                                type="submit"
                                class="button complete-button"
                            >
                                Hoàn thành phiếu bảo dưỡng
                            </button>

                        </form>

                    @else

                        <div class="note">
                            Cần hoàn thành tất cả hạng mục
                            trước khi đóng phiếu bảo dưỡng.
                        </div>

                    @endif

                </section>

            @endif


            @if ($serviceOrder->status === 'COMPLETED')

                <section class="section">

                    <div class="note">

                        <strong>
                            Phiếu bảo dưỡng đã hoàn thành.
                        </strong>

                        @if ($serviceOrder->completed_at)

                            <br><br>

                            Thời điểm hoàn thành:

                            {{
                                $serviceOrder
                                    ->completed_at
                                    ->format('d/m/Y H:i')
                            }}

                        @endif

                    </div>

                </section>

            @endif


            <a
                href="{{ route(
                    'technician.service-orders.index'
                ) }}"
                class="back"
            >
                ← Quay lại công việc của tôi
            </a>

        </article>

    </main>

</body>

</html>