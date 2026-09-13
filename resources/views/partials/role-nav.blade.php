@php
    $currentUser = Auth::user();

    if (
        $currentUser
        && !$currentUser->relationLoaded('role')
    ) {
        $currentUser->load('role');
    }

    $roleCode =
        $currentUser?->role?->code;


    $roleLabel = match ($roleCode) {
        'CUSTOMER' =>
            'Khách hàng',

        'STAFF' =>
            'Nhân viên',

        'ADMIN' =>
            'Quản trị viên',

        'TECHNICIAN' =>
            'Kỹ thuật viên',

        default =>
            $roleCode ?? 'Tài khoản',
    };
@endphp


<style>
    :root {
        --nav-dark: #07111f;
        --nav-dark-2: #0b1630;
        --nav-blue: #0d6efd;
        --nav-blue-light: #38bdf8;
        --nav-cyan: #22d3ee;
        --nav-purple: #7c3aed;

        --nav-text: #e2e8f0;
        --nav-muted: #94a3b8;

        --nav-border:
            rgba(255, 255, 255, 0.10);

        --nav-glass:
            rgba(255, 255, 255, 0.07);
    }


    /* =====================================================
       NAVBAR WRAPPER
       ===================================================== */

    .app-navbar {
        position: sticky;
        top: 0;

        z-index: 1050;

        color: white;

        background:
            linear-gradient(
                115deg,
                #07111f 0%,
                #0f1d40 38%,
                #0b3a88 72%,
                #0756c9 100%
            );

        box-shadow:
            0 12px 35px
            rgba(2, 12, 27, 0.28);

        border-bottom:
            1px solid
            rgba(255, 255, 255, 0.10);

        overflow: visible;
    }


    .app-navbar::before {
        content: "";

        position: absolute;

        width: 430px;
        height: 430px;

        top: -330px;
        left: 12%;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(34, 211, 238, 0.20),
                transparent 68%
            );

        pointer-events: none;
    }


    .app-navbar::after {
        content: "";

        position: absolute;

        width: 380px;
        height: 380px;

        right: 5%;
        top: -300px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(124, 58, 237, 0.17),
                transparent 68%
            );

        pointer-events: none;
    }


    .app-navbar-inner {
        position: relative;

        z-index: 2;

        max-width: 1480px;

        margin: 0 auto;

        padding: 11px 24px;

        display: flex;

        align-items: center;

        gap: 18px;
    }


    /* =====================================================
       BRAND
       ===================================================== */

    .app-navbar-brand {
        display: inline-flex;

        align-items: center;

        gap: 12px;

        text-decoration: none;

        color: white;

        white-space: nowrap;
    }


    .app-navbar-brand:hover {
        color: white;
    }


    .brand-logo {
        position: relative;

        width: 46px;
        height: 46px;

        flex: 0 0 auto;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        border-radius: 14px;

        color: white;

        font-size: 16px;

        font-weight: 900;

        font-style: italic;

        letter-spacing: -1px;

        background:
            linear-gradient(
                135deg,
                #38bdf8,
                #0d6efd 60%,
                #6d28d9
            );

        border:
            1px solid
            rgba(255, 255, 255, 0.25);

        box-shadow:
            0 0 0 4px
            rgba(56, 189, 248, 0.08),

            0 9px 24px
            rgba(13, 110, 253, 0.45);

        transition:
            transform 0.25s ease,
            box-shadow 0.25s ease;
    }


    .brand-logo::after {
        content: "";

        position: absolute;

        width: 14px;
        height: 5px;

        top: 6px;
        right: 6px;

        border-radius: 50%;

        background:
            rgba(255, 255, 255, 0.65);

        filter:
            blur(2px);
    }


    .app-navbar-brand:hover .brand-logo {
        transform:
            rotate(-4deg)
            scale(1.06);

        box-shadow:
            0 0 0 5px
            rgba(56, 189, 248, 0.11),

            0 12px 30px
            rgba(13, 110, 253, 0.55);
    }


    .brand-content {
        line-height: 1.12;
    }


    .brand-name {
        display: block;

        color: white;

        font-size: 18px;

        font-weight: 850;

        letter-spacing: -0.4px;

        text-shadow:
            0 2px 10px
            rgba(0, 0, 0, 0.18);
    }


    .brand-subtitle {
        display: block;

        margin-top: 4px;

        color: #bfdbfe;

        font-size: 9px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: 0.16em;
    }


    /* =====================================================
       TOGGLE
       ===================================================== */

    .app-navbar-toggle-input {
        display: none;
    }


    .app-navbar-toggle {
        display: none;

        width: 43px;
        height: 43px;

        margin-left: auto;

        align-items: center;

        justify-content: center;

        border:
            1px solid
            rgba(255, 255, 255, 0.16);

        border-radius: 12px;

        background:
            rgba(255, 255, 255, 0.08);

        backdrop-filter:
            blur(10px);

        color: white;

        cursor: pointer;

        font-size: 23px;
    }


    /* =====================================================
       MENU
       ===================================================== */

    .app-navbar-menu {
        flex: 1;

        display: flex;

        align-items: center;

        justify-content: flex-end;

        gap: 10px;

        min-width: 0;
    }


    .app-nav-links {
        display: flex;

        align-items: center;

        gap: 4px;

        padding: 4px;

        border:
            1px solid
            rgba(255, 255, 255, 0.08);

        border-radius: 14px;

        background:
            rgba(255, 255, 255, 0.035);

        backdrop-filter:
            blur(12px);
    }


    .app-nav-link {
        position: relative;

        min-height: 39px;

        padding: 9px 11px;

        display: inline-flex;

        align-items: center;

        gap: 7px;

        border-radius: 10px;

        color: #dbeafe;

        text-decoration: none;

        font-size: 12px;

        font-weight: 650;

        white-space: nowrap;

        transition:
            all 0.22s ease;
    }


    .app-nav-link:hover {
        color: white;

        background:
            rgba(255, 255, 255, 0.09);

        transform:
            translateY(-1px);
    }


    .app-nav-link.active {
        color: white;

        background:
            linear-gradient(
                135deg,
                rgba(14, 165, 233, 0.38),
                rgba(37, 99, 235, 0.52)
            );

        box-shadow:
            0 6px 18px
            rgba(13, 110, 253, 0.20),

            inset 0 0 0 1px
            rgba(125, 211, 252, 0.20);
    }


    .nav-dot {
        position: relative;

        width: 7px;
        height: 7px;

        flex: 0 0 auto;

        border-radius: 50%;

        background: #60a5fa;

        box-shadow:
            0 0 8px
            rgba(96, 165, 250, 0.75);
    }


    .app-nav-link.active .nav-dot {
        background: #67e8f9;

        box-shadow:
            0 0 12px
            rgba(103, 232, 249, 1);
    }


    /* =====================================================
       GUEST AUTH ACTIONS
       ===================================================== */

    .guest-auth-links {
        display: flex;

        align-items: center;

        gap: 9px;

        padding: 4px;
    }


    .guest-login-link,
    .guest-register-link {
        min-height: 42px;

        padding:
            9px 17px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 8px;

        border-radius: 11px;

        text-decoration: none;

        white-space: nowrap;

        font-size: 12px;

        font-weight: 800;

        transition:
            transform 0.22s ease,
            background 0.22s ease,
            border-color 0.22s ease,
            box-shadow 0.22s ease;
    }


    .guest-login-link {
        color: #e0f2fe;

        border:
            1px solid
            rgba(186, 230, 253, 0.25);

        background:
            rgba(255, 255, 255, 0.065);

        backdrop-filter:
            blur(12px);
    }


    .guest-login-link:hover {
        color: white;

        border-color:
            rgba(125, 211, 252, 0.50);

        background:
            rgba(255, 255, 255, 0.12);

        transform:
            translateY(-2px);

        box-shadow:
            0 8px 20px
            rgba(2, 132, 199, 0.15);
    }


    .guest-register-link {
        color: #07111f;

        border:
            1px solid
            rgba(255, 255, 255, 0.38);

        background:
            linear-gradient(
                135deg,
                #67e8f9,
                #bfdbfe 55%,
                #ffffff
            );

        box-shadow:
            0 8px 24px
            rgba(56, 189, 248, 0.24),

            inset 0 1px 0
            rgba(255, 255, 255, 0.70);
    }


    .guest-register-link:hover {
        color: #07111f;

        transform:
            translateY(-2px);

        box-shadow:
            0 12px 30px
            rgba(56, 189, 248, 0.34),

            inset 0 1px 0
            rgba(255, 255, 255, 0.75);
    }


    .guest-login-link i,
    .guest-register-link i {
        font-size: 14px;
    }


    /* =====================================================
       AUTOCARE AI NAV ITEM
       ===================================================== */

    .app-nav-link.ai-nav-link {
        color: #ecfeff;

        border:
            1px solid
            rgba(103, 232, 249, 0.13);

        background:
            linear-gradient(
                135deg,
                rgba(34, 211, 238, 0.08),
                rgba(124, 58, 237, 0.11)
            );
    }


    .app-nav-link.ai-nav-link:hover {
        color: white;

        border-color:
            rgba(103, 232, 249, 0.32);

        background:
            linear-gradient(
                135deg,
                rgba(34, 211, 238, 0.19),
                rgba(99, 102, 241, 0.24)
            );

        box-shadow:
            0 8px 22px
            rgba(34, 211, 238, 0.12);
    }


    .app-nav-link.ai-nav-link.active {
        color: white;

        border-color:
            rgba(165, 243, 252, 0.40);

        background:
            linear-gradient(
                135deg,
                #0891b2,
                #2563eb 52%,
                #6d28d9
            );

        box-shadow:
            0 8px 24px
            rgba(37, 99, 235, 0.32),

            inset 0 0 0 1px
            rgba(255, 255, 255, 0.12);
    }


    .ai-nav-icon {
        color: #67e8f9;

        font-size: 13px;

        filter:
            drop-shadow(
                0 0 6px
                rgba(103, 232, 249, 0.75)
            );
    }


    .app-nav-link.ai-nav-link.active
    .ai-nav-icon {
        color: white;
    }


    .ai-nav-badge {
        display: inline-flex;

        align-items: center;

        justify-content: center;

        padding: 2px 5px;

        border-radius: 999px;

        color: #cffafe;

        background:
            rgba(34, 211, 238, 0.14);

        font-size: 7px;

        font-weight: 900;

        letter-spacing: 0.04em;

        text-transform: uppercase;
    }


    .app-nav-link.ai-nav-link.active
    .ai-nav-badge {
        color: white;

        background:
            rgba(255, 255, 255, 0.15);
    }


    /* =====================================================
       ACCOUNT
       ===================================================== */

    .app-navbar-account {
        display: flex;

        align-items: center;

        gap: 10px;

        margin-left: 6px;

        padding: 5px 5px 5px 10px;

        border:
            1px solid
            rgba(255, 255, 255, 0.10);

        border-radius: 14px;

        background:
            rgba(255, 255, 255, 0.055);

        backdrop-filter:
            blur(12px);
    }


    .app-navbar-user {
        display: flex;

        align-items: center;

        gap: 9px;

        min-width: 0;
    }


    .user-avatar {
        width: 39px;
        height: 39px;

        flex: 0 0 auto;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 50%;

        color: #1d4ed8;

        background:
            linear-gradient(
                135deg,
                white,
                #dbeafe
            );

        font-size: 13px;

        font-weight: 900;

        text-transform: uppercase;

        border:
            2px solid
            rgba(255, 255, 255, 0.30);

        box-shadow:
            0 5px 16px
            rgba(0, 0, 0, 0.20);
    }


    .user-information {
        min-width: 0;

        line-height: 1.2;
    }


    .user-name {
        display: block;

        max-width: 145px;

        overflow: hidden;

        text-overflow: ellipsis;

        white-space: nowrap;

        color: white;

        font-size: 12px;

        font-weight: 750;
    }


    .user-role {
        display: block;

        margin-top: 3px;

        color: #93c5fd;

        font-size: 9px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: 0.07em;
    }


    /* =====================================================
       LOGOUT BUTTON
       ===================================================== */

    .logout-open-button {
        min-height: 38px;

        padding: 8px 13px;

        border:
            1px solid
            rgba(248, 113, 113, 0.35);

        border-radius: 10px;

        background:
            linear-gradient(
                135deg,
                rgba(239, 68, 68, 0.14),
                rgba(220, 38, 38, 0.07)
            );

        color: #fee2e2;

        cursor: pointer;

        font-size: 12px;

        font-weight: 750;

        transition:
            all 0.22s ease;
    }


    .logout-open-button:hover {
        color: white;

        border-color:
            rgba(248, 113, 113, 0.65);

        background:
            linear-gradient(
                135deg,
                rgba(239, 68, 68, 0.35),
                rgba(220, 38, 38, 0.20)
            );

        box-shadow:
            0 7px 18px
            rgba(239, 68, 68, 0.18);

        transform:
            translateY(-1px);
    }


    /* =====================================================
       CUSTOMER AI FLOATING LAUNCHER
       ===================================================== */

    .autocare-ai-launcher {
        position: fixed;

        right: 24px;
        bottom: 24px;

        z-index: 1040;

        display: inline-flex;

        align-items: center;

        gap: 11px;

        min-height: 58px;

        padding:
            9px 17px 9px 9px;

        overflow: hidden;

        border:
            1px solid
            rgba(255, 255, 255, 0.30);

        border-radius: 999px;

        color: white;

        text-decoration: none;

        background:
            linear-gradient(
                135deg,
                #0891b2 0%,
                #2563eb 48%,
                #6d28d9 100%
            );

        box-shadow:
            0 17px 42px
            rgba(37, 99, 235, 0.32),

            0 0 0 5px
            rgba(59, 130, 246, 0.07);

        transition:
            transform 0.25s ease,
            box-shadow 0.25s ease;

        isolation: isolate;
    }


    .autocare-ai-launcher::before {
        content: "";

        position: absolute;

        width: 90px;
        height: 90px;

        top: -60px;
        right: 15px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(255, 255, 255, 0.34),
                transparent 67%
            );

        pointer-events: none;

        z-index: -1;
    }


    .autocare-ai-launcher:hover {
        color: white;

        transform:
            translateY(-4px)
            scale(1.02);

        box-shadow:
            0 23px 52px
            rgba(37, 99, 235, 0.42),

            0 0 0 7px
            rgba(34, 211, 238, 0.08);
    }


    .autocare-ai-launcher-icon {
        position: relative;

        width: 42px;
        height: 42px;

        flex: 0 0 auto;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 50%;

        color: white;

        background:
            rgba(255, 255, 255, 0.15);

        border:
            1px solid
            rgba(255, 255, 255, 0.18);

        font-size: 19px;

        box-shadow:
            inset 0 0 18px
            rgba(255, 255, 255, 0.08);
    }


    .autocare-ai-launcher-icon::after {
        content: "";

        position: absolute;

        width: 9px;
        height: 9px;

        right: 0;
        bottom: 1px;

        border-radius: 50%;

        background: #4ade80;

        border: 2px solid #2563eb;

        box-shadow:
            0 0 9px
            rgba(74, 222, 128, 0.85);
    }


    .autocare-ai-launcher-content {
        display: flex;

        flex-direction: column;

        line-height: 1.08;
    }


    .autocare-ai-launcher-label {
        color: white;

        font-size: 12px;

        font-weight: 900;

        white-space: nowrap;
    }


    .autocare-ai-launcher-subtitle {
        margin-top: 4px;

        color: #cffafe;

        font-size: 8px;

        font-weight: 700;

        white-space: nowrap;
    }


    /* =====================================================
       LOGOUT MODAL
       ===================================================== */

    .autocare-modal-overlay {
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

        animation:
            autocareFadeIn
            0.22s ease;
    }


    .autocare-modal-overlay.show {
        display: flex;
    }


    .autocare-modal {
        position: relative;

        width: 100%;

        max-width: 460px;

        overflow: hidden;

        border:
            1px solid
            rgba(255, 255, 255, 0.15);

        border-radius: 24px;

        background:
            linear-gradient(
                160deg,
                rgba(15, 23, 42, 0.98),
                rgba(15, 38, 78, 0.98)
            );

        color: white;

        box-shadow:
            0 30px 100px
            rgba(0, 0, 0, 0.55);

        animation:
            autocareModalIn
            0.28s cubic-bezier(
                0.2,
                0.8,
                0.2,
                1
            );
    }


    .autocare-modal::before {
        content: "";

        position: absolute;

        width: 240px;
        height: 240px;

        right: -100px;
        top: -120px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(56, 189, 248, 0.30),
                transparent 70%
            );

        pointer-events: none;
    }


    .autocare-modal::after {
        content: "";

        position: absolute;

        width: 220px;
        height: 220px;

        left: -110px;
        bottom: -140px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(124, 58, 237, 0.26),
                transparent 70%
            );

        pointer-events: none;
    }


    .autocare-modal-content {
        position: relative;

        z-index: 2;

        padding: 32px;
    }


    .logout-modal-icon {
        width: 68px;
        height: 68px;

        margin-bottom: 22px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 20px;

        color: white;

        font-size: 30px;

        background:
            linear-gradient(
                135deg,
                #ef4444,
                #dc2626
            );

        box-shadow:
            0 13px 32px
            rgba(239, 68, 68, 0.32);
    }


    .logout-modal-title {
        margin:
            0 0 10px;

        color: white;

        font-size: 25px;

        font-weight: 850;

        letter-spacing: -0.4px;
    }


    .logout-modal-description {
        margin: 0;

        color: #cbd5e1;

        font-size: 14px;

        line-height: 1.7;
    }


    .logout-modal-user {
        display: flex;

        align-items: center;

        gap: 12px;

        margin-top: 24px;

        padding: 14px;

        border:
            1px solid
            rgba(255, 255, 255, 0.09);

        border-radius: 14px;

        background:
            rgba(255, 255, 255, 0.055);
    }


    .logout-modal-user-avatar {
        width: 42px;
        height: 42px;

        display: flex;

        align-items: center;

        justify-content: center;

        flex: 0 0 auto;

        border-radius: 50%;

        background:
            linear-gradient(
                135deg,
                #38bdf8,
                #2563eb
            );

        color: white;

        font-weight: 900;
    }


    .logout-modal-user-name {
        color: white;

        font-size: 14px;

        font-weight: 750;
    }


    .logout-modal-user-role {
        margin-top: 3px;

        color: #93c5fd;

        font-size: 11px;
    }


    .logout-modal-actions {
        display: grid;

        grid-template-columns:
            1fr 1fr;

        gap: 12px;

        margin-top: 28px;
    }


    .logout-modal-cancel,
    .logout-modal-submit {
        min-height: 46px;

        border-radius: 12px;

        font-size: 14px;

        font-weight: 750;

        cursor: pointer;

        transition:
            all 0.2s ease;
    }


    .logout-modal-cancel {
        border:
            1px solid
            rgba(255, 255, 255, 0.14);

        color: #e2e8f0;

        background:
            rgba(255, 255, 255, 0.065);
    }


    .logout-modal-cancel:hover {
        color: white;

        background:
            rgba(255, 255, 255, 0.12);
    }


    .logout-modal-submit {
        border:
            1px solid
            rgba(248, 113, 113, 0.35);

        color: white;

        background:
            linear-gradient(
                135deg,
                #ef4444,
                #b91c1c
            );

        box-shadow:
            0 8px 20px
            rgba(239, 68, 68, 0.25);
    }


    .logout-modal-submit:hover {
        transform:
            translateY(-1px);

        box-shadow:
            0 12px 28px
            rgba(239, 68, 68, 0.35);
    }


    @keyframes autocareFadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }


    @keyframes autocareModalIn {
        from {
            opacity: 0;

            transform:
                translateY(18px)
                scale(0.96);
        }

        to {
            opacity: 1;

            transform:
                translateY(0)
                scale(1);
        }
    }


    /* =====================================================
       RESPONSIVE
       ===================================================== */

    @media (max-width: 1280px) {

        .app-navbar-inner {
            flex-wrap: wrap;
        }


        .app-navbar-toggle {
            display: inline-flex;
        }


        .app-navbar-menu {
            display: none;

            width: 100%;

            flex-basis: 100%;

            flex-direction: column;

            align-items: stretch;

            padding-top: 12px;

            border-top:
                1px solid
                rgba(255, 255, 255, 0.10);
        }


        .app-navbar-toggle-input:checked
        ~ .app-navbar-menu {
            display: flex;
        }


        .app-nav-links {
            width: 100%;

            flex-direction: column;

            align-items: stretch;
        }


        .app-nav-link {
            width: 100%;

            min-height: 44px;

            font-size: 13px;
        }


        .guest-auth-links {
            width: 100%;

            flex-direction: column;

            align-items: stretch;

            padding: 0;
        }


        .guest-login-link,
        .guest-register-link {
            width: 100%;

            min-height: 46px;
        }


        .app-navbar-account {
            width: 100%;

            margin-left: 0;

            padding: 12px;

            justify-content:
                space-between;
        }

    }


    @media (max-width: 575px) {

        .app-navbar-inner {
            padding:
                10px 14px;
        }


        .brand-logo {
            width: 40px;
            height: 40px;
        }


        .brand-name {
            font-size: 16px;
        }


        .brand-subtitle {
            font-size: 8px;
        }


        .app-navbar-account {
            align-items: center;
        }


        .user-name {
            max-width: 135px;
        }


        .autocare-modal-content {
            padding: 25px;
        }


        .logout-modal-actions {
            grid-template-columns:
                1fr;
        }


        .autocare-ai-launcher {
            right: 15px;
            bottom: 15px;

            width: 54px;
            height: 54px;

            min-height: 54px;

            padding: 5px;

            justify-content: center;
        }


        .autocare-ai-launcher-icon {
            width: 42px;
            height: 42px;
        }


        .autocare-ai-launcher-content {
            display: none;
        }

    }
