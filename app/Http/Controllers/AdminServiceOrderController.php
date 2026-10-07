<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminServiceOrderController extends Controller
{
    /**
     * Các trạng thái hỗ trợ lọc.
     */
    private const FILTER_STATUSES = [
        'ALL',
        'RECEIVED',
        'IN_PROGRESS',
        'COMPLETED',
        'CANCELLED',
    ];


    /**
     * Các trạng thái cho phép ADMIN
     * thay đổi kỹ thuật viên phụ trách.
     */
    private const REASSIGNABLE_STATUSES = [
        'RECEIVED',
        'IN_PROGRESS',
    ];


    /**
     * Danh sách phiếu bảo dưỡng
     * dành cho Chủ xưởng.
     */
    public function index(
        Request $request
    ): View {
        /*
        |--------------------------------------------------------------------------
        | NORMALIZE FILTERS
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


        $statusFilter =
            strtoupper(
                trim(
                    (string)
                    $request->query(
                        'status',
                        'ALL'
                    )
                )
            );


        if (
            !in_array(
                $statusFilter,
                self::FILTER_STATUSES,
                true
            )
        ) {
            $statusFilter =
                'ALL';
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS COUNTS
        |--------------------------------------------------------------------------
        */

        $statusCounts =
            ServiceOrder::query()
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


        $totalOrders =
            ServiceOrder::query()
                ->count();


        $activeOrders =
            ServiceOrder::query()
                ->whereIn(
                    'status',
                    self::REASSIGNABLE_STATUSES
                )
                ->count();


        /*
        |--------------------------------------------------------------------------
        | SERVICE ORDER QUERY
        |--------------------------------------------------------------------------
        */

        $query =
            ServiceOrder::query()
                ->with([
                    'customer',

                    'vehicle.brand',

                    'vehicle.vehicleModel',

                    'creator',

                    'technician',

                    'items',
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
                            'order_code',
                            'like',
                            '%'.$keyword.'%'
                        )
                        ->orWhereHas(
                            'customer',
                            function ($query) use (
                                $keyword
                            ) {
                                $query->where(
                                    'full_name',
                                    'like',
                                    '%'.$keyword.'%'
                                );
                            }
                        )
                        ->orWhereHas(
                            'vehicle',
                            function ($query) use (
                                $keyword
                            ) {
                                $query->where(
                                    'license_plate',
                                    'like',
                                    '%'.$keyword.'%'
                                );
                            }
                        )
                        ->orWhereHas(
                            'technician',
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
                            }
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
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if (
            $statusFilter !== 'ALL'
        ) {
            $query->where(
                'status',
                $statusFilter
            );
        }


        /*
        |--------------------------------------------------------------------------
        | RESULTS
        |--------------------------------------------------------------------------
        */

        $serviceOrders =
            $query
                ->orderByRaw("
                    CASE status
                        WHEN 'IN_PROGRESS' THEN 1
                        WHEN 'RECEIVED' THEN 2
                        WHEN 'COMPLETED' THEN 3
                        WHEN 'CANCELLED' THEN 4
                        ELSE 5
                    END
                ")
                ->orderByDesc(
                    'id'
                )
                ->paginate(
                    20
                )
                ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | TECHNICIANS
        |--------------------------------------------------------------------------
        */

        $technicians =
            User::query()
                ->with(
                    'role'
                )
                ->whereHas(
                    'role',
                    function ($query) {
                        $query->where(
                            'code',
                            'TECHNICIAN'
                        );
                    }
                )
                ->withCount([
                    'technicianServiceOrders as active_orders_count' =>
                        function ($query) {
                            $query->whereIn(
                                'status',
                                self::REASSIGNABLE_STATUSES
                            );
                        },
                ])
                ->orderBy(
                    'name'
                )
                ->get();


        return view(
            'admin.service-orders.index',
            compact(
                'serviceOrders',
                'technicians',
                'keyword',
                'statusFilter',
                'statusCounts',
                'totalOrders',
                'activeOrders'
            )
        );
    }


    /**
     * ADMIN phân công lại kỹ thuật viên
     * cho phiếu bảo dưỡng.
     */
    public function updateTechnician(
        Request $request,
        ServiceOrder $serviceOrder
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated =
            $request->validate(
                [
                    'technician_id' => [
                        'bail',
                        'required',
                        'integer',
                    ],
                ],
                [
                    'technician_id.required' =>
                        'Vui lòng chọn kỹ thuật viên phụ trách.',

                    'technician_id.integer' =>
                        'Kỹ thuật viên được chọn không hợp lệ.',
                ]
            );


        $newTechnicianId =
            (int)
            $validated[
                'technician_id'
            ];


        /*
        |--------------------------------------------------------------------------
        | TRANSACTION
        |--------------------------------------------------------------------------
        */

        $result =
            DB::transaction(
                function () use (
                    $serviceOrder,
                    $newTechnicianId
                ) {
                    /*
                    |--------------------------------------------------------------------------
                    | LOCK ORDER
                    |--------------------------------------------------------------------------
                    */

                    $lockedOrder =
                        ServiceOrder::query()
                            ->with(
                                'technician'
                            )
                            ->whereKey(
                                $serviceOrder->id
                            )
                            ->lockForUpdate()
                            ->firstOrFail();


                    /*
                    |--------------------------------------------------------------------------
                    | ORDER STATUS GUARD
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !in_array(
                            $lockedOrder->status,
                            self::REASSIGNABLE_STATUSES,
                            true
                        )
                    ) {
                        throw ValidationException::withMessages([
                            'technician_id' =>
                                'Chỉ có thể thay đổi kỹ thuật viên khi phiếu đang ở trạng thái RECEIVED hoặc IN_PROGRESS.',
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | AUTHORITATIVE TECHNICIAN CHECK
                    |--------------------------------------------------------------------------
                    */

                    $newTechnician =
                        User::query()
                            ->with(
                                'role'
                            )
                            ->whereKey(
                                $newTechnicianId
                            )
                            ->lockForUpdate()
                            ->first();


                    if (
                        !$newTechnician
                        ||
                        $newTechnician
                            ->role
                            ?->code
                        !== 'TECHNICIAN'
                    ) {
                        throw ValidationException::withMessages([
                            'technician_id' =>
                                'Tài khoản được chọn không tồn tại hoặc không có vai trò TECHNICIAN.',
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | SAME TECHNICIAN
                    |--------------------------------------------------------------------------
                    */

                    if (
                        (int)
                        $lockedOrder->technician_id
                        ===
                        $newTechnician->id
                    ) {
                        throw ValidationException::withMessages([
                            'technician_id' =>
                                'Kỹ thuật viên được chọn đang phụ trách phiếu này.',
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | OLD DATA
                    |--------------------------------------------------------------------------
                    */

                    $oldTechnicianId =
                        $lockedOrder
                            ->technician_id;


                    $oldTechnicianName =
                        $lockedOrder
                            ->technician
                            ?->name
                        ?? 'Chưa phân công';


                    $orderStatus =
                        $lockedOrder
                            ->status;


                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE ASSIGNMENT
                    |--------------------------------------------------------------------------
                    |
                    | Chỉ thay technician_id.
                    |
                    | Không thay:
                    |
                    | - status;
                    | - started_at;
                    | - completed_at;
                    | - trạng thái hạng mục;
                    | - technician_note.
                    |
                    */

                    $lockedOrder->update([
                        'technician_id' =>
                            $newTechnician->id,
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | ACTIVITY LOG
                    |--------------------------------------------------------------------------
                    */

                    ActivityLogger::log(
                        action:
                            'TECHNICIAN_REASSIGNED',

                        description:
                            'ADMIN đã chuyển phiếu '
                            .$lockedOrder->order_code
                            .' từ kỹ thuật viên '
                            .$oldTechnicianName
                            .' sang '
                            .$newTechnician->name
                            .'.',

                        entity:
                            $lockedOrder,

                        oldValues: [
                            'technician_id' =>
                                $oldTechnicianId,

                            'technician_name' =>
                                $oldTechnicianName,

                            'status' =>
                                $orderStatus,
                        ],

                        newValues: [
                            'technician_id' =>
                                $newTechnician->id,

                            'technician_name' =>
                                $newTechnician->name,

                            'status' =>
                                $lockedOrder->status,
                        ]
                    );


                    return [
                        'old_name' =>
                            $oldTechnicianName,

                        'new_name' =>
                            $newTechnician->name,

                        'order_code' =>
                            $lockedOrder->order_code,
                    ];
                }
            );


        return redirect()
            ->route(
                'admin.service-orders.index'
            )
            ->with(
                'success',
                'Đã chuyển phiếu '
                .$result['order_code']
                .' từ '
                .$result['old_name']
                .' sang '
                .$result['new_name']
                .'.'
            );
    }
}