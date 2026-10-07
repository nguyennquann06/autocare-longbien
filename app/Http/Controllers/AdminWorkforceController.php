<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminWorkforceController extends Controller
{
    /**
     * Các vai trò nhân sự garage.
     */
    private const WORKFORCE_ROLES = [
        'STAFF',
        'TECHNICIAN',
    ];


    /**
     * Danh sách và tổng quan nhân sự.
     */
    public function index(
        Request $request
    ): View {
        /*
        |--------------------------------------------------------------------------
        | NORMALIZE FILTER
        |--------------------------------------------------------------------------
        */

        $keyword =
            preg_replace(
                '/\s+/u',
                ' ',
                trim(
                    (string)
                    $request->query(
                        'q',
                        ''
                    )
                )
            );


        $roleFilter =
            strtoupper(
                trim(
                    (string)
                    $request->query(
                        'role',
                        ''
                    )
                )
            );


        if (
            !in_array(
                $roleFilter,
                [
                    '',
                    'STAFF',
                    'TECHNICIAN',
                ],
                true
            )
        ) {
            $roleFilter =
                '';
        }


        /*
        |--------------------------------------------------------------------------
        | GLOBAL COUNTS
        |--------------------------------------------------------------------------
        */

        $staffCount =
            User::query()
                ->whereHas(
                    'role',
                    function ($query) {
                        $query->where(
                            'code',
                            'STAFF'
                        );
                    }
                )
                ->count();


        $technicianCount =
            User::query()
                ->whereHas(
                    'role',
                    function ($query) {
                        $query->where(
                            'code',
                            'TECHNICIAN'
                        );
                    }
                )
                ->count();


        $receivedOrders =
            ServiceOrder::query()
                ->where(
                    'status',
                    'RECEIVED'
                )
                ->count();


        $inProgressOrders =
            ServiceOrder::query()
                ->where(
                    'status',
                    'IN_PROGRESS'
                )
                ->count();


        $activeOrders =
            $receivedOrders
            +
            $inProgressOrders;


        /*
        |--------------------------------------------------------------------------
        | WORKFORCE QUERY
        |--------------------------------------------------------------------------
        */

        $query =
            User::query()
                ->with(
                    'role'
                )
                ->whereHas(
                    'role',
                    function ($query) {
                        $query->whereIn(
                            'code',
                            self::WORKFORCE_ROLES
                        );
                    }
                )
                ->withCount([
                    'createdServiceOrders',

                    'technicianServiceOrders',

                    'createdInvoices',

                    'inventoryTransactions',

                    'technicianServiceOrders as active_technician_orders_count' =>
                        function ($query) {
                            $query->whereIn(
                                'status',
                                [
                                    'RECEIVED',
                                    'IN_PROGRESS',
                                ]
                            );
                        },

                    'technicianServiceOrders as completed_technician_orders_count' =>
                        function ($query) {
                            $query->where(
                                'status',
                                'COMPLETED'
                            );
                        },
                ]);


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($keyword !== '') {
            $query->where(
                function ($query) use (
                    $keyword
                ) {
                    $query
                        ->where(
                            'name',
                            'like',
                            '%'.$keyword.'%'
                        )
                        ->orWhere(
                            'email',
                            'like',
                            '%'.$keyword.'%'
                        );


                    if (
                        ctype_digit(
                            $keyword
                        )
                    ) {
                        $query->orWhere(
                            'id',
                            (int)
                            $keyword
                        );
                    }
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ROLE FILTER
        |--------------------------------------------------------------------------
        */

        if ($roleFilter !== '') {
            $query->whereHas(
                'role',
                function ($query) use (
                    $roleFilter
                ) {
                    $query->where(
                        'code',
                        $roleFilter
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $workforceUsers =
            $query
                ->orderBy(
                    'name'
                )
                ->paginate(
                    15
                )
                ->withQueryString();


        return view(
            'admin.workforce.index',
            compact(
                'workforceUsers',
                'keyword',
                'roleFilter',
                'staffCount',
                'technicianCount',
                'receivedOrders',
                'inProgressOrders',
                'activeOrders'
            )
        );
    }


    /**
     * Chi tiết hoạt động một nhân sự.
     */
    public function show(
        User $user
    ): View {
        /*
        |--------------------------------------------------------------------------
        | ROLE CHECK
        |--------------------------------------------------------------------------
        */

        $user->load(
            'role'
        );


        $roleCode =
            $user
                ->role
                ?->code;


        if (
            !in_array(
                $roleCode,
                self::WORKFORCE_ROLES,
                true
            )
        ) {
            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | COMMON COUNTS
        |--------------------------------------------------------------------------
        */

        $user->loadCount([
            'createdServiceOrders',

            'technicianServiceOrders',

            'createdInvoices',

            'inventoryTransactions',

            'technicianServiceOrders as active_technician_orders_count' =>
                function ($query) {
                    $query->whereIn(
                        'status',
                        [
                            'RECEIVED',
                            'IN_PROGRESS',
                        ]
                    );
                },

            'technicianServiceOrders as completed_technician_orders_count' =>
                function ($query) {
                    $query->where(
                        'status',
                        'COMPLETED'
                    );
                },
        ]);


        /*
        |--------------------------------------------------------------------------
        | DEFAULT DATA
        |--------------------------------------------------------------------------
        */

        $serviceOrders =
            collect();


        $invoices =
            collect();


        $inventoryTransactions =
            collect();


        $technicianStatusCounts =
            collect();


        $technicianItems =
            collect();


        $technicianItemStatusCounts =
            collect();


        $totalTechnicianItems =
            0;


        /*
        |--------------------------------------------------------------------------
        | TECHNICIAN MONITORING
        |--------------------------------------------------------------------------
        */

        if (
            $roleCode
            === 'TECHNICIAN'
        ) {
            /*
            |--------------------------------------------------------------------------
            | ASSIGNED SERVICE ORDERS
            |--------------------------------------------------------------------------
            */

            $serviceOrders =
                $user
                    ->technicianServiceOrders()
                    ->with([
                        'customer',

                        'vehicle.brand',

                        'vehicle.vehicleModel',

                        'creator',

                        'items.service',
                    ])
                    ->orderByRaw("
                        CASE status
                            WHEN 'IN_PROGRESS' THEN 1
                            WHEN 'RECEIVED' THEN 2
                            WHEN 'COMPLETED' THEN 3
                            WHEN 'CANCELLED' THEN 4
                            ELSE 5
                        END
                    ")
                    ->orderByRaw("
                        CASE
                            WHEN status = 'IN_PROGRESS'
                            THEN COALESCE(
                                started_at,
                                received_at
                            )
                        END ASC
                    ")
                    ->orderByRaw("
                        CASE
                            WHEN status = 'RECEIVED'
                            THEN received_at
                        END ASC
                    ")
                    ->orderByRaw("
                        CASE
                            WHEN status = 'COMPLETED'
                            THEN completed_at
                        END DESC
                    ")
                    ->orderByDesc(
                        'received_at'
                    )
                    ->orderByDesc(
                        'id'
                    )
                    ->limit(
                        30
                    )
                    ->get();


            /*
            |--------------------------------------------------------------------------
            | ORDER STATUS COUNTS
            |--------------------------------------------------------------------------
            */

            $technicianStatusCounts =
                ServiceOrder::query()
                    ->where(
                        'technician_id',
                        $user->id
                    )
                    ->selectRaw(
                        'status, COUNT(*) as total'
                    )
                    ->groupBy(
                        'status'
                    )
                    ->pluck(
                        'total',
                        'status'
                    );


            /*
            |--------------------------------------------------------------------------
            | TECHNICAL ITEMS
            |--------------------------------------------------------------------------
            |
            | Lấy toàn bộ hạng mục trong các phiếu
            | đang hiển thị để ADMIN có thể xem
            | chi tiết tiến độ công việc kỹ thuật.
            |
            */

            $technicianItems =
                $serviceOrders
                    ->flatMap(
                        function ($order) {
                            return $order
                                ->items
                                ->map(
                                    function ($item) use (
                                        $order
                                    ) {
                                        $item->setRelation(
                                            'serviceOrder',
                                            $order
                                        );


                                        return $item;
                                    }
                                );
                        }
                    )
                    ->values();


            $technicianItemStatusCounts =
                $technicianItems
                    ->countBy(
                        function ($item) {
                            return $item->status;
                        }
                    );


            $totalTechnicianItems =
                $technicianItems
                    ->count();
        }


        /*
        |--------------------------------------------------------------------------
        | STAFF MONITORING
        |--------------------------------------------------------------------------
        */

        if (
            $roleCode
            === 'STAFF'
        ) {
            /*
            |--------------------------------------------------------------------------
            | SERVICE ORDERS CREATED
            |--------------------------------------------------------------------------
            */

            $serviceOrders =
                $user
                    ->createdServiceOrders()
                    ->with([
                        'customer',

                        'vehicle.brand',

                        'vehicle.vehicleModel',

                        'technician',

                        'items',

                        'invoice',
                    ])
                    ->orderByDesc(
                        'id'
                    )
                    ->limit(
                        30
                    )
                    ->get();


            /*
            |--------------------------------------------------------------------------
            | INVOICES CREATED
            |--------------------------------------------------------------------------
            */

            $invoices =
                $user
                    ->createdInvoices()
                    ->with([
                        'customer',

                        'serviceOrder.vehicle.brand',

                        'serviceOrder.vehicle.vehicleModel',
                    ])
                    ->orderByDesc(
                        'issued_at'
                    )
                    ->orderByDesc(
                        'id'
                    )
                    ->limit(
                        30
                    )
                    ->get();


            /*
            |--------------------------------------------------------------------------
            | INVENTORY TRANSACTIONS
            |--------------------------------------------------------------------------
            */

            $inventoryTransactions =
                $user
                    ->inventoryTransactions()
                    ->with([
                        'part',

                        'serviceOrder',
                    ])
                    ->orderByDesc(
                        'transaction_at'
                    )
                    ->orderByDesc(
                        'id'
                    )
                    ->limit(
                        50
                    )
                    ->get();
        }


        return view(
            'admin.workforce.show',
            compact(
                'user',
                'roleCode',
                'serviceOrders',
                'invoices',
                'inventoryTransactions',
                'technicianStatusCounts',
                'technicianItems',
                'technicianItemStatusCounts',
                'totalTechnicianItems'
            )
        );
    }
}