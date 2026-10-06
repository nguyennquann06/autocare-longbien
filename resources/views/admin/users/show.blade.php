@extends('layouts.app')


@section(
    'title',
    'Chi tiết tài khoản - AutoCare Long Biên'
)


@push('styles')

<style>
    .admin-user-detail {
        width: 100%;
        max-width: 1280px;

        margin: 0 auto;

        padding-bottom: 50px;
    }


    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

    .admin-user-detail-header {
        position: relative;

        overflow: hidden;

        margin-bottom: 22px;

        padding: 30px;

        border-radius: 24px;

        color: white;

        background:
            linear-gradient(
                120deg,
                #06101e,
                #102b5d 52%,
                #4338ca
            );

        box-shadow:
            0 25px 65px
            rgba(15, 23, 42, 0.18);
    }


    .admin-user-detail-header::after {
        content: "";

        position: absolute;

        width: 340px;
        height: 340px;

        top: -210px;
        right: -90px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, 0.30),
                transparent 68%
            );
    }


    .admin-user-detail-header-content {
        position: relative;

        z-index: 2;
    }


    .admin-user-detail-back {
        display: inline-flex;

        align-items: center;

        gap: 7px;

        margin-bottom: 18px;

        color: #bfdbfe;

        text-decoration: none;

        font-size: 11px;

        font-weight: 800;
    }


    .admin-user-detail-back:hover {
        color: white;
    }


    .admin-user-heading {
        display: flex;

        align-items: center;

        gap: 14px;
    }


    .admin-user-heading-avatar {
        width: 58px;
        height: 58px;

        flex: 0 0 auto;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 50%;

        color: #4338ca;

        background:
            linear-gradient(
                135deg,
                white,
                #e0e7ff
            );

        font-size: 19px;

        font-weight: 900;

        border:
            3px solid
            rgba(255, 255, 255, 0.25);
    }


    .admin-user-heading h1 {
        margin: 0;

        color: white;

        font-size:
            clamp(
                1.8rem,
                4vw,
                3rem
            );

        font-weight: 900;

        letter-spacing:
            -0.045em;
    }


    .admin-user-heading-email {
        margin-top: 5px;

        color: #cbd5e1;

        font-size: 12px;
    }


    /*
    |--------------------------------------------------------------------------
    | GENERAL PANELS
    |--------------------------------------------------------------------------
    */

    .admin-detail-grid {
        display: grid;

        grid-template-columns:
            repeat(
                2,
                minmax(0, 1fr)
            );

        gap: 20px;

        margin-bottom: 20px;
    }


    .admin-detail-panel {
        overflow: hidden;

        border:
            1px solid
            rgba(255, 255, 255, 0.88);

        border-radius: 20px;

        background:
            rgba(255, 255, 255, 0.95);

        box-shadow:
            var(--ac-shadow);
    }


    .admin-detail-panel-header {
        padding: 19px 22px;

        border-bottom:
            1px solid
            #edf2f7;

        background: #f8fafc;
    }


    .admin-detail-panel-title {
        display: flex;

        align-items: center;

        gap: 9px;

        margin: 0;

        color: #0f172a;

        font-size: 15px;

        font-weight: 900;
    }


    .admin-detail-panel-title i {
        color: #2563eb;
    }


    .admin-detail-panel-body {
        padding: 21px 22px;
    }


    .admin-detail-row {
        display: grid;

        grid-template-columns:
            150px
            minmax(0, 1fr);

        gap: 14px;

        padding: 10px 0;

        border-bottom:
            1px solid
            #edf2f7;
    }


    .admin-detail-row:last-child {
        border-bottom: none;
    }


    .admin-detail-label {
        color: #64748b;

        font-size: 10px;

        font-weight: 800;
    }


    .admin-detail-value {
        color: #0f172a;

        font-size: 11px;

        font-weight: 700;

        overflow-wrap: anywhere;
    }


    /*
    |--------------------------------------------------------------------------
    | BADGES
    |--------------------------------------------------------------------------
    */

    .admin-role-badge {
        display: inline-flex;

        align-items: center;

        padding:
            5px 9px;

        border-radius: 999px;

        color: #5b21b6;

        background: #ede9fe;

        font-size: 9px;

        font-weight: 900;
    }


    .admin-yes-badge {
        display: inline-flex;

        padding:
            5px 8px;

        border-radius: 999px;

        color: #166534;

        background: #dcfce7;

        font-size: 9px;

        font-weight: 850;
    }


    .admin-no-badge {
        display: inline-flex;

        padding:
            5px 8px;

        border-radius: 999px;

        color: #64748b;

        background: #f1f5f9;

        font-size: 9px;

        font-weight: 850;
    }


    /*
    |--------------------------------------------------------------------------
    | ROLE MANAGEMENT
    |--------------------------------------------------------------------------
    */

    .admin-role-panel {
        margin-bottom: 20px;

        overflow: hidden;

        scroll-margin-top: 110px;

        border:
            1px solid
            rgba(255, 255, 255, 0.88);

        border-radius: 20px;

        background:
            rgba(255, 255, 255, 0.95);

        box-shadow:
            var(--ac-shadow);
    }


    .admin-role-panel:target {
        border-color: #818cf8;

        box-shadow:
            0 0 0 3px
            rgba(99, 102, 241, 0.10),

            var(--ac-shadow);
    }


    .admin-role-panel-body {
        padding: 22px;
    }


    .admin-role-form {
        display: grid;

        grid-template-columns:
            minmax(
                220px,
                1fr
            )
            auto;

        gap: 12px;

        align-items: end;
    }


    .admin-role-field label {
        display: block;

        margin-bottom: 7px;

        color: #475569;

        font-size: 9px;

        font-weight: 900;

        text-transform: uppercase;

        letter-spacing: 0.05em;
    }


    .admin-role-select {
        width: 100%;

        min-height: 44px;

        padding:
            9px 12px;

        border:
            1px solid
            #cbd5e1;

        border-radius: 11px;

        color: #0f172a;

        background: white;

        font-size: 12px;

        outline: none;
    }


    .admin-role-select:focus {
        border-color: #6366f1;

        box-shadow:
            0 0 0 3px
            rgba(99, 102, 241, 0.10);
    }


    .admin-role-select.is-invalid {
        border-color: #ef4444;

        box-shadow:
            0 0 0 3px
            rgba(239, 68, 68, 0.10);
    }


    .admin-role-submit {
        min-height: 44px;

        padding:
            9px 17px;

        border: none;

        border-radius: 11px;

        color: white;

        background:
            linear-gradient(
                135deg,
                #2563eb,
                #4f46e5
            );

        cursor: pointer;

        font-size: 11px;

        font-weight: 900;

        white-space: nowrap;
    }


    .admin-role-submit:disabled {
        opacity: 0.65;

        cursor: not-allowed;
    }


    .admin-role-help {
        margin-top: 11px;

        color: #64748b;

        font-size: 10px;

        line-height: 1.7;
    }


    .admin-role-warning {
        margin-top: 14px;

        padding:
            12px 13px;

        border:
            1px solid
            #fde68a;

        border-radius: 12px;

        color: #92400e;

        background: #fffbeb;

        font-size: 10px;

        line-height: 1.65;
    }


    .admin-role-locked {
        padding: 15px;

        border:
            1px solid
            #ddd6fe;

        border-radius: 13px;

        color: #5b21b6;

        background: #f5f3ff;

        font-size: 11px;

        line-height: 1.7;
    }


    /*
    |--------------------------------------------------------------------------
    | STATISTICS
    |--------------------------------------------------------------------------
    */

    .admin-user-stat-grid {
        display: grid;

        grid-template-columns:
            repeat(
                4,
                minmax(0, 1fr)
            );

        gap: 12px;

        margin-bottom: 20px;
    }


    .admin-user-stat {
        padding: 17px;

        border:
            1px solid
            #e2e8f0;

        border-radius: 16px;

        background:
            rgba(255, 255, 255, 0.95);
    }


    .admin-user-stat-label {
        color: #64748b;

        font-size: 9px;

        font-weight: 900;

        text-transform: uppercase;

        letter-spacing: 0.05em;
    }


    .admin-user-stat-value {
        margin-top: 4px;

        color: #0f172a;

        font-size: 22px;

        font-weight: 900;
    }


    /*
    |--------------------------------------------------------------------------
    | VEHICLES
    |--------------------------------------------------------------------------
    */

    .admin-vehicle-list {
        display: grid;

        grid-template-columns:
            repeat(
                2,
                minmax(0, 1fr)
            );

        gap: 12px;
    }


    .admin-vehicle-card {
        padding: 16px;

        border:
            1px solid
            #e2e8f0;

        border-radius: 15px;

        background: #f8fafc;
    }


    .admin-vehicle-title {
        color: #0f172a;

        font-size: 12px;

        font-weight: 900;
    }


    .admin-vehicle-plate {
        margin-top: 5px;

        color: #2563eb;

        font-size: 11px;

        font-weight: 850;
    }


    .admin-vehicle-meta {
        margin-top: 6px;

        color: #64748b;

        font-size: 10px;

        line-height: 1.7;
    }


    .admin-detail-empty {
        padding: 22px;

        border:
            1px dashed
            #cbd5e1;

        border-radius: 14px;

        color: #64748b;

        background: #f8fafc;

        text-align: center;

        font-size: 11px;
    }


    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 991px) {
        .admin-detail-grid,
        .admin-vehicle-list {
            grid-template-columns: 1fr;
        }


        .admin-user-stat-grid {
            grid-template-columns:
                repeat(
                    2,
                    1fr
                );
        }
    }


    @media (max-width: 767px) {
        .admin-role-form {
            grid-template-columns: 1fr;
        }
    }


    @media (max-width: 575px) {
        .admin-user-stat-grid {
            grid-template-columns: 1fr;
        }


        .admin-user-detail-header {
            padding: 24px;
        }


        .admin-detail-row {
            grid-template-columns: 1fr;

            gap: 5px;
        }
    }
