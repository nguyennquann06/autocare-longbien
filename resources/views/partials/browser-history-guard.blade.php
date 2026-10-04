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

                'STAFF' =>
                    'staff.dashboard',

                'ADMIN' =>
                    'admin.dashboard',

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
            | BFCACHE PROTECTION
            |--------------------------------------------------------------------------
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
            | LOGOUT CONFIRMATION
            |--------------------------------------------------------------------------
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
            | INSTALL HISTORY GUARD
            |--------------------------------------------------------------------------
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
                 * Nếu đang ở Dashboard của role hiện tại:
                 *
                 * Back => mở xác nhận đăng xuất.
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
                 * Nếu đang ở trang chức năng:
                 *
                 * CUSTOMER
                 *     => Customer Dashboard
                 *
                 * STAFF
                 *     => Staff Dashboard
                 *
                 * ADMIN
                 *     => Admin Dashboard
                 *
                 * TECHNICIAN
                 *     => Công việc kỹ thuật viên
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