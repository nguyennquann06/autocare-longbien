@extends('layouts.app')


@section(
    'title',
    'Quản lý kho - AutoCare Long Biên'
)


@push('styles')

<style>
    .inventory-page {
        max-width: 1280px;
    }

    .inventory-hero {
        position: relative;
        overflow: hidden;
        padding: 33px;
        margin-bottom: 25px;
        border-radius: 27px;
        color: white;
        background:
            linear-gradient(
                120deg,
                #06101e,
                #0c3474 52%,
                #1677ff
            );
        box-shadow:
            0 25px 70px
            rgba(22, 119, 255, .22);
    }

    .inventory-hero::before {
        content: "";
        position: absolute;
        width: 340px;
        height: 340px;
        right: -100px;
        top: -200px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, .4),
                transparent 70%
            );
    }

    .inventory-hero-content {
        position: relative;
        z-index: 2;
    }

    .inventory-hero h1 {
        margin: 0;
        color: white;
        font-size:
            clamp(
                2rem,
                4vw,
                3.3rem
            );
        font-weight: 900;
        letter-spacing: -.055em;
    }

    .inventory-hero p {
        max-width: 700px;
        margin: 11px 0 0;
        color: #cbd5e1;
        line-height: 1.75;
    }

    .inventory-summary {
        display: grid;
        grid-template-columns:
            repeat(
                4,
                minmax(0, 1fr)
            );
        gap: 15px;
        margin-bottom: 22px;
    }

    .summary-card {
        padding: 19px;
        border:
            1px solid
            rgba(255, 255, 255, .88);
        border-radius: 18px;
        background:
            rgba(255, 255, 255, .92);
        box-shadow: var(--ac-shadow);
    }

    .summary-icon {
        width: 43px;
        height: 43px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        border-radius: 13px;
        color: #2563eb;
        background: #eff6ff;
        font-size: 18px;
    }

    .summary-label {
        color: #64748b;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .summary-value {
        margin-top: 5px;
        color: #0f172a;
        font-size: 23px;
        font-weight: 900;
    }

    .inventory-notice {
        display: flex;
        gap: 10px;
        margin-bottom: 22px;
        padding: 15px 17px;
        border: 1px solid #bfdbfe;
        border-radius: 14px;
        color: #1e40af;
        background: #eff6ff;
        font-size: 12px;
        line-height: 1.6;
    }

    .inventory-table-card {
        overflow: hidden;
        border:
            1px solid
            rgba(255, 255, 255, .88);
        border-radius: 20px;
        background:
            rgba(255, 255, 255, .94);
        box-shadow: var(--ac-shadow);
    }

    .inventory-table-wrapper {
        overflow-x: auto;
    }

    .inventory-table {
        width: 100%;
        min-width: 1100px;
        margin: 0;
    }

    .inventory-table th {
        padding: 14px;
        color: #475569;
        background: #f8fbff;
        font-size: 10px;
        font-weight: 850;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .inventory-table td {
        padding: 14px;
        border-bottom:
            1px solid
            #edf1f6;
        color: #334155;
        font-size: 12px;
        vertical-align: middle;
    }

    .part-name {
        color: #0f172a;
        font-weight: 900;
    }

    .part-code {
        margin-top: 3px;
        color: #94a3b8;
        font-size: 10px;
    }

    .stock-value {
        color: #0f172a;
        font-weight: 900;
    }

    .inventory-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 11px;
        border-radius: 9px;
        color: white;
        text-decoration: none;
        background:
            linear-gradient(
                135deg,
                #1683ff,
                #4f46e5
            );
        font-size: 10px;
        font-weight: 850;
        transition:
            transform .2s ease,
            box-shadow .2s ease;
    }

    .inventory-action:hover {
        color: white;
        transform:
            translateY(-2px);
        box-shadow:
            0 8px 20px
            rgba(37, 99, 235, .22);
    }

    .inventory-back {
        display: inline-flex;
        gap: 7px;
        margin-top: 20px;
        color: #64748b;
        text-decoration: none;
        font-size: 12px;
        font-weight: 750;
    }

    @media (max-width: 991px) {
        .inventory-summary {
            grid-template-columns:
                repeat(
                    2,
                    1fr
                );
        }
    }

    @media (max-width: 575px) {
        .inventory-summary {
            grid-template-columns:
                1fr;
        }
    }
</style>

@endpush


@section('content')

