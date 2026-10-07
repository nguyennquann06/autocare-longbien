@extends('layouts.app')


@section(
    'title',
    'Tạo tài khoản nhân sự - AutoCare Long Biên'
)


@push('styles')

<style>
    .admin-create-user-page {
        width: 100%;
        max-width: 1050px;

        margin: 0 auto;

        padding-bottom: 50px;
    }


    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

    .admin-create-user-header {
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
            rgba(
                15,
                23,
                42,
                0.18
            );
    }


    .admin-create-user-header::after {
        content: "";

        position: absolute;

        width: 350px;
        height: 350px;

        top: -220px;
        right: -90px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(
                    103,
                    232,
                    249,
                    0.30
                ),
                transparent 68%
            );
    }


    .admin-create-user-header-content {
        position: relative;

        z-index: 2;
    }


    .admin-create-user-back {
        display: inline-flex;

        align-items: center;

        gap: 7px;

        margin-bottom: 17px;

        color: #bfdbfe;

        text-decoration: none;

        font-size: 11px;

        font-weight: 800;
    }


    .admin-create-user-back:hover {
        color: white;
    }


    .admin-create-user-chip {
        display: inline-flex;

        align-items: center;

        gap: 7px;

        margin-bottom: 13px;

        padding:
            6px 10px;

        border:
            1px solid
            rgba(
                255,
                255,
                255,
                0.15
            );

        border-radius: 999px;

        color: #dbeafe;

        background:
            rgba(
                255,
                255,
                255,
                0.08
            );

        font-size: 9px;

        font-weight: 900;

        text-transform: uppercase;
    }


    .admin-create-user-header h1 {
        margin: 0;

        color: white;

        font-size:
            clamp(
                2rem,
                4vw,
                3rem
            );

        font-weight: 900;

        letter-spacing:
            -0.045em;
    }


    .admin-create-user-header p {
        max-width: 720px;

        margin:
            10px 0 0;

        color: #cbd5e1;

        font-size: 12px;

        line-height: 1.75;
    }


    /*
    |--------------------------------------------------------------------------
    | CONTENT
    |--------------------------------------------------------------------------
    */

    .admin-create-layout {
        display: grid;

        grid-template-columns:
            minmax(
                0,
                1.5fr
            )
            minmax(
                260px,
                0.7fr
            );

        gap: 20px;

        align-items: start;
    }


    .admin-create-panel {
        overflow: hidden;

        border:
            1px solid
            rgba(
                255,
                255,
                255,
                0.9
            );

        border-radius: 20px;

        background:
            rgba(
                255,
                255,
                255,
                0.96
            );

        box-shadow:
            var(--ac-shadow);
    }


    .admin-create-panel-header {
        padding:
            19px 22px;

        border-bottom:
            1px solid
            #e2e8f0;

        background: #f8fafc;
    }


    .admin-create-panel-title {
        margin: 0;

        display: flex;

        align-items: center;

        gap: 9px;

        color: #0f172a;

        font-size: 15px;

        font-weight: 900;
    }


    .admin-create-panel-title i {
        color: #2563eb;
    }


    .admin-create-panel-body {
        padding: 22px;
    }


    /*
    |--------------------------------------------------------------------------
    | FORM
    |--------------------------------------------------------------------------
    */

    .admin-create-form-grid {
        display: grid;

        grid-template-columns:
            repeat(
                2,
                minmax(
                    0,
                    1fr
                )
            );

        gap: 17px;
    }


    .admin-create-form-group {
        min-width: 0;
    }


    .admin-create-form-group.full {
        grid-column:
            1 / -1;
    }


    .admin-create-label {
        display: block;

        margin-bottom: 7px;

        color: #334155;

        font-size: 10px;

        font-weight: 900;
    }


    .admin-create-required {
        color: #dc2626;
    }


    .admin-create-control {
        width: 100%;

        min-height: 45px;

        padding:
            10px 12px;

        border:
            1px solid
            #cbd5e1;

        border-radius: 11px;

        color: #0f172a;

        background: white;

        font-size: 12px;

        outline: none;

        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease;
    }


    .admin-create-control:focus {
        border-color: #6366f1;

        box-shadow:
            0 0 0 3px
            rgba(
                99,
                102,
                241,
                0.10
            );
    }


    .admin-create-control.is-invalid {
        border-color: #ef4444;
    }


    .admin-create-help {
        margin-top: 6px;

        color: #64748b;

        font-size: 9px;

        line-height: 1.6;
    }


    .admin-create-actions {
        display: flex;

        justify-content: flex-end;

        gap: 10px;

        margin-top: 23px;

        padding-top: 20px;

        border-top:
            1px solid
            #e2e8f0;
    }


    .admin-create-cancel,
    .admin-create-submit {
        min-height: 44px;

        padding:
            10px 17px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 7px;

        border-radius: 11px;

        font-size: 11px;

        font-weight: 900;

        text-decoration: none;
    }


    .admin-create-cancel {
        border:
            1px solid
            #cbd5e1;

        color: #475569;

        background: white;
    }


    .admin-create-submit {
        border: none;

        color: white;

        background:
            linear-gradient(
                135deg,
                #2563eb,
                #4f46e5
            );

        cursor: pointer;
    }


    .admin-create-submit:disabled {
        opacity: 0.65;

        cursor: not-allowed;
    }


    /*
    |--------------------------------------------------------------------------
    | RULES
    |--------------------------------------------------------------------------
    */

    .admin-workforce-rule {
        display: flex;

        gap: 10px;

        padding: 12px 0;

        border-bottom:
            1px solid
            #edf2f7;
    }


    .admin-workforce-rule:last-child {
        border-bottom: none;
    }


    .admin-workforce-rule-icon {
        width: 34px;
        height: 34px;

        flex: 0 0 auto;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 10px;

        color: #2563eb;

        background: #eff6ff;
    }


    .admin-workforce-rule-title {
        color: #0f172a;

        font-size: 10px;

        font-weight: 900;
    }


    .admin-workforce-rule-text {
        margin-top: 3px;

        color: #64748b;

        font-size: 9px;

        line-height: 1.6;
    }


    .admin-workforce-note {
        margin-top: 15px;

        padding: 13px;

        border:
            1px solid
            #bae6fd;

        border-radius: 12px;

        color: #075985;

        background: #f0f9ff;

        font-size: 10px;

        line-height: 1.7;
    }


    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 900px) {
        .admin-create-layout {
            grid-template-columns: 1fr;
        }
    }


    @media (max-width: 650px) {
        .admin-create-form-grid {
            grid-template-columns: 1fr;
        }


        .admin-create-form-group.full {
            grid-column: auto;
        }


        .admin-create-actions {
            flex-direction: column-reverse;
        }


        .admin-create-cancel,
        .admin-create-submit {
            width: 100%;
        }
    }
