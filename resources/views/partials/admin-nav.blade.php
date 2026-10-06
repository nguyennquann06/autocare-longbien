@php
    $adminNavUser =
        Auth::user();


    if (
        $adminNavUser
        &&
        !$adminNavUser
            ->relationLoaded('role')
    ) {
        $adminNavUser
            ->load('role');
    }
@endphp


<style>
    .admin-owner-navbar {
        position: sticky;

        top: 0;

        z-index: 1050;

        color: white;

        background:
            linear-gradient(
                115deg,
                #050b16 0%,
                #102554 45%,
                #312e81 75%,
                #4c1d95 100%
            );

        border-bottom:
            1px solid
            rgba(255, 255, 255, 0.11);

        box-shadow:
            0 12px 35px
            rgba(2, 12, 27, 0.30);
    }


    .admin-owner-navbar-inner {
        position: relative;

        width:
            min(
                1480px,
                calc(100% - 36px)
            );

        min-height: 72px;

        margin: 0 auto;

        display: flex;

        align-items: center;

        gap: 18px;
    }


    .admin-owner-brand {
        display: inline-flex;

        align-items: center;

        gap: 11px;

        color: white;

        text-decoration: none;

        white-space: nowrap;
    }


    .admin-owner-brand:hover {
        color: white;
    }


    .admin-owner-logo {
        width: 46px;
        height: 46px;

        flex: 0 0 auto;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        border-radius: 14px;

        color: white;

        font-size: 15px;

        font-weight: 900;

        font-style: italic;

        background:
            linear-gradient(
                135deg,
                #22d3ee,
                #2563eb 48%,
                #7c3aed
            );

        border:
            1px solid
            rgba(255, 255, 255, 0.25);

        box-shadow:
            0 9px 25px
            rgba(37, 99, 235, 0.38);
    }


    .admin-owner-brand-content {
        line-height: 1.1;
    }


    .admin-owner-brand-name {
        display: block;

        color: white;

        font-size: 17px;

        font-weight: 900;

        letter-spacing: -0.3px;
    }


    .admin-owner-brand-subtitle {
        display: block;

        margin-top: 4px;

        color: #c4b5fd;

        font-size: 8px;

        font-weight: 800;

        text-transform: uppercase;

        letter-spacing: 0.14em;
    }


    /*
    |--------------------------------------------------------------------------
    | MENU
    |--------------------------------------------------------------------------
    */

    .admin-owner-menu {
        flex: 1;

        display: flex;

        align-items: center;

        justify-content: flex-end;

        gap: 9px;

        min-width: 0;
    }


    .admin-owner-links {
        display: flex;

        align-items: center;

        gap: 4px;

        padding: 4px;

        border:
            1px solid
            rgba(255, 255, 255, 0.08);

        border-radius: 14px;

        background:
            rgba(255, 255, 255, 0.045);
    }


    .admin-owner-link {
        min-height: 39px;

        padding:
            9px 10px;

        display: inline-flex;

        align-items: center;

        gap: 6px;

        border-radius: 10px;

        color: #ddd6fe;

        text-decoration: none;

        white-space: nowrap;

        font-size: 10px;

        font-weight: 750;

        transition:
            all 0.2s ease;
    }


    .admin-owner-link:hover {
        color: white;

        background:
            rgba(255, 255, 255, 0.10);

        transform:
            translateY(-1px);
    }


    .admin-owner-link.active {
        color: white;

        background:
            linear-gradient(
                135deg,
                rgba(37, 99, 235, 0.60),
                rgba(124, 58, 237, 0.62)
            );

        box-shadow:
            inset 0 0 0 1px
            rgba(255, 255, 255, 0.12),

            0 7px 20px
            rgba(37, 99, 235, 0.20);
    }


    .admin-owner-link i {
        color: #67e8f9;

        font-size: 12px;
    }


    /*
    |--------------------------------------------------------------------------
    | ACCOUNT
    |--------------------------------------------------------------------------
    */

    .admin-owner-account {
        display: flex;

        align-items: center;

        gap: 9px;

        padding: 5px;

        border:
            1px solid
            rgba(255, 255, 255, 0.10);

        border-radius: 14px;

        background:
            rgba(255, 255, 255, 0.055);
    }


    .admin-owner-user {
        display: flex;

        align-items: center;

        gap: 8px;

        padding-left: 6px;
    }


    .admin-owner-avatar {
        width: 37px;
        height: 37px;

        flex: 0 0 auto;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 50%;

        color: #4338ca;

        background:
            linear-gradient(
                135deg,
                #ffffff,
                #e0e7ff
            );

        font-size: 12px;

        font-weight: 900;

        border:
            2px solid
            rgba(255, 255, 255, 0.25);
    }


    .admin-owner-user-name {
        display: block;

        max-width: 130px;

        overflow: hidden;

        text-overflow: ellipsis;

        white-space: nowrap;

        color: white;

        font-size: 11px;

        font-weight: 800;
    }


    .admin-owner-user-role {
        display: block;

        margin-top: 2px;

        color: #c4b5fd;

        font-size: 8px;

        font-weight: 800;

        text-transform: uppercase;

        letter-spacing: 0.06em;
    }


    .admin-owner-logout-button {
        min-height: 37px;

        padding:
            8px 11px;

        border:
            1px solid
            rgba(248, 113, 113, 0.34);

        border-radius: 10px;

        color: #fee2e2;

        background:
            rgba(239, 68, 68, 0.11);

        cursor: pointer;

        font-size: 10px;

        font-weight: 800;
    }


    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 1250px) {
        .admin-owner-navbar-inner {
            flex-wrap: wrap;

            padding:
                10px 0;
        }


        .admin-owner-menu {
            width: 100%;

            flex-basis: 100%;

            justify-content:
                space-between;
        }


        .admin-owner-links {
            overflow-x: auto;

            scrollbar-width: thin;
        }
    }


    @media (max-width: 767px) {
        .admin-owner-navbar-inner {
            width:
                calc(
                    100% - 24px
                );
        }


        .admin-owner-brand-subtitle,
        .admin-owner-user-name,
        .admin-owner-user-role {
            display: none;
        }


        .admin-owner-account {
            flex-shrink: 0;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT MODAL
    |--------------------------------------------------------------------------
    */

    .admin-logout-modal-overlay {
        position: fixed;

        inset: 0;

        z-index: 99999;

        display: none;

        align-items: center;

        justify-content: center;

        padding: 20px;

        background:
            rgba(2, 6, 23, 0.74);

        backdrop-filter:
            blur(8px);
    }


    .admin-logout-modal-overlay.show {
        display: flex;
    }


    .admin-logout-modal {
        width: 100%;

        max-width: 450px;

        overflow: hidden;

        border:
            1px solid
            rgba(255, 255, 255, 0.14);

        border-radius: 23px;

        color: white;

        background:
            linear-gradient(
                160deg,
                #0f172a,
                #1e1b4b
            );

        box-shadow:
            0 30px 100px
            rgba(0, 0, 0, 0.50);
    }


    .admin-logout-modal-body {
        padding: 27px;
    }


    .admin-logout-modal-icon {
        width: 48px;
        height: 48px;

        display: flex;

        align-items: center;

        justify-content: center;

        margin-bottom: 17px;

        border-radius: 15px;

        color: #fca5a5;

        background:
            rgba(239, 68, 68, 0.14);

        font-size: 21px;
    }


    .admin-logout-modal-title {
        margin: 0;

        color: white;

        font-size: 21px;

        font-weight: 900;
    }


    .admin-logout-modal-description {
        margin:
            9px 0 0;

        color: #cbd5e1;

        font-size: 12px;

        line-height: 1.7;
    }


    .admin-logout-modal-user {
        display: flex;

        align-items: center;

        gap: 10px;

        margin-top: 20px;

        padding: 13px;

        border:
            1px solid
            rgba(255, 255, 255, 0.09);

        border-radius: 14px;

        background:
            rgba(255, 255, 255, 0.05);
    }


    .admin-logout-modal-actions {
        display: grid;

        grid-template-columns:
            1fr 1fr;

        gap: 10px;

        margin-top: 21px;
    }


    .admin-logout-cancel,
    .admin-logout-submit {
        min-height: 43px;

        border-radius: 11px;

        font-size: 11px;

        font-weight: 850;

        cursor: pointer;
    }


    .admin-logout-cancel {
        border:
            1px solid
            rgba(255, 255, 255, 0.15);

        color: white;

        background:
            rgba(255, 255, 255, 0.07);
    }


    .admin-logout-submit {
        width: 100%;

        border:
            1px solid
            rgba(248, 113, 113, 0.35);

        color: white;

        background:
            linear-gradient(
                135deg,
                #dc2626,
                #b91c1c
            );
    }


    @media (max-width: 480px) {
        .admin-logout-modal-actions {
            grid-template-columns: 1fr;
        }
    }
</style>


<header class="admin-owner-navbar">

    <div class="admin-owner-navbar-inner">

        <a
            href="{{ route('admin.dashboard') }}"
            class="admin-owner-brand"
        >

            <span class="admin-owner-logo">
                AC
            </span>


            <span class="admin-owner-brand-content">

                <span class="admin-owner-brand-name">
                    AutoCare Long Biên
                </span>

                <span class="admin-owner-brand-subtitle">
                    Garage Owner Control Center
                </span>

            </span>

        </a>


        <nav class="admin-owner-menu">

            <div class="admin-owner-links">

                {{-- DASHBOARD --}}

                <a
                    href="{{ route('admin.dashboard') }}"
                    class="
                        admin-owner-link
                        {{
                            request()->routeIs(
                                'admin.dashboard'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >

                    <i class="bi bi-speedometer2"></i>

                    Tổng quan

                </a>


                {{-- USER MANAGEMENT --}}

                <a
                    href="{{ route('admin.users.index') }}"
                    class="
                        admin-owner-link
                        {{
                            request()->routeIs(
                                'admin.users.*'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >

                    <i class="bi bi-people-fill"></i>

                    Tài khoản

                </a>


                {{-- STAFF OPERATIONS --}}

                <a
                    href="{{ route('staff.dashboard') }}"
                    class="
                        admin-owner-link
                        {{
                            request()->routeIs(
                                'staff.dashboard'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >

                    <i class="bi bi-grid-1x2-fill"></i>

                    Vận hành

                </a>


                {{-- MAINTENANCE --}}

                <a
                    href="{{ route('staff.appointments.index') }}"
                    class="
                        admin-owner-link
                        {{
                            request()->routeIs(
                                'staff.appointments.*',
                                'staff.service-orders.*',
                                'staff.invoices.*'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >

                    <i class="bi bi-calendar-check"></i>

                    Bảo dưỡng

                </a>


                {{-- INVENTORY --}}

                <a
                    href="{{ route('staff.parts.index') }}"
                    class="
                        admin-owner-link
                        {{
                            request()->routeIs(
                                'staff.parts.*'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >

                    <i class="bi bi-box-seam"></i>

                    Kho phụ tùng

                </a>


                {{-- SERVICES --}}

                <a
                    href="{{ route('services.index') }}"
                    class="
                        admin-owner-link
                        {{
                            request()->routeIs(
                                'services.*'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >

                    <i class="bi bi-wrench-adjustable"></i>

                    Dịch vụ

                </a>

            </div>


            <div class="admin-owner-account">

                <div class="admin-owner-user">

                    <div class="admin-owner-avatar">

                        {{
                            mb_strtoupper(
                                mb_substr(
                                    $adminNavUser->name,
                                    0,
                                    1
                                )
                            )
                        }}

                    </div>


                    <div>

                        <span class="admin-owner-user-name">
                            {{ $adminNavUser->name }}
                        </span>

                        <span class="admin-owner-user-role">
                            Chủ xưởng / Admin
                        </span>

                    </div>

                </div>


                <button
                    type="button"
                    class="admin-owner-logout-button"
                    onclick="openLogoutModal()"
                >
                    Đăng xuất
                </button>

            </div>

        </nav>

    </div>

</header>


{{-- =====================================================
    LOGOUT MODAL
====================================================== --}}

<div
    id="logoutModal"
    class="admin-logout-modal-overlay"
    onclick="handleLogoutOverlayClick(event)"
>

    <div
        class="admin-logout-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="adminLogoutModalTitle"
    >

        <div class="admin-logout-modal-body">

            <div class="admin-logout-modal-icon">

                <i class="bi bi-box-arrow-right"></i>

            </div>


            <h2
                id="adminLogoutModalTitle"
                class="admin-logout-modal-title"
            >
                Xác nhận đăng xuất
            </h2>


            <p class="admin-logout-modal-description">

                Bạn đang đăng nhập với quyền
                Chủ xưởng / Quản trị viên.

                Xác nhận kết thúc phiên
                làm việc hiện tại.

            </p>


            <div class="admin-logout-modal-user">

                <div class="admin-owner-avatar">

                    {{
                        mb_strtoupper(
                            mb_substr(
                                $adminNavUser->name,
                                0,
                                1
                            )
                        )
                    }}

                </div>


                <div>

                    <span class="admin-owner-user-name">
                        {{ $adminNavUser->name }}
                    </span>

                    <span class="admin-owner-user-role">
                        Chủ xưởng / Admin
                    </span>

                </div>

            </div>


            <div class="admin-logout-modal-actions">

                <button
                    type="button"
                    class="admin-logout-cancel"
                    onclick="closeLogoutModal()"
                >
                    Tiếp tục làm việc
                </button>


                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    style="margin:0;"
                >

                    @csrf


                    <button
                        type="submit"
                        class="admin-logout-submit"
                    >
                        Đăng xuất ngay
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


<script>
    function openLogoutModal() {
        const modal =
            document.getElementById(
                'logoutModal'
            );


        if (!modal) {
            return;
        }


        modal.classList.add(
            'show'
        );


        document.body.style.overflow =
            'hidden';
    }


    function closeLogoutModal() {
        const modal =
            document.getElementById(
                'logoutModal'
            );


        if (!modal) {
            return;
        }


        modal.classList.remove(
            'show'
        );


        document.body.style.overflow =
            '';
    }


    function handleLogoutOverlayClick(
        event
    ) {
        if (
            event.target.id
            === 'logoutModal'
        ) {
            closeLogoutModal();
        }
    }


    document.addEventListener(
        'keydown',
        function (event) {
            if (
                event.key
                === 'Escape'
            ) {
                closeLogoutModal();
            }
        }
    );
</script>