</style>


<header class="app-navbar">

    <div class="app-navbar-inner">

        <a
            href="{{ route('home') }}"
            class="app-navbar-brand"
        >

            <span class="brand-logo">
                AC
            </span>


            <span class="brand-content">

                <span class="brand-name">
                    AutoCare Long Biên
                </span>

                <span class="brand-subtitle">
                    Car Service System
                </span>

            </span>

        </a>


        <input
            type="checkbox"
            id="app-navbar-toggle"
            class="app-navbar-toggle-input"
        >


        <label
            for="app-navbar-toggle"
            class="app-navbar-toggle"
            aria-label="Mở menu"
        >
            ☰
        </label>


        <nav class="app-navbar-menu">

            {{-- =====================================================
                AUTHENTICATED NAVIGATION
            ====================================================== --}}
            @auth

                <div class="app-nav-links">

                    {{-- CUSTOMER --}}
                    @if ($roleCode === 'CUSTOMER')

                        <a
                            href="{{ route(
                                'customer.dashboard'
                            ) }}"
                            class="
                                app-nav-link
                                {{
                                    request()->routeIs(
                                        'customer.dashboard'
                                    )
                                        ? 'active'
                                        : ''
                                }}
                            "
                        >
                            <span class="nav-dot"></span>
                            Tổng quan
                        </a>


                        <a
                            href="{{ route(
                                'vehicles.index'
                            ) }}"
                            class="
                                app-nav-link
                                {{
                                    request()->routeIs(
                                        'vehicles.*'
                                    )
                                        ? 'active'
                                        : ''
                                }}
                            "
                        >
                            <span class="nav-dot"></span>
                            Xe của tôi
                        </a>


                        <a
                            href="{{ route(
                                'appointments.create'
                            ) }}"
                            class="
                                app-nav-link
                                {{
                                    request()->routeIs(
                                        'appointments.create'
                                    )
                                        ? 'active'
                                        : ''
                                }}
                            "
                        >
                            <span class="nav-dot"></span>
                            Đặt lịch
                        </a>


                        <a
                            href="{{ route(
                                'appointments.index'
                            ) }}"
                            class="
                                app-nav-link
                                {{
                                    request()->routeIs(
                                        'appointments.index',
                                        'appointments.show'
                                    )
                                        ? 'active'
                                        : ''
                                }}
                            "
                        >
                            <span class="nav-dot"></span>
                            Lịch hẹn
                        </a>


                        <a
                            href="{{ route(
                                'maintenance-history.index'
                            ) }}"
                            class="
                                app-nav-link
                                {{
                                    request()->routeIs(
                                        'maintenance-history.*'
                                    )
                                        ? 'active'
                                        : ''
                                }}
                            "
                        >
                            <span class="nav-dot"></span>
                            Lịch sử bảo dưỡng
                        </a>


                        <a
                            href="{{ route(
                                'customer.invoices.index'
                            ) }}"
                            class="
                                app-nav-link
                                {{
                                    request()->routeIs(
                                        'customer.invoices.*'
                                    )
                                        ? 'active'
                                        : ''
                                }}
                            "
                        >
                            <span class="nav-dot"></span>
                            Hóa đơn
                        </a>


                        <a
                            href="{{ route(
                                'chat.index'
                            ) }}"
                            class="
                                app-nav-link
                                ai-nav-link
                                {{
                                    request()->routeIs(
                                        'chat.*'
                                    )
                                        ? 'active'
                                        : ''
                                }}
                            "
                        >

                            <i
                                class="
                                    bi
                                    bi-stars
                                    ai-nav-icon
                                "
                            ></i>

                            AutoCare AI

                            <span class="ai-nav-badge">
                                AI
                            </span>

                        </a>


                    {{-- STAFF / ADMIN --}}
                    @elseif (
                        in_array(
                            $roleCode,
                            [
                                'STAFF',
                                'ADMIN',
                            ],
                            true
                        )
                    )

                        <a
                            href="{{ route(
                                'staff.dashboard'
                            ) }}"
                            class="
                                app-nav-link
                                {{
                                    request()->routeIs(
                                        'staff.dashboard'
                                    )
                                        ? 'active'
                                        : ''
                                }}
                            "
                        >
                            <span class="nav-dot"></span>
                            Dashboard
                        </a>


                        <a
                            href="{{ route(
                                'staff.appointments.index'
                            ) }}"
                            class="
                                app-nav-link
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
                            <span class="nav-dot"></span>
                            Lịch hẹn
                        </a>


                        <a
                            href="{{ route(
                                'staff.parts.index'
                            ) }}"
                            class="
                                app-nav-link
                                {{
                                    request()->routeIs(
                                        'staff.parts.*'
                                    )
                                        ? 'active'
                                        : ''
                                }}
                            "
                        >
                            <span class="nav-dot"></span>
                            Kho phụ tùng
                        </a>


                        <a
                            href="{{ route(
                                'services.index'
                            ) }}"
                            class="
                                app-nav-link
                                {{
                                    request()->routeIs(
                                        'services.*'
                                    )
                                        ? 'active'
                                        : ''
                                }}
                            "
                        >
                            <span class="nav-dot"></span>
                            Dịch vụ
                        </a>


                    {{-- TECHNICIAN --}}
                    @elseif (
                        $roleCode === 'TECHNICIAN'
                    )

                        <a
                            href="{{ route(
                                'technician.service-orders.index'
                            ) }}"
                            class="
                                app-nav-link
                                {{
                                    request()->routeIs(
                                        'technician.service-orders.*'
                                    )
                                        ? 'active'
                                        : ''
                                }}
                            "
                        >
                            <span class="nav-dot"></span>
                            Công việc của tôi
                        </a>


                        <a
                            href="{{ route('home') }}"
                            class="
                                app-nav-link
                                {{
                                    request()->routeIs(
                                        'home'
                                    )
                                        ? 'active'
                                        : ''
                                }}
                            "
                        >
                            <span class="nav-dot"></span>
                            Trang chủ
                        </a>

                    @endif

                </div>


                <div class="app-navbar-account">

                    <div class="app-navbar-user">

                        <div class="user-avatar">

                            {{
                                mb_strtoupper(
                                    mb_substr(
                                        $currentUser->name,
                                        0,
                                        1
                                    )
                                )
                            }}

                        </div>


                        <div class="user-information">

                            <span class="user-name">
                                {{ $currentUser->name }}
                            </span>

                            <span class="user-role">
                                {{ $roleLabel }}
                            </span>

                        </div>

                    </div>


                    <button
                        type="button"
                        class="logout-open-button"
                        onclick="openLogoutModal()"
                    >
                        Đăng xuất
                    </button>

                </div>

            @endauth


            {{-- =====================================================
                GUEST ACTIONS
                Chỉ hiển thị tại URL /
            ====================================================== --}}
            @guest

                @if (
                    request()->routeIs(
                        'home'
                    )
                )

                    <div class="guest-auth-links">

                        <a
                            href="{{ route('login') }}"
                            class="guest-login-link"
                        >

                            <i
                                class="
                                    bi
                                    bi-box-arrow-in-right
                                "
                            ></i>

                            Đăng nhập

                        </a>


                        <a
                            href="{{ route('register') }}"
                            class="guest-register-link"
                        >

                            <i
                                class="
                                    bi
                                    bi-person-plus-fill
                                "
                            ></i>

                            Đăng ký

                        </a>

                    </div>

                @endif

            @endguest

        </nav>

    </div>