</style>

@endpush


@section('content')

<div class="container admin-create-user-page">

    {{-- =====================================================
        HEADER
    ====================================================== --}}

    <section class="admin-create-user-header">

        <div class="admin-create-user-header-content">

            <a
                href="{{ route('admin.users.index') }}"
                class="admin-create-user-back"
            >

                <i class="bi bi-arrow-left"></i>

                Quay lại quản lý tài khoản

            </a>


            <div class="admin-create-user-chip">

                <i class="bi bi-person-plus-fill"></i>

                Quản lý nhân sự

            </div>


            <h1>
                Tạo tài khoản nhân sự
            </h1>


            <p>
                Chủ xưởng tạo tài khoản riêng cho
                STAFF hoặc TECHNICIAN.
                Chức năng này không tạo CUSTOMER
                và không tạo hồ sơ phương tiện.
            </p>

        </div>

    </section>


    <div class="admin-create-layout">

        {{-- =================================================
            FORM
        ================================================== --}}

        <section class="admin-create-panel">

            <div class="admin-create-panel-header">

                <h2 class="admin-create-panel-title">

                    <i class="bi bi-person-vcard"></i>

                    Thông tin tài khoản

                </h2>

            </div>


            <div class="admin-create-panel-body">

                <form
                    method="POST"
                    action="{{ route('admin.users.store') }}"
                    data-create-workforce-form
                    novalidate
                >

                    @csrf


                    <div class="admin-create-form-grid">

                        {{-- NAME --}}

                        <div class="admin-create-form-group full">

                            <label
                                for="name"
                                class="admin-create-label"
                            >

                                Họ và tên

                                <span class="admin-create-required">
                                    *
                                </span>

                            </label>


                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="
                                    admin-create-control
                                    {{
                                        $errors->has('name')
                                            ? 'is-invalid'
                                            : ''
                                    }}
                                "
                                maxlength="100"
                                autocomplete="name"
                                placeholder="Ví dụ: Nguyễn Văn Kỹ Thuật"
                                required
                            >

                        </div>


                        {{-- EMAIL --}}

                        <div class="admin-create-form-group full">

                            <label
                                for="email"
                                class="admin-create-label"
                            >

                                Email đăng nhập

                                <span class="admin-create-required">
                                    *
                                </span>

                            </label>


                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="
                                    admin-create-control
                                    {{
                                        $errors->has('email')
                                            ? 'is-invalid'
                                            : ''
                                    }}
                                "
                                maxlength="254"
                                autocomplete="off"
                                placeholder="nhanvien@autocare.vn"
                                required
                            >


                            <div class="admin-create-help">
                                Email phải chưa tồn tại
                                trong hệ thống.
                            </div>

                        </div>


                        {{-- ROLE --}}

                        <div class="admin-create-form-group full">

                            <label
                                for="role"
                                class="admin-create-label"
                            >

                                Vai trò nhân sự

                                <span class="admin-create-required">
                                    *
                                </span>

                            </label>


                            <select
                                id="role"
                                name="role"
                                class="
                                    admin-create-control
                                    {{
                                        $errors->has('role')
                                            ? 'is-invalid'
                                            : ''
                                    }}
                                "
                                required
                            >

                                <option value="">
                                    -- Chọn vai trò --
                                </option>


                                @foreach (
                                    $workforceRoles
                                    as $role
                                )

                                    <option
                                        value="{{ $role->code }}"
                                        @selected(
                                            old('role')
                                            ===
                                            $role->code
                                        )
                                    >

                                        {{ $role->name }}
                                        ({{ $role->code }})

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- PASSWORD --}}

                        <div class="admin-create-form-group">

                            <label
                                for="password"
                                class="admin-create-label"
                            >

                                Mật khẩu

                                <span class="admin-create-required">
                                    *
                                </span>

                            </label>


                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="
                                    admin-create-control
                                    {{
                                        $errors->has('password')
                                            ? 'is-invalid'
                                            : ''
                                    }}
                                "
                                maxlength="72"
                                autocomplete="new-password"
                                placeholder="Ít nhất 8 ký tự"
                                required
                            >


                            <div class="admin-create-help">
                                Tối thiểu 8 ký tự,
                                có ít nhất một chữ cái
                                và một chữ số.
                            </div>

                        </div>


                        {{-- CONFIRM PASSWORD --}}

                        <div class="admin-create-form-group">

                            <label
                                for="password_confirmation"
                                class="admin-create-label"
                            >

                                Xác nhận mật khẩu

                                <span class="admin-create-required">
                                    *
                                </span>

                            </label>


                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                class="admin-create-control"
                                maxlength="72"
                                autocomplete="new-password"
                                placeholder="Nhập lại mật khẩu"
                                required
                            >

                        </div>

                    </div>


                    <div class="admin-create-actions">

                        <a
                            href="{{ route('admin.users.index') }}"
                            class="admin-create-cancel"
                        >

                            <i class="bi bi-x-lg"></i>

                            Hủy

                        </a>


                        <button
                            type="submit"
                            class="admin-create-submit"
                            data-create-workforce-submit
                        >

                            <i class="bi bi-person-plus-fill"></i>

                            Tạo tài khoản

                        </button>

                    </div>

                </form>

            </div>

        </section>


        {{-- =================================================
            BUSINESS RULES
        ================================================== --}}

        <aside class="admin-create-panel">

            <div class="admin-create-panel-header">

                <h2 class="admin-create-panel-title">

                    <i class="bi bi-shield-check"></i>

                    Quy tắc tài khoản

                </h2>

            </div>


            <div class="admin-create-panel-body">

                <div class="admin-workforce-rule">

                    <div class="admin-workforce-rule-icon">
                        <i class="bi bi-person-workspace"></i>
                    </div>


                    <div>

                        <div class="admin-workforce-rule-title">
                            STAFF
                        </div>

                        <div class="admin-workforce-rule-text">
                            Nhân viên tiếp nhận,
                            vận hành lịch hẹn,
                            phiếu bảo dưỡng,
                            hóa đơn và kho.
                        </div>

                    </div>

                </div>


                <div class="admin-workforce-rule">

                    <div class="admin-workforce-rule-icon">
                        <i class="bi bi-tools"></i>
                    </div>


                    <div>

                        <div class="admin-workforce-rule-title">
                            TECHNICIAN
                        </div>

                        <div class="admin-workforce-rule-text">
                            Kỹ thuật viên nhận và
                            thực hiện các phiếu
                            được phân công.
                        </div>

                    </div>

                </div>


                <div class="admin-workforce-rule">

                    <div class="admin-workforce-rule-icon">
                        <i class="bi bi-person-lock"></i>
                    </div>


                    <div>

                        <div class="admin-workforce-rule-title">
                            Không tạo CUSTOMER
                        </div>

                        <div class="admin-workforce-rule-text">
                            CUSTOMER đăng ký
                            theo luồng khách hàng riêng
                            và được gắn với hồ sơ xe.
                        </div>

                    </div>

                </div>


                <div class="admin-workforce-rule">

                    <div class="admin-workforce-rule-icon">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>


                    <div>

                        <div class="admin-workforce-rule-title">
                            Không tạo ADMIN
                        </div>

                        <div class="admin-workforce-rule-text">
                            Quyền chủ xưởng
                            không được cấp từ
                            chức năng quản lý nhân sự.
                        </div>

                    </div>

                </div>

            </div>

        </aside>

    </div>

</div>

@endsection


@push('scripts')

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function () {
            const form =
                document.querySelector(
                    '[data-create-workforce-form]'
                );


            if (!form) {
                return;
            }


            form.addEventListener(
                'submit',
                function () {
                    const button =
                        form.querySelector(
                            '[data-create-workforce-submit]'
                        );


                    if (!button) {
                        return;
                    }


                    button.disabled =
                        true;


                    button.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2"></span>Đang tạo...';
                }
            );
        }
    );
</script>

@endpush