<div class="container inventory-page">

    <section
        class="inventory-hero"
        data-reveal="zoom"
    >

        <div class="inventory-hero-content">

            <h1>
                Kho phụ tùng
            </h1>

            <p>

                Theo dõi danh mục phụ tùng,
                giá nhập, giá bán và số lượng
                tồn kho phục vụ quá trình
                bảo dưỡng ô tô.

            </p>

        </div>

    </section>


    <section class="inventory-summary">

        <div
            class="summary-card"
            data-reveal
            data-tilt
        >

            <div class="summary-icon">

                <i class="bi bi-box-seam"></i>

            </div>

            <div class="summary-label">
                Số loại phụ tùng
            </div>

            <div class="summary-value">
                {{ $totalParts }}
            </div>

        </div>


        <div
            class="summary-card"
            data-reveal
            data-tilt
        >

            <div class="summary-icon">

                <i class="bi bi-boxes"></i>

            </div>

            <div class="summary-label">
                Tổng số lượng tồn
            </div>

            <div class="summary-value">

                {{
                    number_format(
                        $totalStockQuantity,
                        0,
                        ',',
                        '.'
                    )
                }}

            </div>

        </div>


        <div
            class="summary-card"
            data-reveal
            data-tilt
        >

            <div
                class="summary-icon"
                style="
                    color: #d97706;
                    background: #fff7ed;
                "
            >

                <i class="bi bi-exclamation-triangle"></i>

            </div>

            <div class="summary-label">
                Phụ tùng cần chú ý
            </div>

            <div class="summary-value">
                {{ $lowStockCount }}
            </div>

        </div>


        <div
            class="summary-card"
            data-reveal
            data-tilt
        >

            <div
                class="summary-icon"
                style="
                    color: #059669;
                    background: #ecfdf5;
                "
            >

                <i class="bi bi-cash-stack"></i>

            </div>

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
                }}
                đ

            </div>

        </div>

    </section>


    <div class="inventory-notice">

        <i class="bi bi-info-circle-fill"></i>

        <div>

            <strong>
                Quy tắc cảnh báo:
            </strong>

            phụ tùng đang hoạt động được coi
            là sắp hết khi số lượng tồn
            nhỏ hơn hoặc bằng mức tồn tối thiểu.

        </div>

    </div>


    @if ($parts->isEmpty())

        <div class="empty-state">

            <div class="empty-state-icon">

                <i class="bi bi-box-seam"></i>

            </div>

            <h3>
                Kho chưa có phụ tùng
            </h3>

            <p>

                Hãy seed hoặc thêm dữ liệu
                phụ tùng trước khi sử dụng.

            </p>

        </div>

    @else

        <section
            class="inventory-table-card"
            data-reveal
        >

            <div class="inventory-table-wrapper">

                <table class="table inventory-table">

                    <thead>

                        <tr>

                            <th>Phụ tùng</th>
                            <th>Nhóm</th>
                            <th>ĐVT</th>
                            <th>Giá nhập</th>
                            <th>Giá bán</th>
                            <th>Tồn hiện tại</th>
                            <th>Tồn tối thiểu</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach (
                            $parts
                            as $part
                        )

                            @php
                                if (
                                    !$part->is_active
                                ) {
                                    $stockText =
                                        'Ngừng sử dụng';

                                    $stockClass =
                                        'text-bg-secondary';
                                } elseif (
                                    (int)
                                    $part->stock_quantity
                                    === 0
                                ) {
                                    $stockText =
                                        'Hết hàng';

                                    $stockClass =
                                        'text-bg-danger';
                                } elseif (
                                    (int)
                                    $part->stock_quantity
                                    <=
                                    (int)
                                    $part->minimum_stock
                                ) {
                                    $stockText =
                                        'Sắp hết';

                                    $stockClass =
                                        'text-bg-warning';
                                } else {
                                    $stockText =
                                        'Đủ hàng';

                                    $stockClass =
                                        'text-bg-success';
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

                                    {{
                                        $part->category
                                        ?? 'Khác'
                                    }}

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
                                    }}
                                    đ

                                </td>


                                <td>

                                    {{
                                        number_format(
                                            $part->selling_price,
                                            0,
                                            ',',
                                            '.'
                                        )
                                    }}
                                    đ

                                </td>


                                <td class="stock-value">

                                    {{
                                        number_format(
                                            $part->stock_quantity,
                                            0,
                                            ',',
                                            '.'
                                        )
                                    }}

                                    {{ $part->unit }}

                                </td>


                                <td>

                                    {{
                                        number_format(
                                            $part->minimum_stock,
                                            0,
                                            ',',
                                            '.'
                                        )
                                    }}

                                    {{ $part->unit }}

                                </td>


                                <td>

                                    <span
                                        class="
                                            badge
                                            rounded-pill
                                            {{ $stockClass }}
                                        "
                                    >

                                        {{ $stockText }}

                                    </span>

                                </td>


                                <td>

                                    @if (
                                        $part->is_active
                                    )

                                        <a
                                            href="{{ route(
                                                'staff.parts.stock-in.form',
                                                $part->id
                                            ) }}"
                                            class="inventory-action"
                                        >

                                            <i class="bi bi-box-arrow-in-down"></i>

                                            Nhập kho

                                        </a>

                                    @else

                                        <span
                                            class="
                                                badge
                                                text-bg-secondary
                                            "
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

        </section>

    @endif


    <a
        href="{{ route(
            'staff.dashboard'
        ) }}"
        class="inventory-back"
    >

        <i class="bi bi-arrow-left"></i>

        Quay lại Dashboard

    </a>

</div>

@endsection