</header>


{{-- =====================================================
    CUSTOMER AI FLOATING LAUNCHER
===================================================== --}}
@if (
    $roleCode === 'CUSTOMER'
    &&
    !request()->routeIs('chat.*')
)

    <a
        href="{{ route('chat.index') }}"
        class="autocare-ai-launcher"
        title="Mở AutoCare AI"
        aria-label="Mở trợ lý AutoCare AI"
    >

        <span class="autocare-ai-launcher-icon">

            <i class="bi bi-robot"></i>

        </span>


        <span class="autocare-ai-launcher-content">

            <span class="autocare-ai-launcher-label">
                AutoCare AI
            </span>

            <span class="autocare-ai-launcher-subtitle">
                Hỏi trợ lý bảo dưỡng
            </span>

        </span>

    </a>

@endif


{{-- =====================================================
    CUSTOM LOGOUT MODAL
===================================================== --}}
@auth

    <div
        id="logoutModal"
        class="autocare-modal-overlay"
        onclick="handleLogoutOverlayClick(event)"
    >

        <div
            class="autocare-modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="logoutModalTitle"
        >

            <div class="autocare-modal-content">

                <div class="logout-modal-icon">
                    ↪
                </div>


                <h2
                    id="logoutModalTitle"
                    class="logout-modal-title"
                >
                    Xác nhận đăng xuất
                </h2>


                <p class="logout-modal-description">

                    Bạn sắp rời khỏi phiên làm việc
                    hiện tại.

                    Các thao tác chưa lưu trên trang
                    có thể bị mất.

                </p>


                <div class="logout-modal-user">

                    <div class="logout-modal-user-avatar">

                        {{
                            mb_strtoupper(
                                mb_substr(
                                    $currentUser->name,
                                    0,
                                    1
                                )
                            )
                        }}

                    </div>


                    <div>

                        <div class="logout-modal-user-name">
                            {{ $currentUser->name }}
                        </div>

                        <div class="logout-modal-user-role">
                            {{ $roleLabel }}
                        </div>

                    </div>

                </div>


                <div class="logout-modal-actions">

                    <button
                        type="button"
                        class="logout-modal-cancel"
                        onclick="closeLogoutModal()"
                    >
                        Tiếp tục làm việc
                    </button>


                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                        style="margin: 0;"
                    >

                        @csrf


                        <button
                            type="submit"
                            class="logout-modal-submit"
                            style="width: 100%;"
                        >
                            Đăng xuất ngay
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

@endauth


<script>
    function openLogoutModal() {
        const modal =
            document.getElementById(
                'logoutModal'
            );

        if (!modal) {
            return;
        }

        modal.classList.add('show');

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

        modal.classList.remove('show');

        document.body.style.overflow =
            '';
    }


    function handleLogoutOverlayClick(
        event
    ) {
        if (
            event.target.id
            ===
            'logoutModal'
        ) {
            closeLogoutModal();
        }
    }


    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key
                ===
                'Escape'
            ) {
                closeLogoutModal();
            }

        }
    );
</script>