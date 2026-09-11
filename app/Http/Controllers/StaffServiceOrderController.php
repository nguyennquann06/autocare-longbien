<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\InventoryTransaction;
use App\Models\Part;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderPart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StaffServiceOrderController extends Controller
{
    public function create(
        Appointment $appointment
    ) {
        $this->authorizeStaff();

        $appointment->load([
            'customer',
            'vehicle.brand',
            'vehicle.vehicleModel',
            'services',
            'serviceOrder',
        ]);

        if ($appointment->status !== 'CONFIRMED') {
            return redirect()
                ->route(
                    'staff.appointments.show',
                    $appointment->id
                )
                ->with(
                    'error',
                    'Chỉ lịch hẹn đã xác nhận mới có thể tạo phiếu bảo dưỡng.'
                );
        }

        if ($appointment->serviceOrder) {
            return redirect()
                ->route(
                    'staff.appointments.show',
                    $appointment->id
                )
                ->with(
                    'error',
                    'Lịch hẹn này đã có phiếu bảo dưỡng.'
                );
        }

        $technicians = User::whereHas(
            'role',
            function ($query) {
                $query->where(
                    'code',
                    'TECHNICIAN'
                );
            }
        )
            ->orderBy('name')
            ->get();

        return view(
            'staff.service-orders.create',
            compact(
                'appointment',
                'technicians'
            )
        );
    }


    public function store(
        Request $request,
        Appointment $appointment
    ) {
        $this->authorizeStaff();

        $appointment->load([
            'vehicle',
            'services',
            'serviceOrder',
        ]);

        if ($appointment->status !== 'CONFIRMED') {
            return redirect()
                ->route(
                    'staff.appointments.show',
                    $appointment->id
                )
                ->with(
                    'error',
                    'Chỉ lịch hẹn đã xác nhận mới có thể tạo phiếu bảo dưỡng.'
                );
        }

        if ($appointment->serviceOrder) {
            return redirect()
                ->route(
                    'staff.appointments.show',
                    $appointment->id
                )
                ->with(
                    'error',
                    'Lịch hẹn này đã có phiếu bảo dưỡng.'
                );
        }

        $currentMileage =
            (int) $appointment
                ->vehicle
                ->current_mileage;

        $validated = $request->validate(
            [
                'technician_id' => [
                    'required',

                    Rule::exists(
                        'users',
                        'id'
                    )->where(
                        function ($query) {
                            $query->whereIn(
                                'role_id',
                                function ($subQuery) {
                                    $subQuery
                                        ->select('id')
                                        ->from('roles')
                                        ->where(
                                            'code',
                                            'TECHNICIAN'
                                        );
                                }
                            );
                        }
                    ),
                ],

                'received_mileage' => [
                    'required',
                    'integer',
                    'min:' . $currentMileage,
                ],

                'vehicle_condition' => [
                    'nullable',
                    'string',
                    'max:2000',
                ],

                'diagnosis' => [
                    'nullable',
                    'string',
                    'max:2000',
                ],

                'staff_note' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ]
        );

        $user = Auth::user();

        $serviceOrder = DB::transaction(
            function () use (
                $appointment,
                $validated,
                $user
            ) {
                $serviceTotal =
                    $appointment
                        ->services
                        ->sum(
                            fn ($service) =>
                                (float)
                                $service
                                    ->pivot
                                    ->price
                        );

                $serviceOrder =
                    ServiceOrder::create([
                        'order_code' =>
                            $this->generateOrderCode(),

                        'appointment_id' =>
                            $appointment->id,

                        'customer_id' =>
                            $appointment->customer_id,

                        'vehicle_id' =>
                            $appointment->vehicle_id,

                        'created_by' =>
                            $user->id,

                        'technician_id' =>
                            $validated[
                                'technician_id'
                            ],

                        'received_mileage' =>
                            $validated[
                                'received_mileage'
                            ],

                        'status' =>
                            'RECEIVED',

                        'received_at' =>
                            now(),

                        'vehicle_condition' =>
                            $validated[
                                'vehicle_condition'
                            ]
                            ?? null,

                        'diagnosis' =>
                            $validated[
                                'diagnosis'
                            ]
                            ?? null,

                        'staff_note' =>
                            $validated[
                                'staff_note'
                            ]
                            ?? null,

                        'service_total' =>
                            $serviceTotal,

                        'parts_total' =>
                            0,

                        'total_amount' =>
                            $serviceTotal,
                    ]);

                foreach (
                    $appointment->services
                    as $service
                ) {
                    $price =
                        (float)
                        $service
                            ->pivot
                            ->price;

                    $serviceOrder
                        ->items()
                        ->create([
                            'service_id' =>
                                $service->id,

                            'service_name' =>
                                $service->name,

                            'unit_price' =>
                                $price,

                            'quantity' =>
                                1,

                            'line_total' =>
                                $price,

                            'estimated_duration_minutes' =>
                                $service
                                    ->pivot
                                    ->estimated_duration_minutes,

                            'status' =>
                                'PENDING',
                        ]);
                }

                $appointment
                    ->vehicle
                    ->update([
                        'current_mileage' =>
                            $validated[
                                'received_mileage'
                            ],
                    ]);

                return $serviceOrder;
            }
        );

        return redirect()
            ->route(
                'staff.service-orders.show',
                $serviceOrder->id
            )
            ->with(
                'success',
                'Tạo phiếu bảo dưỡng thành công.'
            );
    }


    public function show(
        ServiceOrder $serviceOrder
    ) {
        $this->authorizeStaff();

        $serviceOrder->load([
            'appointment',
            'customer',
            'vehicle.brand',
            'vehicle.vehicleModel',
            'creator',
            'technician',
            'items.service',
            'parts.part',
            'invoice',
        ]);

        $availableParts = Part::where(
            'is_active',
            true
        )
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return view(
            'staff.service-orders.show',
            compact(
                'serviceOrder',
                'availableParts'
            )
        );
    }


    public function addPart(
        Request $request,
        ServiceOrder $serviceOrder
    ) {
        $this->authorizeStaff();

        if (
            !in_array(
                $serviceOrder->status,
                [
                    'RECEIVED',
                    'IN_PROGRESS',
                ],
                true
            )
        ) {
            return redirect()
                ->route(
                    'staff.service-orders.show',
                    $serviceOrder->id
                )
                ->with(
                    'error',
                    'Không thể xuất phụ tùng cho phiếu đã hoàn thành hoặc đã hủy.'
                );
        }

        $validated = $request->validate(
            [
                'part_id' => [
                    'required',
                    'integer',
                    'exists:parts,id',
                ],

                'quantity' => [
                    'required',
                    'integer',
                    'min:1',
                ],

                'note' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ]
        );

        $user = Auth::user();

        DB::transaction(
            function () use (
                $serviceOrder,
                $validated,
                $user
            ) {
                $lockedOrder =
                    ServiceOrder::whereKey(
                        $serviceOrder->id
                    )
                        ->lockForUpdate()
                        ->firstOrFail();

                if (
                    !in_array(
                        $lockedOrder->status,
                        [
                            'RECEIVED',
                            'IN_PROGRESS',
                        ],
                        true
                    )
                ) {
                    throw ValidationException::withMessages([
                        'part_id' =>
                            'Phiếu bảo dưỡng không còn cho phép xuất phụ tùng.',
                    ]);
                }

                $part = Part::whereKey(
                    $validated['part_id']
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                if (!$part->is_active) {
                    throw ValidationException::withMessages([
                        'part_id' =>
                            'Phụ tùng này đã ngừng sử dụng.',
                    ]);
                }

                $quantity =
                    (int)
                    $validated['quantity'];

                $quantityBefore =
                    (int)
                    $part->stock_quantity;

                if (
                    $quantity >
                    $quantityBefore
                ) {
                    throw ValidationException::withMessages([
                        'quantity' =>
                            'Tồn kho hiện chỉ còn '
                            . $quantityBefore
                            . ' '
                            . $part->unit
                            . '.',
                    ]);
                }

                $quantityAfter =
                    $quantityBefore
                    -
                    $quantity;

                $existingOrderPart =
                    ServiceOrderPart::where(
                        'service_order_id',
                        $lockedOrder->id
                    )
                        ->where(
                            'part_id',
                            $part->id
                        )
                        ->lockForUpdate()
                        ->first();

                if ($existingOrderPart) {
                    $unitPrice =
                        (float)
                        $existingOrderPart
                            ->unit_price;

                    $newQuantity =
                        (int)
                        $existingOrderPart
                            ->quantity
                        +
                        $quantity;

                    $existingOrderPart->update([
                        'quantity' =>
                            $newQuantity,

                        'line_total' =>
                            $unitPrice
                            *
                            $newQuantity,

                        'note' =>
                            !empty(
                                $validated['note']
                                ?? null
                            )
                                ? trim(
                                    $validated['note']
                                )
                                : $existingOrderPart
                                    ->note,
                    ]);
                } else {
                    $unitPrice =
                        (float)
                        $part->selling_price;

                    ServiceOrderPart::create([
                        'service_order_id' =>
                            $lockedOrder->id,

                        'part_id' =>
                            $part->id,

                        'part_code' =>
                            $part->code,

                        'part_name' =>
                            $part->name,

                        'unit' =>
                            $part->unit,

                        'unit_price' =>
                            $unitPrice,

                        'quantity' =>
                            $quantity,

                        'line_total' =>
                            $unitPrice
                            *
                            $quantity,

                        'note' =>
                            !empty(
                                $validated['note']
                                ?? null
                            )
                                ? trim(
                                    $validated['note']
                                )
                                : null,
                    ]);
                }

                $part->update([
                    'stock_quantity' =>
                        $quantityAfter,
                ]);

                InventoryTransaction::create([
                    'part_id' =>
                        $part->id,

                    'service_order_id' =>
                        $lockedOrder->id,

                    'performed_by' =>
                        $user->id,

                    'transaction_type' =>
                        InventoryTransaction::TYPE_OUT,

                    'quantity' =>
                        $quantity,

                    'quantity_before' =>
                        $quantityBefore,

                    'quantity_after' =>
                        $quantityAfter,

                    'unit_cost' =>
                        $part->cost_price,

                    'note' =>
                        'Xuất cho phiếu '
                        . $lockedOrder->order_code
                        . (
                            !empty(
                                $validated['note']
                                ?? null
                            )
                                ? ' - '
                                    . trim(
                                        $validated['note']
                                    )
                                : ''
                        ),

                    'transaction_at' =>
                        now(),
                ]);

                $partsTotal =
                    ServiceOrderPart::where(
                        'service_order_id',
                        $lockedOrder->id
                    )
                        ->sum(
                            'line_total'
                        );

                $totalAmount =
                    (float)
                    $lockedOrder
                        ->service_total
                    +
                    (float)
                    $partsTotal;

                $lockedOrder->update([
                    'parts_total' =>
                        $partsTotal,

                    'total_amount' =>
                        $totalAmount,
                ]);
            }
        );

        return redirect()
            ->route(
                'staff.service-orders.show',
                $serviceOrder->id
            )
            ->with(
                'success',
                'Xuất phụ tùng cho phiếu bảo dưỡng thành công.'
            );
    }


    private function authorizeStaff(): void
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
            !in_array(
                $user->role->code,
                [
                    'STAFF',
                    'ADMIN',
                ],
                true
            )
        ) {
            abort(
                403,
                'Bạn không có quyền truy cập khu vực nhân viên.'
            );
        }
    }


    private function generateOrderCode(): string
    {
        do {
            $code =
                'SO'
                . now()->format('Ymd')
                . strtoupper(
                    Str::random(6)
                );
        } while (
            ServiceOrder::where(
                'order_code',
                $code
            )->exists()
        );

        return $code;
    }
}