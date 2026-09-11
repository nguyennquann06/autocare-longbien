<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use App\Models\ServiceOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TechnicianServiceOrderController extends Controller
{
    /**
     * Danh sách phiếu bảo dưỡng
     * được giao cho kỹ thuật viên hiện tại.
     */
    public function index()
    {
        $user = $this->authorizeTechnician();

        $serviceOrders = ServiceOrder::with([
            'customer',
            'vehicle.brand',
            'vehicle.vehicleModel',
            'appointment',
            'items',
        ])
            ->where(
                'technician_id',
                $user->id
            )
            ->orderByRaw("
                CASE status
                    WHEN 'IN_PROGRESS' THEN 1
                    WHEN 'RECEIVED' THEN 2
                    WHEN 'COMPLETED' THEN 3
                    WHEN 'CANCELLED' THEN 4
                    ELSE 5
                END
            ")
            ->orderByDesc('received_at')
            ->get();

        return view(
            'technician.service-orders.index',
            compact('serviceOrders')
        );
    }


    /**
     * Chi tiết phiếu bảo dưỡng.
     */
    public function show(
        ServiceOrder $serviceOrder
    ) {
        $user = $this->authorizeTechnician();

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
        $user = $this->authorizeTechnician();

        $this->authorizeAssignedTechnician(
            $serviceOrder,
            $user->id
        );

        /**
         * Chỉ phiếu RECEIVED mới được bắt đầu.
         */
        if ($serviceOrder->status !== 'RECEIVED') {
            return redirect()
                ->route(
                    'technician.service-orders.show',
                    $serviceOrder->id
                )
                ->with(
                    'error',
                    'Chỉ phiếu đã tiếp nhận mới có thể bắt đầu thực hiện.'
                );
        }

        DB::transaction(
            function () use ($serviceOrder) {

                /**
                 * Chuyển Service Order
                 * sang IN_PROGRESS.
                 */
                $serviceOrder->update([
                    'status' => 'IN_PROGRESS',
                    'started_at' => now(),
                ]);

                /**
                 * Đồng bộ Appointment.
                 */
                if (
                    $serviceOrder->appointment &&
                    $serviceOrder->appointment->status === 'CONFIRMED'
                ) {
                    $serviceOrder
                        ->appointment
                        ->update([
                            'status' => 'IN_PROGRESS',
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
        $user = $this->authorizeTechnician();

        $this->authorizeAssignedTechnician(
            $serviceOrder,
            $user->id
        );

        /**
         * Chống thao tác item
         * thuộc Service Order khác.
         */
        if (
            (int) $item->service_order_id !==
            (int) $serviceOrder->id
        ) {
            abort(
                403,
                'Hạng mục không thuộc phiếu bảo dưỡng này.'
            );
        }

        /**
         * Chỉ thao tác khi phiếu đang thực hiện.
         */
        if ($serviceOrder->status !== 'IN_PROGRESS') {
            return redirect()
                ->route(
                    'technician.service-orders.show',
                    $serviceOrder->id
                )
                ->with(
                    'error',
                    'Phiếu bảo dưỡng chưa ở trạng thái đang thực hiện.'
                );
        }

        $validated = $request->validate(
            [
                'status' => [
                    'required',

                    Rule::in([
                        'IN_PROGRESS',
                        'COMPLETED',
                    ]),
                ],

                'technician_note' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ],
            [
                'status.required' =>
                    'Vui lòng chọn trạng thái.',

                'status.in' =>
                    'Trạng thái hạng mục không hợp lệ.',

                'technician_note.max' =>
                    'Ghi chú kỹ thuật không được vượt quá 1000 ký tự.',
            ]
        );

        /**
         * Không cho hạng mục đã hoàn thành
         * quay ngược trạng thái.
         */
        if ($item->status === 'COMPLETED') {
            return redirect()
                ->route(
                    'technician.service-orders.show',
                    $serviceOrder->id
                )
                ->with(
                    'error',
                    'Hạng mục này đã hoàn thành.'
                );
        }

        /**
         * PENDING chỉ được:
         * → IN_PROGRESS
         *
         * IN_PROGRESS chỉ được:
         * → COMPLETED
         */
        if (
            $item->status === 'PENDING' &&
            $validated['status'] !== 'IN_PROGRESS'
        ) {
            return redirect()
                ->route(
                    'technician.service-orders.show',
                    $serviceOrder->id
                )
                ->with(
                    'error',
                    'Hạng mục phải được bắt đầu trước khi hoàn thành.'
                );
        }

        if (
            $item->status === 'IN_PROGRESS' &&
            $validated['status'] !== 'COMPLETED'
        ) {
            return redirect()
                ->route(
                    'technician.service-orders.show',
                    $serviceOrder->id
                )
                ->with(
                    'error',
                    'Trạng thái hạng mục không hợp lệ.'
                );
        }

        $item->update([
            'status' =>
                $validated['status'],

            'technician_note' =>
                array_key_exists(
                    'technician_note',
                    $validated
                )
                    ? (
                        !empty(
                            trim(
                                $validated['technician_note']
                                ?? ''
                            )
                        )
                            ? trim(
                                $validated['technician_note']
                            )
                            : null
                    )
                    : $item->technician_note,
        ]);

        return redirect()
            ->route(
                'technician.service-orders.show',
                $serviceOrder->id
            )
            ->with(
                'success',
                'Cập nhật hạng mục thành công.'
            );
    }


    /**
     * Hoàn thành toàn bộ phiếu bảo dưỡng.
     */
    public function complete(
        Request $request,
        ServiceOrder $serviceOrder
    ) {
        $user = $this->authorizeTechnician();

        $this->authorizeAssignedTechnician(
            $serviceOrder,
            $user->id
        );

        $serviceOrder->load([
            'items',
            'appointment',
        ]);

        if ($serviceOrder->status !== 'IN_PROGRESS') {
            return redirect()
                ->route(
                    'technician.service-orders.show',
                    $serviceOrder->id
                )
                ->with(
                    'error',
                    'Chỉ phiếu đang thực hiện mới có thể hoàn thành.'
                );
        }

        /**
         * Bắt buộc tất cả hạng mục
         * phải COMPLETED.
         */
        $unfinishedItems = $serviceOrder
            ->items
            ->where(
                'status',
                '!=',
                'COMPLETED'
            );

        if ($unfinishedItems->isNotEmpty()) {
            return redirect()
                ->route(
                    'technician.service-orders.show',
                    $serviceOrder->id
                )
                ->with(
                    'error',
                    'Vẫn còn hạng mục chưa hoàn thành.'
                );
        }

        $validated = $request->validate(
            [
                'technician_note' => [
                    'nullable',
                    'string',
                    'max:2000',
                ],
            ],
            [
                'technician_note.max' =>
                    'Ghi chú kỹ thuật không được vượt quá 2000 ký tự.',
            ]
        );

        DB::transaction(
            function () use (
                $serviceOrder,
                $validated
            ) {
                /**
                 * Hoàn thành Service Order.
                 */
                $serviceOrder->update([
                    'status' => 'COMPLETED',

                    'completed_at' => now(),

                    'technician_note' =>
                        !empty(
                            $validated['technician_note']
                            ?? null
                        )
                            ? trim(
                                $validated['technician_note']
                            )
                            : $serviceOrder
                                ->technician_note,
                ]);

                /**
                 * Đồng bộ Appointment.
                 */
                if ($serviceOrder->appointment) {
                    $serviceOrder
                        ->appointment
                        ->update([
                            'status' => 'COMPLETED',
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
        $user = Auth::user();

        if (
            !$user ||
            !$user->role
        ) {
            abort(
                403,
                'Bạn không có quyền truy cập.'
            );
        }

        if (
            $user->role->code !==
            'TECHNICIAN'
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
            (int) $serviceOrder->technician_id !==
            $userId
        ) {
            abort(
                403,
                'Phiếu bảo dưỡng này không được phân công cho bạn.'
            );
        }
    }
}