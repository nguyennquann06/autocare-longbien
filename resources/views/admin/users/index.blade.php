@extends('layouts.app')


@section(
    'title',
    'Quản lý tài khoản - AutoCare Long Biên'
)


@push('styles')

<style>
    .admin-users-page {
        width: 100%;
        max-width: 1420px;

        margin: 0 auto;

        padding-bottom: 50px;
    }


    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

    .admin-users-header {
        position: relative;

        overflow: hidden;

        margin-bottom: 22px;

        padding: 32px;

        border-radius: 25px;

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


    .admin-users-header::after {
        content: "";

        position: absolute;

        width: 330px;
        height: 330px;

        right: -100px;
        top: -190px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(
                    103,
                    232,
                    249,
                    0.28
                ),
                transparent 68%
            );
    }


    .admin-users-header-content {
        position: relative;

        z-index: 2;
    }


    .admin-users-chip {
        display: inline-flex;

        align-items: center;

        gap: 7px;

        margin-bottom: 14px;

        padding:
            7px 11px;

        border:
            1px solid
            rgba(
                255,
                255,
                255,
                0.16
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

        letter-spacing: 0.08em;
    }


    .admin-users-header h1 {
        margin: 0;

        color: white;

        font-size:
            clamp(
                2rem,
                4vw,
                3.2rem
            );

        font-weight: 900;

        letter-spacing: -0.05em;
    }


    .admin-users-header p {
        max-width: 760px;

        margin:
            10px 0 0;

        color: #cbd5e1;

        font-size: 13px;

        line-height: 1.75;
    }


    /*
    |--------------------------------------------------------------------------
    | SUMMARY
    |--------------------------------------------------------------------------
    */

    .admin-users-summary {
        display: grid;

        grid-template-columns:
            repeat(
                5,
                minmax(
                    0,
                    1fr
                )
            );

        gap: 12px;

        margin-bottom: 20px;
    }


    .admin-user-summary-card {
        display: block;

        padding: 17px;

        border:
            1px solid
            rgba(
                255,
                255,
                255,
                0.88
            );

        border-radius: 17px;

        color: inherit;

        background:
            rgba(
                255,
                255,
                255,
                0.94
            );

        box-shadow:
            0 15px 38px
            rgba(
                15,
                23,
                42,
                0.07
            );

        text-decoration: none;

        transition:
            transform 0.2s ease,
            border-color 0.2s ease,
            box-shadow 0.2s ease;
    }


    .admin-user-summary-card:hover {
        color: inherit;

        transform:
            translateY(-3px);

        border-color:
            #a5b4fc;

        box-shadow:
            0 20px 48px
            rgba(
                79,
                70,
                229,
                0.13
            );
    }


    .admin-user-summary-card.active {
        border-color:
            #6366f1;

        background:
            linear-gradient(
                135deg,
                #eef2ff,
                #ffffff
            );

        box-shadow:
            0 0 0 2px
            rgba(
                99,
                102,
                241,
                0.09
            );
    }


    .admin-user-summary-label {
        color: #64748b;

        font-size: 9px;

        font-weight: 900;

        text-transform: uppercase;

        letter-spacing: 0.05em;
    }


    .admin-user-summary-value {
        margin-top: 4px;

        color: #0f172a;

        font-size: 23px;

        font-weight: 900;
    }


    /*
    |--------------------------------------------------------------------------
    | PANEL
    |--------------------------------------------------------------------------
    */

    .admin-users-panel {
        overflow: hidden;

        border:
            1px solid
            rgba(
                255,
                255,
                255,
                0.88
            );

        border-radius: 21px;

        background:
            rgba(
                255,
                255,
                255,
                0.95
            );

        box-shadow:
            var(--ac-shadow);

        backdrop-filter:
            blur(16px);
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER
    |--------------------------------------------------------------------------
    */

    .admin-users-filter {
        padding: 20px;

        border-bottom:
            1px solid
            #e2e8f0;

        background: #f8fafc;
    }


    .admin-users-filter-form {
        display: grid;

        grid-template-columns:
            minmax(
                220px,
                1fr
            )
            230px
            auto
            auto;

        gap: 10px;

        align-items: end;
    }


    .admin-filter-group label {
        display: block;

        margin-bottom: 6px;

        color: #475569;

        font-size: 9px;

        font-weight: 900;

        text-transform: uppercase;

        letter-spacing: 0.05em;
    }


    .admin-filter-control {
        width: 100%;

        min-height: 43px;

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


    .admin-filter-control:focus {
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


    .admin-filter-button,
    .admin-filter-reset {
        min-height: 43px;

        padding:
            9px 15px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 7px;

        border-radius: 11px;

        font-size: 11px;

        font-weight: 850;

        text-decoration: none;
    }


    .admin-filter-button {
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


    .admin-filter-reset {
        border:
            1px solid
            #cbd5e1;

        color: #475569;

        background: white;
    }


    .admin-filter-reset:hover {
        color: #0f172a;

        background: #f1f5f9;
    }


    /*
    |--------------------------------------------------------------------------
    | RESULT
    |--------------------------------------------------------------------------
    */

    .admin-users-result {
        display: flex;

        align-items: center;

        justify-content:
            space-between;

        gap: 15px;

        padding:
            17px 20px;

        border-bottom:
            1px solid
            #edf2f7;
    }


    .admin-users-result-title {
        color: #0f172a;

        font-size: 14px;

        font-weight: 900;
    }


    .admin-users-result-meta {
        margin-top: 3px;

        color: #64748b;

        font-size: 10px;
    }


    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    .admin-users-table-wrapper {
        overflow-x: auto;
    }


    .admin-users-table {
        width: 100%;

        border-collapse: collapse;
    }


    .admin-users-table th,
    .admin-users-table td {
        padding:
            14px 16px;

        border-bottom:
            1px solid
            #edf2f7;

        text-align: left;

        vertical-align: middle;
    }


    .admin-users-table th {
        color: #64748b;

        background: #f8fafc;

        font-size: 9px;

        font-weight: 900;

        text-transform: uppercase;

        letter-spacing: 0.05em;
    }


    .admin-users-table td {
        color: #475569;

        font-size: 11px;
    }


    .admin-users-table tbody tr {
        transition:
            background 0.18s ease;
    }


    .admin-users-table tbody tr:hover {
        background:
            rgba(
                248,
                250,
                252,
                0.75
            );
    }


    .admin-users-table tbody tr.current-user-row {
        background:
            rgba(
                245,
                243,
                255,
                0.70
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ACCOUNT
    |--------------------------------------------------------------------------
    */

    .admin-user-main {
        display: flex;

        align-items: center;

        gap: 10px;

        min-width: 210px;
    }


    .admin-user-avatar {
        width: 40px;
        height: 40px;

        flex: 0 0 auto;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 50%;

        color: #4338ca;

        background:
            linear-gradient(
                135deg,
                #eef2ff,
                #dbeafe
            );

        font-size: 12px;

        font-weight: 900;
    }


    .admin-user-name {
        color: #0f172a;

        font-size: 11px;

        font-weight: 900;
    }


    .admin-user-email {
        margin-top: 3px;

        color: #64748b;

        font-size: 10px;
    }


    .admin-current-badge {
        display: inline-flex;

        margin-left: 5px;

        padding:
            3px 6px;

        border-radius: 999px;

        color: #7c3aed;

        background: #f3e8ff;

        font-size: 7px;

        font-weight: 900;

        text-transform: uppercase;
    }


    /*
    |--------------------------------------------------------------------------
    | ROLE
    |--------------------------------------------------------------------------
    */

    .admin-role-badge {
        display: inline-flex;

        align-items: center;

        gap: 5px;

        padding:
            5px 9px;

        border-radius: 999px;

        font-size: 8px;

        font-weight: 900;

        white-space: nowrap;
    }


    .admin-role-customer {
        color: #0369a1;

        background: #e0f2fe;
    }


    .admin-role-staff {
        color: #047857;

        background: #d1fae5;
    }


    .admin-role-technician {
        color: #b45309;

        background: #fef3c7;
    }


    .admin-role-admin {
        color: #6d28d9;

        background: #ede9fe;
    }


    .admin-role-unknown {
        color: #475569;

        background: #e2e8f0;
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS BADGES
    |--------------------------------------------------------------------------
    */

    .admin-google-badge,
    .admin-profile-badge {
        display: inline-flex;

        align-items: center;

        gap: 5px;

        padding:
            5px 8px;

        border-radius: 999px;

        font-size: 8px;

        font-weight: 850;

        white-space: nowrap;
    }


    .admin-google-badge {
        color: #166534;

        background: #dcfce7;
    }


    .admin-google-none {
        color: #64748b;

        background: #f1f5f9;
    }


    .admin-profile-yes {
        color: #166534;

        background: #dcfce7;
    }


    .admin-profile-none {
        color: #64748b;

        background: #f1f5f9;
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIONS
    |--------------------------------------------------------------------------
    */

    .admin-user-actions {
        min-width: 185px;

        display: flex;

        align-items: center;

        gap: 7px;
    }


    .admin-user-action {
        min-height: 34px;

        padding:
            7px 10px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 6px;

        border-radius: 9px;

        text-decoration: none;

        font-size: 9px;

        font-weight: 850;

        white-space: nowrap;

        transition:
            transform 0.18s ease,
            background 0.18s ease;
    }


    .admin-user-action:hover {
        transform:
            translateY(-1px);
    }


    .admin-user-action-view {
        border:
            1px solid
            #bfdbfe;

        color: #1d4ed8;

        background: #eff6ff;
    }


    .admin-user-action-view:hover {
        color: #1e40af;

        background: #dbeafe;
    }


    .admin-user-action-role {
        border:
            1px solid
            #ddd6fe;

        color: #6d28d9;

        background: #f5f3ff;
    }


    .admin-user-action-role:hover {
        color: #5b21b6;

        background: #ede9fe;
    }


    .admin-protected-badge {
        display: inline-flex;

        align-items: center;

        gap: 5px;

        min-height: 34px;

        padding:
            7px 10px;

        border:
            1px solid
            #ddd6fe;

        border-radius: 9px;

        color: #6d28d9;

        background: #f5f3ff;

        font-size: 8px;

        font-weight: 850;

        white-space: nowrap;
    }


    /*
    |--------------------------------------------------------------------------
    | EMPTY
    |--------------------------------------------------------------------------
    */

    .admin-users-empty {
        padding:
            42px 20px !important;

        color:
            #64748b !important;

        text-align:
            center !important;
    }


    .admin-users-empty-icon {
        margin-bottom: 10px;

        color: #94a3b8;

        font-size: 26px;
    }


    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */

    .admin-users-pagination {
        padding:
            18px 20px;
    }


    .admin-users-pagination nav {
        margin: 0;
    }


    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 1199px) {
        .admin-users-summary {
            grid-template-columns:
                repeat(
                    3,
                    1fr
                );
        }


        .admin-users-filter-form {
            grid-template-columns:
                1fr
                220px;
        }
    }


    @media (max-width: 767px) {
        .admin-users-summary {
            grid-template-columns:
                repeat(
                    2,
                    1fr
                );
        }


        .admin-users-filter-form {
            grid-template-columns:
                1fr;
        }
    }


    @media (max-width: 575px) {
        .admin-users-summary {
            grid-template-columns: 1fr;
        }


        .admin-users-header {
            padding: 24px;
        }
    }
</style>

@endpush


@section('content')

<div class="container admin-users-page">

    {{-- =====================================================
        HEADER
    ====================================================== --}}

    <section class="admin-users-header">

        <div class="admin-users-header-content">

            <div class="admin-users-chip">

                <i class="bi bi-people-fill"></i>

                Quản trị tài khoản & phân quyền

            </div>


            <h1>
                Tài khoản hệ thống
            </h1>


            <p>
                Theo dõi tài khoản, kiểm tra vai trò
                và quản lý quyền truy cập của CUSTOMER,
                STAFF và TECHNICIAN trong hệ thống
                AutoCare Long Biên.
            </p>

        </div>

    </section>


    {{-- =====================================================
        SUMMARY / QUICK FILTER
    ====================================================== --}}

    <section class="admin-users-summary">

        <a
            href="{{ route('admin.users.index') }}"
            class="
                admin-user-summary-card
                {{
                    $roleCode === ''
                    ? 'active'
                    : ''
                }}
            "
        >

            <div class="admin-user-summary-label">
                Tất cả
            </div>

            <div class="admin-user-summary-value">
                {{ $totalUsers }}
            </div>

        </a>


        @foreach (
            $roles
            as $role
        )

            <a
                href="{{ route(
                    'admin.users.index',
                    [
                        'role' =>
                            $role->code,
                    ]
                ) }}"
                class="
                    admin-user-summary-card
                    {{
                        $roleCode
                        ===
                        strtoupper(
                            $role->code
                        )
                            ? 'active'
                            : ''
                    }}
                "
            >

                <div class="admin-user-summary-label">
                    {{ $role->code }}
                </div>

                <div class="admin-user-summary-value">

                    {{
                        $roleCounts[
                            $role->code
                        ]
                        ?? 0
                    }}

                </div>

            </a>

        @endforeach

    </section>


    {{-- =====================================================
        USER MANAGEMENT
    ====================================================== --}}

    <section class="admin-users-panel">

        {{-- FILTER --}}

        <div class="admin-users-filter">

            <form
                method="GET"
                action="{{ route('admin.users.index') }}"
                class="admin-users-filter-form"
            >

                <div class="admin-filter-group">

                    <label for="admin-user-search">
                        Tìm kiếm tài khoản
                    </label>


                    <input
                        id="admin-user-search"
                        type="search"
                        name="q"
                        value="{{ $keyword }}"
                        class="admin-filter-control"
                        placeholder="Tên, email hoặc ID..."
                        maxlength="120"
                        autocomplete="off"
                    >

                </div>


                <div class="admin-filter-group">

                    <label for="admin-role-filter">
                        Vai trò
                    </label>


                    <select
                        id="admin-role-filter"
                        name="role"
                        class="admin-filter-control"
                    >

                        <option value="">
                            Tất cả vai trò
                        </option>


                        @foreach (
                            $roles
                            as $role
                        )

                            <option
                                value="{{ $role->code }}"
                                @selected(
                                    $roleCode
                                    ===
                                    strtoupper(
                                        $role->code
                                    )
                                )
                            >

                                {{ $role->name }}

                                ({{ $role->code }})

                            </option>

                        @endforeach

                    </select>

                </div>


                <button
                    type="submit"
                    class="admin-filter-button"
                >

                    <i class="bi bi-search"></i>

                    Lọc

                </button>


                <a
                    href="{{ route('admin.users.index') }}"
                    class="admin-filter-reset"
                >

                    <i class="bi bi-arrow-counterclockwise"></i>

                    Đặt lại

                </a>

            </form>

        </div>


        {{-- RESULT --}}

        <div class="admin-users-result">

            <div>

                <div class="admin-users-result-title">
                    Danh sách tài khoản
                </div>


                <div class="admin-users-result-meta">

                    Hiển thị

                    <strong>
                        {{ $users->count() }}
                    </strong>

                    /

                    <strong>
                        {{ $users->total() }}
                    </strong>

                    kết quả.

                    @if ($keyword !== '')

                        Từ khóa:

                        <strong>
                            "{{ $keyword }}"
                        </strong>

                    @endif


                    @if ($roleCode !== '')

                        · Vai trò:

                        <strong>
                            {{ $roleCode }}
                        </strong>

                    @endif

                </div>

            </div>

        </div>


        {{-- TABLE --}}

        <div class="admin-users-table-wrapper">

            <table class="admin-users-table">

                <thead>

                    <tr>

                        <th>
                            ID
                        </th>

                        <th>
                            Tài khoản
                        </th>

                        <th>
                            Vai trò
                        </th>

                        <th>
                            Google
                        </th>

                        <th>
                            Hồ sơ khách
                        </th>

                        <th>
                            Ngày tạo
                        </th>

                        <th>
                            Thao tác
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse (
                        $users
                        as $user
                    )

                        @php
                            $userRoleCode =
                                $user
                                    ->role
                                    ?->code;


                            $roleClass =
                                match (
                                    $userRoleCode
                                ) {
                                    'CUSTOMER' =>
                                        'admin-role-customer',

                                    'STAFF' =>
                                        'admin-role-staff',

                                    'TECHNICIAN' =>
                                        'admin-role-technician',

                                    'ADMIN' =>
                                        'admin-role-admin',

                                    default =>
                                        'admin-role-unknown',
                                };


                            $roleIcon =
                                match (
                                    $userRoleCode
                                ) {
                                    'CUSTOMER' =>
                                        'bi-person-fill',

                                    'STAFF' =>
                                        'bi-person-workspace',

                                    'TECHNICIAN' =>
                                        'bi-tools',

                                    'ADMIN' =>
                                        'bi-shield-lock-fill',

                                    default =>
                                        'bi-question-circle',
                                };


                            $isCurrentUser =
                                auth()->id()
                                ===
                                $user->id;
                        @endphp


                        <tr
                            class="{{
                                $isCurrentUser
                                    ? 'current-user-row'
                                    : ''
                            }}"
                        >

                            {{-- ID --}}

                            <td>
                                #{{ $user->id }}
                            </td>


                            {{-- ACCOUNT --}}

                            <td>

                                <div class="admin-user-main">

                                    <div class="admin-user-avatar">

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

                                        <div class="admin-user-name">

                                            {{ $user->name }}


                                            @if ($isCurrentUser)

                                                <span class="admin-current-badge">
                                                    Tài khoản của bạn
                                                </span>

                                            @endif

                                        </div>


                                        <div class="admin-user-email">
                                            {{ $user->email }}
                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- ROLE --}}

                            <td>

                                <span
                                    class="
                                        admin-role-badge
                                        {{ $roleClass }}
                                    "
                                >

                                    <i
                                        class="
                                            bi
                                            {{ $roleIcon }}
                                        "
                                    ></i>

                                    {{
                                        $userRoleCode
                                        ?? 'CHƯA GÁN'
                                    }}

                                </span>

                            </td>


                            {{-- GOOGLE --}}

                            <td>

                                @if ($user->google_id)

                                    <span class="admin-google-badge">

                                        <i class="bi bi-google"></i>

                                        Đã liên kết

                                    </span>

                                @else

                                    <span
                                        class="
                                            admin-google-badge
                                            admin-google-none
                                        "
                                    >

                                        Chưa liên kết

                                    </span>

                                @endif

                            </td>


                            {{-- CUSTOMER PROFILE --}}

                            <td>

                                @if ($user->customer)

                                    <span
                                        class="
                                            admin-profile-badge
                                            admin-profile-yes
                                        "
                                    >

                                        <i class="bi bi-check-lg"></i>

                                        Có hồ sơ

                                    </span>

                                @else

                                    <span
                                        class="
                                            admin-profile-badge
                                            admin-profile-none
                                        "
                                    >

                                        Không có

                                    </span>

                                @endif

                            </td>


                            {{-- CREATED AT --}}

                            <td>

                                {{
                                    $user
                                        ->created_at
                                        ?->format(
                                            'd/m/Y H:i'
                                        )
                                    ?? '—'
                                }}

                            </td>


                            {{-- ACTIONS --}}

                            <td>

                                <div class="admin-user-actions">

                                    <a
                                        href="{{ route(
                                            'admin.users.show',
                                            $user->id
                                        ) }}"
                                        class="
                                            admin-user-action
                                            admin-user-action-view
                                        "
                                    >

                                        <i class="bi bi-eye"></i>

                                        Chi tiết

                                    </a>


                                    @if (
                                        $userRoleCode
                                        !== 'ADMIN'
                                    )

                                        <a
                                            href="{{
                                                route(
                                                    'admin.users.show',
                                                    $user->id
                                                )
                                            }}#role-management"
                                            class="
                                                admin-user-action
                                                admin-user-action-role
                                            "
                                        >

                                            <i class="bi bi-shield-check"></i>

                                            Phân quyền

                                        </a>

                                    @else

                                        <span class="admin-protected-badge">

                                            <i class="bi bi-lock-fill"></i>

                                            Bảo vệ

                                        </span>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="admin-users-empty"
                            >

                                <div class="admin-users-empty-icon">

                                    <i class="bi bi-search"></i>

                                </div>


                                <strong>
                                    Không tìm thấy tài khoản phù hợp.
                                </strong>

                                <br>

                                Hãy thử thay đổi từ khóa
                                hoặc bộ lọc vai trò.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}

        @if (
            $users
                ->hasPages()
        )

            <div class="admin-users-pagination">

                {{
                    $users->links()
                }}

            </div>

        @endif

    </section>

</div>

@endsection