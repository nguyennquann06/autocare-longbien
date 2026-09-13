<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use App\Models\ServiceOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TechnicianServiceOrderController extends Controller
{
    /**
     * Danh sách phiếu bảo dưỡng
     * được giao cho kỹ thuật viên hiện tại.
     *
     * Thứ tự ưu tiên:
     *
     * 1. IN_PROGRESS
     * 2. RECEIVED
     * 3. COMPLETED
     * 4. CANCELLED
     */
    public function index(
        Request $request
    ) {
        $user =
            $this->authorizeTechnician();


        $allowedStatuses = [
            'ALL',
            'IN_PROGRESS',
            'RECEIVED',
            'COMPLETED',
            'CANCELLED',
        ];


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
                $allowedStatuses,
                true
            )
        ) {
            $statusFilter =
                'ALL';
        }


        $statusCounts =
            ServiceOrder::query()
                ->where(
                    'technician_id',
                    $user->id
                )
                ->selectRaw(
                    'status, COUNT(*) as total'
                )
                ->groupBy('status')
                ->pluck(
                    'total',
                    'status'
                );


        $totalServiceOrders =
            (int)
            $statusCounts->sum();


        $query =
            ServiceOrder::with([
                'customer',
                'vehicle.brand',
                'vehicle.vehicleModel',
                'appointment',
                'items',
            ])
                ->where(
                    'technician_id',
                    $user->id
                );


        if (
            $statusFilter !== 'ALL'
        ) {
            $query->where(
                'status',
                $statusFilter
            );
        }


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
                ->get();


        return view(
            'technician.service-orders.index',
            compact(
                'serviceOrders',
                'statusFilter',
                'statusCounts',
                'totalServiceOrders'
            )
        );
    }


    /**
     * Chi tiết phiếu bảo dưỡng.
     */
    public function show(
        ServiceOrder $serviceOrder
    ) {
        $user =
            $this->authorizeTechnician();


        $this->authorizeAssignedTechnician(
            $serviceOrder,
            $user->id
        );


        $serviceOrder->load([
            'appointment',
            'customer',
            'vehicle.brand',
            'vehicle.vehicleModel',
            'creator',
            'technician',
            'items.service',
        ]);


        return view(
            'technician.service-orders.show',
            compact('serviceOrder')
        );
    }


    /**
     * Kỹ thuật viên bắt đầu thực hiện phiếu.
     */
    public function start(
        ServiceOrder $serviceOrder
    ) {
        $user =
            $this->authorizeTechnician();


        $this->authorizeAssignedTechnician(
            $serviceOrder,
            $user->id
        );


        DB::transaction(
            function () use (
                $serviceOrder,
                $user
            ) {
                $lockedOrder =
                    ServiceOrder::query()
                        ->whereKey(
                            $serviceOrder->id
                        )
                        ->lockForUpdate()
                        ->firstOrFail();


                $this->authorizeAssignedTechnician(
                    $lockedOrder,
                    $user->id
                );


                if (
                    $lockedOrder->status
                    !== 'RECEIVED'
                ) {
                    throw ValidationException::withMessages([
                        'service_order' =>
                            $lockedOrder->status
                            === 'IN_PROGRESS'
                                ? 'Phiếu bảo dưỡng này đã được bắt đầu trước đó.'
                                : 'Chỉ phiếu đã tiếp nhận mới có thể bắt đầu thực hiện.',
                    ]);
                }


                $lockedOrder->update([
                    'status' =>
                        'IN_PROGRESS',

                    'started_at' =>
                        now(),
                ]);


                $appointment =
                    $lockedOrder
                        ->appointment()
                        ->lockForUpdate()
                        ->first();


                if (
                    $appointment
                    &&
                    $appointment->status
                    === 'CONFIRMED'
                ) {
                    $appointment->update([
                        'status' =>
                            'IN_PROGRESS',
                    ]);
                }
            }
        );


        return redirect()
            ->route(
                'technician.service-orders.show',
                $serviceOrder->id
            )
            ->with(
                'success',
                'Đã bắt đầu thực hiện phiếu bảo dưỡng.'
            );
    }


    /**
     * Cập nhật trạng thái từng hạng mục.
     */
    public function updateItemStatus(
        Request $request,
        ServiceOrder $serviceOrder,
        ServiceOrderItem $item
    ) {
        $user =
            $this->authorizeTechnician();


        $this->authorizeAssignedTechnician(
            $serviceOrder,
            $user->id
        );


        if (
            (int)
            $item->service_order_id
            !==
            (int)
            $serviceOrder->id
        ) {
            abort(
                403,
                'Hạng mục không thuộc phiếu bảo dưỡng này.'
            );
        }


        $request->merge([
            'technician_note' =>
                $this->normalizeNullableText(
                    $request->input(
                        'technician_note'
                    )
                ),
        ]);


        $validated =
            $request->validate(
                [
                    'status' => [
                        'bail',
                        'required',
                        'string',

                        Rule::in([
                            'IN_PROGRESS',
                            'COMPLETED',
                        ]),
                    ],

                    'technician_note' => [
                        'bail',
                        'nullable',
                        'string',
                        'max:1000',
                    ],
                ],
                [
                    'status.required' =>
                        'Không xác định được trạng thái tiếp theo của hạng mục.',

                    'status.string' =>
                        'Trạng thái hạng mục không hợp lệ.',

                    'status.in' =>
                        'Trạng thái hạng mục không hợp lệ.',

                    'technician_note.string' =>
                        'Ghi chú kỹ thuật không hợp lệ.',

                    'technician_note.max' =>
                        'Ghi chú kỹ thuật không được vượt quá 1000 ký tự.',
                ]
            );


        DB::transaction(
            function () use (
                $serviceOrder,
                $item,
                $validated,
                $user
            ) {
                $lockedOrder =
                    ServiceOrder::query()
                        ->whereKey(
                            $serviceOrder->id
                        )
                        ->lockForUpdate()
                        ->firstOrFail();


                $this->authorizeAssignedTechnician(
                    $lockedOrder,
                    $user->id
                );


                if (
                    $lockedOrder->status
                    !== 'IN_PROGRESS'
                ) {
                    throw ValidationException::withMessages([
                        'status' =>
                            'Chỉ có thể cập nhật hạng mục khi phiếu bảo dưỡng đang được thực hiện.',
                    ]);
                }


                $lockedItem =
                    ServiceOrderItem::query()
                        ->whereKey(
                            $item->id
                        )
                        ->where(
                            'service_order_id',
                            $lockedOrder->id
                        )
                        ->lockForUpdate()
                        ->first();


                if (
                    !$lockedItem
                ) {
                    throw ValidationException::withMessages([
                        'status' =>
                            'Hạng mục không còn tồn tại hoặc không thuộc phiếu bảo dưỡng này.',
                    ]);
                }


                $currentStatus =
                    $lockedItem->status;


                $nextStatus =
                    $validated[
                        'status'
                    ];


                if (
                    $currentStatus
                    === 'COMPLETED'
                ) {
                    throw ValidationException::withMessages([
                        'status' =>
                            'Hạng mục này đã hoàn thành và không thể cập nhật lại.',
                    ]);
                }


                if (
                    $currentStatus
                    === 'CANCELLED'
                ) {
                    throw ValidationException::withMessages([
                        'status' =>
                            'Hạng mục đã bị hủy nên không thể tiếp tục thực hiện.',
                    ]);
                }


                if (
                    $currentStatus
                    === 'PENDING'
                    &&
                    $nextStatus
                    !== 'IN_PROGRESS'
                ) {
                    throw ValidationException::withMessages([
                        'status' =>
                            'Hạng mục phải được bắt đầu trước khi có thể hoàn thành.',
                    ]);
                }


                if (
                    $currentStatus
                    === 'IN_PROGRESS'
                    &&
                    $nextStatus
                    !== 'COMPLETED'
                ) {
                    throw ValidationException::withMessages([
                        'status' =>
                            'Hạng mục đang được thực hiện. Bước tiếp theo hợp lệ là hoàn thành hạng mục.',
                    ]);
                }


                if (
                    !in_array(
                        $currentStatus,
                        [
                            'PENDING',
                            'IN_PROGRESS',
                        ],
                        true
                    )
                ) {
                    throw ValidationException::withMessages([
                        'status' =>
                            'Trạng thái hiện tại của hạng mục không cho phép cập nhật.',
                    ]);
                }


                $lockedItem->update([
                    'status' =>
                        $nextStatus,

                    'technician_note' =>
                        $validated[
                            'technician_note'
                        ]
                        ?? null,
                ]);
            }
        );


        return redirect()
            ->route(
                'technician.service-orders.show',
                $serviceOrder->id
            )
            ->with(
                'success',
                $validated['status']
                === 'IN_PROGRESS'
                    ? 'Đã bắt đầu thực hiện hạng mục.'
                    : 'Đã hoàn thành hạng mục.'
            );
    }


    /**
     * Hoàn thành toàn bộ phiếu bảo dưỡng.
     */
    public function complete(
        Request $request,
        ServiceOrder $serviceOrder
    ) {
        $user =
            $this->authorizeTechnician();


        /*
        |--------------------------------------------------------------------------
        | INITIAL AUTHORIZATION
        |--------------------------------------------------------------------------
        */

        $this->authorizeAssignedTechnician(
            $serviceOrder,
            $user->id
        );


        /*
        |--------------------------------------------------------------------------
        | NORMALIZE FINAL NOTE
        |--------------------------------------------------------------------------
        */

        $request->merge([
            'technician_note' =>
                $this->normalizeNullableText(
                    $request->input(
                        'technician_note'
                    )
                ),
        ]);


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated =
            $request->validate(
                [
                    'technician_note' => [
                        'bail',
                        'nullable',
                        'string',
                        'max:2000',
                    ],
                ],
                [
                    'technician_note.string' =>
                        'Ghi chú tổng kết kỹ thuật không hợp lệ.',

                    'technician_note.max' =>
                        'Ghi chú tổng kết kỹ thuật không được vượt quá 2000 ký tự.',
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | COMPLETE TRANSACTION
        |--------------------------------------------------------------------------
        |
        | Khóa Service Order + toàn bộ Item trước khi hoàn thành.
        |
        | Điều này tránh trường hợp:
        |
        | - hai request cùng đóng phiếu;
        | - item thay đổi trong lúc đang kiểm tra;
        | - phiếu bị phân công lại;
        | - trạng thái phiếu thay đổi đồng thời.
        |
        */

        DB::transaction(
            function () use (
                $serviceOrder,
                $validated,
                $user
            ) {
                /*
                |--------------------------------------------------------------------------
                | LOCK SERVICE ORDER
                |--------------------------------------------------------------------------
                */

                $lockedOrder =
                    ServiceOrder::query()
                        ->whereKey(
                            $serviceOrder->id
                        )
                        ->lockForUpdate()
                        ->firstOrFail();


                /*
                |--------------------------------------------------------------------------
                | RECHECK ASSIGNMENT
                |--------------------------------------------------------------------------
                */

                $this->authorizeAssignedTechnician(
                    $lockedOrder,
                    $user->id
                );


                /*
                |--------------------------------------------------------------------------
                | RECHECK ORDER STATUS
                |--------------------------------------------------------------------------
                */

                if (
                    $lockedOrder->status
                    !== 'IN_PROGRESS'
                ) {
                    $message =
                        $lockedOrder->status
                        === 'COMPLETED'
                            ? 'Phiếu bảo dưỡng này đã được hoàn thành trước đó.'
                            : 'Chỉ phiếu đang thực hiện mới có thể hoàn thành.';


                    throw ValidationException::withMessages([
                        'service_order' =>
                            $message,
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | LOCK ALL ITEMS
                |--------------------------------------------------------------------------
                */

                $lockedItems =
                    ServiceOrderItem::query()
                        ->where(
                            'service_order_id',
                            $lockedOrder->id
                        )
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->get();


                /*
                |--------------------------------------------------------------------------
                | REQUIRE AT LEAST ONE ITEM
                |--------------------------------------------------------------------------
                */

                if (
                    $lockedItems->isEmpty()
                ) {
                    throw ValidationException::withMessages([
                        'service_order' =>
                            'Phiếu bảo dưỡng chưa có hạng mục công việc nên không thể hoàn thành.',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | REQUIRE ALL ITEMS COMPLETED
                |--------------------------------------------------------------------------
                */

                $unfinishedItems =
                    $lockedItems->filter(
                        fn ($item) =>
                            $item->status
                            !== 'COMPLETED'
                    );


                if (
                    $unfinishedItems->isNotEmpty()
                ) {
                    throw ValidationException::withMessages([
                        'service_order' =>
                            'Vẫn còn '
                            . $unfinishedItems->count()
                            . ' hạng mục chưa hoàn thành. Vui lòng hoàn thành toàn bộ hạng mục trước khi đóng phiếu.',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | LOCK APPOINTMENT
                |--------------------------------------------------------------------------
                */

                $appointment =
                    $lockedOrder
                        ->appointment()
                        ->lockForUpdate()
                        ->first();


                /*
                |--------------------------------------------------------------------------
                | PROTECT CANCELLED APPOINTMENT
                |--------------------------------------------------------------------------
                */

                if (
                    $appointment
                    &&
                    $appointment->status
                    === 'CANCELLED'
                ) {
                    throw ValidationException::withMessages([
                        'service_order' =>
                            'Lịch hẹn liên quan đã bị hủy nên không thể hoàn thành phiếu bảo dưỡng.',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | COMPLETE SERVICE ORDER
                |--------------------------------------------------------------------------
                */

                $lockedOrder->update([
                    'status' =>
                        'COMPLETED',

                    'completed_at' =>
                        now(),

                    'technician_note' =>
                        $validated[
                            'technician_note'
                        ]
                        ?? null,
                ]);


                /*
                |--------------------------------------------------------------------------
                | SYNCHRONIZE APPOINTMENT
                |--------------------------------------------------------------------------
                */

                if (
                    $appointment
                    &&
                    $appointment->status
                    !== 'COMPLETED'
                ) {
                    $appointment->update([
                        'status' =>
                            'COMPLETED',
                    ]);
                }
            }
        );


        return redirect()
            ->route(
                'technician.service-orders.show',
                $serviceOrder->id
            )
            ->with(
                'success',
                'Phiếu bảo dưỡng đã hoàn thành.'
            );
    }


    /**
     * Kiểm tra tài khoản TECHNICIAN.
     */
    private function authorizeTechnician()
    {
        $user =
            Auth::user();


        if (
            !$user
            ||
            !$user->role
        ) {
            abort(
                403,
                'Bạn không có quyền truy cập.'
            );
        }


        if (
            $user->role->code
            !== 'TECHNICIAN'
        ) {
            abort(
                403,
                'Bạn không có quyền truy cập khu vực kỹ thuật viên.'
            );
        }


        return $user;
    }


    /**
     * Chỉ kỹ thuật viên được phân công
     * mới được thao tác phiếu.
     */
    private function authorizeAssignedTechnician(
        ServiceOrder $serviceOrder,
        int $userId
    ): void {
        if (
            (int)
            $serviceOrder->technician_id
            !==
            $userId
        ) {
            abort(
                403,
                'Phiếu bảo dưỡng này không được phân công cho bạn.'
            );
        }
    }


    /**
     * Chuẩn hóa ghi chú nullable.
     *
     * Chỉ trim đầu/cuối, vẫn giữ
     * xuống dòng trong nội dung kỹ thuật.
     */
    private function normalizeNullableText(
        mixed $value
    ): ?string {
        if (
            $value === null
        ) {
            return null;
        }


        $value =
            trim(
                (string)
                $value
            );


        return $value !== ''
            ? $value
            : null;
    }
}