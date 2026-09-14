@auth

    @php
        $browserHistoryUser =
            auth()->user();

        $browserHistoryUser
            ?->loadMissing(
                'role'
            );

        $browserHistoryRole =
            $browserHistoryUser
                ?->role
                ?->code;


        $browserHistoryDashboardRoute =
            match (
                $browserHistoryRole
            ) {
                'CUSTOMER' =>
                    'customer.dashboard',

                'STAFF',
                'ADMIN' =>
                    'staff.dashboard',

                'TECHNICIAN' =>
                    'technician.service-orders.index',

                default =>
                    null,
            };


        $browserHistoryDashboardUrl =
            $browserHistoryDashboardRoute
                ? route(
                    $browserHistoryDashboardRoute
                )
                : route('home');


        $browserHistoryIsDashboard =
            $browserHistoryDashboardRoute
                ? request()->routeIs(
                    $browserHistoryDashboardRoute
                )
                : false;
    @endphp


    <script>
        (() => {
            'use strict';


            /*
            |--------------------------------------------------------------------------
            | AUTOCARE BROWSER HISTORY GUARD
            |--------------------------------------------------------------------------
            |
            | Mục tiêu:
            |
            | 1. User đã đăng nhập:
            |    - Back từ trang chức năng
            |      => Dashboard.
            |
            | 2. Back từ Dashboard:
            |    - Không quay về snapshot Login/Home cũ.
            |    - Mở modal xác nhận đăng xuất.
            |
            | 3. BFCache:
            |    - Nếu browser restore một trang cũ từ memory,
            |      reload lại để Laravel kiểm tra session thật.
            |
            */


            const dashboardUrl =
                @json(
                    $browserHistoryDashboardUrl
                );


            const isDashboard =
                @json(
                    $browserHistoryIsDashboard
                );


            const pageUrl =
                window.location.href;


            const guardStateKey =
                'autocare_history_guard';


            const pageStateKey =
                'autocare_history_page';


            let isHandlingBack =
                false;


            /*
            |--------------------------------------------------------------------------
            | BFCache PROTECTION
            |--------------------------------------------------------------------------
            |
            | Chrome / Edge có thể giữ nguyên HTML cũ
            | trong Back-Forward Cache.
            |
            | Sau logout, nếu người dùng Back/Forward
            | tới một snapshot cũ, ta buộc reload để
            | backend kiểm tra session hiện tại.
            |
            */

            window.addEventListener(
                'pageshow',
                function (event) {
                    if (
                        event.persisted
                    ) {
                        window.location.reload();
                    }
                }
            );


            /*
            |--------------------------------------------------------------------------
            | OPEN EXISTING LOGOUT MODAL
            |--------------------------------------------------------------------------
            |
            | Không tạo modal mới.
            | Dùng đúng modal của nút "Đăng xuất"
            | trong role-nav.
            |
            */

            function openLogoutConfirmation() {
                if (
                    typeof window
                        .openLogoutModal
                    === 'function'
                ) {
                    window
                        .openLogoutModal();

                    return;
                }


                const modal =
                    document
                        .getElementById(
                            'logoutModal'
                        );


                if (!modal) {
                    return;
                }


                modal.classList.add(
                    'show'
                );


                document.body.style
                    .overflow =
                    'hidden';
            }


            /*
            |--------------------------------------------------------------------------
            | INSTALL HISTORY SENTINEL
            |--------------------------------------------------------------------------
            |
            | Tạo một history entry giả ngay phía
            | trước trang hiện tại.
            |
            | Vì vậy khi user nhấn Back:
            | - Browser không lập tức rời trang.
            | - popstate chạy trước.
            | - AutoCare quyết định hành vi.
            |
            */

            function installGuard() {
                const currentState =
                    window.history.state;


                if (
                    !currentState
                    ||
                    currentState[
                        pageStateKey
                    ] !== true
                ) {
                    window.history
                        .replaceState(
                            {
                                ...(
                                    currentState
                                    || {}
                                ),

                                [
                                    pageStateKey
                                ]:
                                    true,
                            },
                            '',
                            pageUrl
                        );
                }


                window.history
                    .pushState(
                        {
                            [
                                guardStateKey
                            ]:
                                true,
                        },
                        '',
                        pageUrl
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | RE-ARM
            |--------------------------------------------------------------------------
            |
            | Khi Back đã bị bắt lại mà user
            | vẫn ở Dashboard, phải push sentinel
            | thêm một lần để lần Back tiếp theo
            | tiếp tục được kiểm soát.
            |
            */

            function rearmGuard() {
                window.history
                    .pushState(
                        {
                            [
                                guardStateKey
                            ]:
                                true,
                        },
                        '',
                        pageUrl
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | BACK HANDLER
            |--------------------------------------------------------------------------
            */

            function handleBrowserBack() {
                if (
                    isHandlingBack
                ) {
                    return;
                }


                isHandlingBack =
                    true;


                /*
                 * DASHBOARD
                 *
                 * Back = hành vi giống nút Đăng xuất:
                 * mở modal xác nhận.
                 */
                if (
                    isDashboard
                ) {
                    rearmGuard();


                    window.setTimeout(
                        function () {
                            openLogoutConfirmation();

                            isHandlingBack =
                                false;
                        },
                        0
                    );


                    return;
                }


                /*
                 * TRANG CHỨC NĂNG
                 *
                 * Back = quay thẳng về Dashboard.
                 *
                 * replace() để không tạo thêm
                 * một history entry dư.
                 */
                window.location.replace(
                    dashboardUrl
                );
            }


            /*
            |--------------------------------------------------------------------------
            | INITIALIZE
            |--------------------------------------------------------------------------
            */

            function initializeHistoryGuard() {
                if (
                    window.history
                    &&
                    typeof window.history
                        .pushState
                    === 'function'
                ) {
                    installGuard();


                    window.addEventListener(
                        'popstate',
                        handleBrowserBack
                    );
                }
            }


            if (
                document.readyState
                === 'loading'
            ) {
                document.addEventListener(
                    'DOMContentLoaded',
                    initializeHistoryGuard,
                    {
                        once: true,
                    }
                );
            } else {
                initializeHistoryGuard();
            }
        })();
    </script>

@endauth