</style>

@endpush


@section('content')

<div class="container admin-user-detail">

    {{-- HEADER --}}

    <section class="admin-user-detail-header">

        <div class="admin-user-detail-header-content">

            <a
                href="{{ route('admin.users.index') }}"
                class="admin-user-detail-back"
            >

                <i class="bi bi-arrow-left"></i>

                Quay lại danh sách tài khoản

            </a>


            <div class="admin-user-heading">

                <div class="admin-user-heading-avatar">

                    {{
                        mb_strtoupper(
                            mb_substr(
                                $user->name,
                                0,
                                1
                            )
                        )
                    }}

                </div>


                <div>

                    <h1>
                        {{ $user->name }}
                    </h1>

                    <div class="admin-user-heading-email">
                        {{ $user->email }}
                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- ROLE MANAGEMENT --}}

    <section
        id="role-management"
        class="admin-role-panel"
    >

        <div class="admin-detail-panel-header">

            <h2 class="admin-detail-panel-title">

                <i class="bi bi-shield-lock-fill"></i>

                Quản lý vai trò

            </h2>

        </div>


        <div class="admin-role-panel-body">

            @if (
                $user->role?->code
                === 'ADMIN'
            )

                <div class="admin-role-locked">

                    <strong>
                        Tài khoản ADMIN được bảo vệ.
                    </strong>

                    <br>

                    Không thể thay đổi vai trò
                    của tài khoản ADMIN từ chức năng này
                    nhằm tránh mất quyền quản trị hệ thống.

                </div>

            @else

                <form
                    method="POST"
                    action="{{ route(
                        'admin.users.role.update',
                        $user->id
                    ) }}"
                    class="admin-role-form"
                    data-role-update-form
                    novalidate
                >

                    @csrf

                    @method('PATCH')


                    <div class="admin-role-field">

                        <label for="role">
                            Vai trò mới
                        </label>


                        <select
                            id="role"
                            name="role"
                            class="
                                admin-role-select
                                {{
                                    $errors->has('role')
                                        ? 'is-invalid'
                                        : ''
                                }}
                            "
                        >

                            @foreach (
                                $assignableRoles
                                as $role
                            )

                                <option
                                    value="{{ $role->code }}"
                                    @selected(
                                        old(
                                            'role',
                                            $user
                                                ->role
                                                ?->code
                                        )
                                        ===
                                        $role->code
                                    )
                                >

                                    {{ $role->name }}
                                    ({{ $role->code }})

                                </option>

                            @endforeach

                        </select>


                        <div class="admin-role-help">

                            <strong>CUSTOMER:</strong>
                            khách hàng sử dụng dịch vụ.

                            <br>

                            <strong>STAFF:</strong>
                            nhân viên vận hành garage.

                            <br>

                            <strong>TECHNICIAN:</strong>
                            kỹ thuật viên thực hiện
                            các phiếu bảo dưỡng được phân công.

                        </div>


                        @if (
                            $user->role?->code
                            === 'TECHNICIAN'
                            &&
                            $activeTechnicianOrders > 0
                        )

                            <div class="admin-role-warning">

                                Kỹ thuật viên này đang có

                                <strong>
                                    {{ $activeTechnicianOrders }}
                                </strong>

                                phiếu bảo dưỡng chưa hoàn thành.

                                <br>

                                Hệ thống sẽ không cho phép
                                chuyển sang vai trò khác
                                cho đến khi các công việc
                                đang phụ trách được hoàn tất
                                hoặc phân công lại.

                            </div>

                        @endif

                    </div>


                    <button
                        type="submit"
                        class="admin-role-submit"
                        data-role-submit
                    >

                        <i class="bi bi-arrow-repeat"></i>

                        Cập nhật vai trò

                    </button>

                </form>

            @endif

        </div>

    </section>


    {{-- ACCOUNT INFORMATION --}}

    <section class="admin-detail-grid">

        <div class="admin-detail-panel">

            <div class="admin-detail-panel-header">

                <h2 class="admin-detail-panel-title">

                    <i class="bi bi-person-vcard"></i>

                    Thông tin tài khoản

                </h2>

            </div>


            <div class="admin-detail-panel-body">

                <div class="admin-detail-row">

                    <div class="admin-detail-label">
                        ID tài khoản
                    </div>

                    <div class="admin-detail-value">
                        #{{ $user->id }}
                    </div>

                </div>


                <div class="admin-detail-row">

                    <div class="admin-detail-label">
                        Họ tên
                    </div>

                    <div class="admin-detail-value">
                        {{ $user->name }}
                    </div>

                </div>


                <div class="admin-detail-row">

                    <div class="admin-detail-label">
                        Email
                    </div>

                    <div class="admin-detail-value">
                        {{ $user->email }}
                    </div>

                </div>


                <div class="admin-detail-row">

                    <div class="admin-detail-label">
                        Vai trò
                    </div>

                    <div class="admin-detail-value">

                        <span class="admin-role-badge">

                            {{
                                $user
                                    ->role
                                    ?->code
                                ?? 'CHƯA GÁN'
                            }}

                        </span>

                    </div>

                </div>


                <div class="admin-detail-row">

                    <div class="admin-detail-label">
                        Google Login
                    </div>

                    <div class="admin-detail-value">

                        @if ($user->google_id)

                            <span class="admin-yes-badge">
                                Đã liên kết
                            </span>

                        @else

                            <span class="admin-no-badge">
                                Chưa liên kết
                            </span>

                        @endif

                    </div>

                </div>


                <div class="admin-detail-row">

                    <div class="admin-detail-label">
                        Email xác minh
                    </div>

                    <div class="admin-detail-value">

                        @if ($user->email_verified_at)

                            {{
                                $user
                                    ->email_verified_at
                                    ->format(
                                        'd/m/Y H:i'
                                    )
                            }}

                        @else

                            Chưa xác minh

                        @endif

                    </div>

                </div>


                <div class="admin-detail-row">

                    <div class="admin-detail-label">
                        Ngày tạo
                    </div>

                    <div class="admin-detail-value">

                        {{
                            $user
                                ->created_at
                                ?->format(
                                    'd/m/Y H:i'
                                )
                            ?? '—'
                        }}

                    </div>

                </div>


                <div class="admin-detail-row">

                    <div class="admin-detail-label">
                        Cập nhật gần nhất
                    </div>

                    <div class="admin-detail-value">

                        {{
                            $user
                                ->updated_at
                                ?->format(
                                    'd/m/Y H:i'
                                )
                            ?? '—'
                        }}

                    </div>

                </div>

            </div>

        </div>


        <div class="admin-detail-panel">

            <div class="admin-detail-panel-header">

                <h2 class="admin-detail-panel-title">

                    <i class="bi bi-activity"></i>

                    Thống kê hoạt động

                </h2>

            </div>


            <div class="admin-detail-panel-body">

                <div class="admin-detail-row">

                    <div class="admin-detail-label">
                        Phiếu đã tạo
                    </div>

                    <div class="admin-detail-value">
                        {{ $user->created_service_orders_count }}
                    </div>

                </div>


                <div class="admin-detail-row">

                    <div class="admin-detail-label">
                        Phiếu được phân công
                    </div>

                    <div class="admin-detail-value">
                        {{ $user->technician_service_orders_count }}
                    </div>

                </div>


                <div class="admin-detail-row">

                    <div class="admin-detail-label">
                        Giao dịch kho
                    </div>

                    <div class="admin-detail-value">
                        {{ $user->inventory_transactions_count }}
                    </div>

                </div>


                <div class="admin-detail-row">

                    <div class="admin-detail-label">
                        Hóa đơn đã lập
                    </div>

                    <div class="admin-detail-value">
                        {{ $user->created_invoices_count }}
                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- CUSTOMER DATA --}}

    @if ($customer)

        <section class="admin-user-stat-grid">

            <div class="admin-user-stat">

                <div class="admin-user-stat-label">
                    Xe
                </div>

                <div class="admin-user-stat-value">
                    {{ $customer->vehicles_count }}
                </div>

            </div>


            <div class="admin-user-stat">

                <div class="admin-user-stat-label">
                    Lịch hẹn
                </div>

                <div class="admin-user-stat-value">
                    {{ $customer->appointments_count }}
                </div>

            </div>


            <div class="admin-user-stat">

                <div class="admin-user-stat-label">
                    Phiếu bảo dưỡng
                </div>

                <div class="admin-user-stat-value">
                    {{ $customer->service_orders_count }}
                </div>

            </div>


            <div class="admin-user-stat">

                <div class="admin-user-stat-label">
                    Hóa đơn
                </div>

                <div class="admin-user-stat-value">
                    {{ $customer->invoices_count }}
                </div>

            </div>

        </section>


        <section class="admin-detail-grid">

            <div class="admin-detail-panel">

                <div class="admin-detail-panel-header">

                    <h2 class="admin-detail-panel-title">

                        <i class="bi bi-person-lines-fill"></i>

                        Hồ sơ khách hàng

                    </h2>

                </div>


                <div class="admin-detail-panel-body">

                    <div class="admin-detail-row">

                        <div class="admin-detail-label">
                            Customer ID
                        </div>

                        <div class="admin-detail-value">
                            #{{ $customer->id }}
                        </div>

                    </div>


                    <div class="admin-detail-row">

                        <div class="admin-detail-label">
                            Họ tên
                        </div>

                        <div class="admin-detail-value">
                            {{ $customer->full_name }}
                        </div>

                    </div>


                    <div class="admin-detail-row">

                        <div class="admin-detail-label">
                            Điện thoại
                        </div>

                        <div class="admin-detail-value">
                            {{ $customer->phone ?: '—' }}
                        </div>

                    </div>


                    <div class="admin-detail-row">

                        <div class="admin-detail-label">
                            Email
                        </div>

                        <div class="admin-detail-value">
                            {{ $customer->email ?: '—' }}
                        </div>

                    </div>


                    <div class="admin-detail-row">

                        <div class="admin-detail-label">
                            Địa chỉ
                        </div>

                        <div class="admin-detail-value">
                            {{ $customer->address ?: '—' }}
                        </div>

                    </div>

                </div>

            </div>


            <div class="admin-detail-panel">

                <div class="admin-detail-panel-header">

                    <h2 class="admin-detail-panel-title">

                        <i class="bi bi-car-front-fill"></i>

                        Phương tiện

                    </h2>

                </div>


                <div class="admin-detail-panel-body">

                    @if (
                        $customer
                            ->vehicles
                            ->isNotEmpty()
                    )

                        <div class="admin-vehicle-list">

                            @foreach (
                                $customer->vehicles
                                as $vehicle
                            )

                                <div class="admin-vehicle-card">

                                    <div class="admin-vehicle-title">

                                        {{
                                            $vehicle
                                                ->brand
                                                ?->name
                                            ?? 'Không rõ hãng'
                                        }}

                                        {{
                                            $vehicle
                                                ->vehicleModel
                                                ?->name
                                            ?? ''
                                        }}

                                    </div>


                                    <div class="admin-vehicle-plate">
                                        {{ $vehicle->license_plate }}
                                    </div>


                                    <div class="admin-vehicle-meta">

                                        Năm:
                                        {{
                                            $vehicle->manufacture_year
                                            ?: '—'
                                        }}

                                        <br>

                                        Nhiên liệu:
                                        {{
                                            $vehicle->fuel_type
                                            ?: '—'
                                        }}

                                        <br>

                                        ODO:

                                        {{
                                            number_format(
                                                $vehicle->current_mileage
                                                ?? 0,
                                                0,
                                                ',',
                                                '.'
                                            )
                                        }}
                                        km

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="admin-detail-empty">

                            Khách hàng chưa có
                            phương tiện.

                        </div>

                    @endif

                </div>

            </div>

        </section>

    @endif

</div>

@endsection


@push('scripts')

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function () {
            const forms =
                document.querySelectorAll(
                    '[data-role-update-form]'
                );


            forms.forEach(
                function (form) {
                    form.addEventListener(
                        'submit',
                        function () {
                            const button =
                                form.querySelector(
                                    '[data-role-submit]'
                                );


                            if (!button) {
                                return;
                            }


                            button.disabled =
                                true;


                            button.innerHTML =
                                '<span class="spinner-border spinner-border-sm me-2"></span>Đang cập nhật...';
                        }
                    );
                }
            );
        }
    );
</script>

@endpush