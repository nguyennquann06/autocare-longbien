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
    /**
     * Giới hạn ODO hợp lý cho hệ thống.
     */
    private const MAX_MILEAGE = 5000000;


    /**
     * Form tạo phiếu bảo dưỡng.
     */
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


        if (
            $appointment->status
            !== 'CONFIRMED'
        ) {
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


        if (
            $appointment->serviceOrder
        ) {
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


        $technicians =
            User::whereHas(
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


    /**
     * Lưu phiếu bảo dưỡng.
     */
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


        /*
        |--------------------------------------------------------------------------
        | BUSINESS GUARD
        |--------------------------------------------------------------------------
        */

        if (
            $appointment->status
            !== 'CONFIRMED'
        ) {
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


        if (
            $appointment->serviceOrder
        ) {
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


        /*
        |--------------------------------------------------------------------------
        | NORMALIZE TEXT INPUT
        |--------------------------------------------------------------------------
        */

        $request->merge([
            'vehicle_condition' =>
                $this->normalizeNullableText(
                    $request->input(
                        'vehicle_condition'
                    )
                ),

            'diagnosis' =>
                $this->normalizeNullableText(
                    $request->input(
                        'diagnosis'
                    )
                ),

            'staff_note' =>
                $this->normalizeNullableText(
                    $request->input(
                        'staff_note'
                    )
                ),
        ]);


        $currentMileage =
            (int)
            $appointment
                ->vehicle
                ->current_mileage;


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

                        Rule::exists(
                            'users',
                            'id'
                        )->where(
                            function ($query) {
                                $query->whereIn(
                                    'role_id',
                                    function (
                                        $subQuery
                                    ) {
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
                        'bail',
                        'required',
                        'integer',
                        'min:' . $currentMileage,
                        'max:' . self::MAX_MILEAGE,
                    ],


                    'vehicle_condition' => [
                        'bail',
                        'nullable',
                        'string',
                        'max:2000',
                    ],


                    'diagnosis' => [
                        'bail',
                        'nullable',
                        'string',
                        'max:2000',
                    ],


                    'staff_note' => [
                        'bail',
                        'nullable',
                        'string',
                        'max:1000',
                    ],
                ],
                [
                    /*
                    |--------------------------------------------------------------------------
                    | TECHNICIAN
                    |--------------------------------------------------------------------------
                    */

                    'technician_id.required' =>
                        'Vui lòng chọn kỹ thuật viên phụ trách.',

                    'technician_id.integer' =>
                        'Kỹ thuật viên được chọn không hợp lệ.',

                    'technician_id.exists' =>
                        'Kỹ thuật viên không tồn tại hoặc tài khoản được chọn không có vai trò kỹ thuật viên.',


                    /*
                    |--------------------------------------------------------------------------
                    | ODO
                    |--------------------------------------------------------------------------
                    */

                    'received_mileage.required' =>
                        'Vui lòng nhập ODO khi tiếp nhận xe.',

                    'received_mileage.integer' =>
                        'ODO phải là số nguyên, không được nhập số thập phân.',

                    'received_mileage.min' =>
                        'ODO khi tiếp nhận không được nhỏ hơn ODO hiện tại của xe là '
                        . number_format(
                            $currentMileage,
                            0,
                            ',',
                            '.'
                        )
                        . ' km.',

                    'received_mileage.max' =>
                        'ODO không được vượt quá '
                        . number_format(
                            self::MAX_MILEAGE,
                            0,
                            ',',
                            '.'
                        )
                        . ' km.',


                    /*
                    |--------------------------------------------------------------------------
                    | VEHICLE CONDITION
                    |--------------------------------------------------------------------------
                    */

                    'vehicle_condition.string' =>
                        'Tình trạng xe khi tiếp nhận không hợp lệ.',

                    'vehicle_condition.max' =>
                        'Tình trạng xe khi tiếp nhận không được vượt quá 2000 ký tự.',


                    /*
                    |--------------------------------------------------------------------------
                    | DIAGNOSIS
                    |--------------------------------------------------------------------------
                    */

                    'diagnosis.string' =>
                        'Chẩn đoán ban đầu không hợp lệ.',

                    'diagnosis.max' =>
                        'Chẩn đoán ban đầu không được vượt quá 2000 ký tự.',


                    /*
                    |--------------------------------------------------------------------------
                    | STAFF NOTE
                    |--------------------------------------------------------------------------
                    */

                    'staff_note.string' =>
                        'Ghi chú nhân viên không hợp lệ.',

                    'staff_note.max' =>
                        'Ghi chú nhân viên không được vượt quá 1000 ký tự.',
                ]
            );


        $user =
            Auth::user();


        /*
        |--------------------------------------------------------------------------
        | CREATE SERVICE ORDER
        |--------------------------------------------------------------------------
        |
        | Lock Appointment + Vehicle để:
        |
        | - tránh double-submit tạo 2 phiếu;
        | - tránh ODO bị thay đổi đồng thời;
        | - kiểm tra lại trạng thái ngay trước khi ghi DB.
        |
        */

        $serviceOrder =
            DB::transaction(
                function () use (
                    $appointment,
                    $validated,
                    $user
                ) {
                    $lockedAppointment =
                        Appointment::query()
                            ->whereKey(
                                $appointment->id
                            )
                            ->lockForUpdate()
                            ->firstOrFail();


                    $lockedAppointment->load([
                        'services',
                        'serviceOrder',
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | RECHECK APPOINTMENT
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $lockedAppointment->status
                        !== 'CONFIRMED'
                    ) {
                        throw ValidationException::withMessages([
                            'appointment' =>
                                'Lịch hẹn không còn ở trạng thái đã xác nhận nên không thể tạo phiếu bảo dưỡng.',
                        ]);
                    }


                    if (
                        $lockedAppointment
                            ->serviceOrder
                    ) {
                        throw ValidationException::withMessages([
                            'appointment' =>
                                'Lịch hẹn này đã có phiếu bảo dưỡng. Không thể tạo thêm phiếu mới.',
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | LOCK VEHICLE
                    |--------------------------------------------------------------------------
                    */

                    $lockedVehicle =
                        $lockedAppointment
                            ->vehicle()
                            ->lockForUpdate()
                            ->firstOrFail();


                    $latestMileage =
                        (int)
                        $lockedVehicle
                            ->current_mileage;


                    if (
                        (int)
                        $validated[
                            'received_mileage'
                        ]
                        <
                        $latestMileage
                    ) {
                        throw ValidationException::withMessages([
                            'received_mileage' =>
                                'ODO khi tiếp nhận không được nhỏ hơn ODO hiện tại của xe là '
                                . number_format(
                                    $latestMileage,
                                    0,
                                    ',',
                                    '.'
                                )
                                . ' km.',
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | SERVICE SNAPSHOT TOTAL
                    |--------------------------------------------------------------------------
                    */

                    $serviceTotal =
                        $lockedAppointment
                            ->services
                            ->sum(
                                fn ($service) =>
                                    (float)
                                    $service
                                        ->pivot
                                        ->price
                            );


                    /*
                    |--------------------------------------------------------------------------
                    | CREATE ORDER
                    |--------------------------------------------------------------------------
                    */

                    $serviceOrder =
                        ServiceOrder::create([
                            'order_code' =>
                                $this
                                    ->generateOrderCode(),

                            'appointment_id' =>
                                $lockedAppointment
                                    ->id,

                            'customer_id' =>
                                $lockedAppointment
                                    ->customer_id,

                            'vehicle_id' =>
                                $lockedAppointment
                                    ->vehicle_id,

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


                    /*
                    |--------------------------------------------------------------------------
                    | CREATE SERVICE ORDER ITEMS
                    |--------------------------------------------------------------------------
                    */

                    foreach (
                        $lockedAppointment
                            ->services
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


                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE VEHICLE ODO
                    |--------------------------------------------------------------------------
                    */

                    $lockedVehicle->update([
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


    /**
     * Chi tiết phiếu bảo dưỡng.
     */
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


        $availableParts =
            Part::where(
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


    /**
     * Xuất phụ tùng cho phiếu bảo dưỡng.
     */
    public function addPart(
        Request $request,
        ServiceOrder $serviceOrder
    ) {
        $this->authorizeStaff();


        /*
        |--------------------------------------------------------------------------
        | BUSINESS GUARD
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | NORMALIZE INPUT
        |--------------------------------------------------------------------------
        */

        $request->merge([
            'note' =>
                $this->normalizeNullableText(
                    $request->input(
                        'note'
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
                    'part_id' => [
                        'bail',
                        'required',
                        'integer',

                        Rule::exists(
                            'parts',
                            'id'
                        )->where(
                            function ($query) {
                                $query->where(
                                    'is_active',
                                    true
                                );
                            }
                        ),
                    ],


                    'quantity' => [
                        'bail',
                        'required',
                        'integer',
                        'min:1',
                    ],


                    'note' => [
                        'bail',
                        'nullable',
                        'string',
                        'max:1000',
                    ],
                ],
                [
                    /*
                    |--------------------------------------------------------------------------
                    | PART
                    |--------------------------------------------------------------------------
                    */

                    'part_id.required' =>
                        'Vui lòng chọn phụ tùng cần xuất.',

                    'part_id.integer' =>
                        'Phụ tùng được chọn không hợp lệ.',

                    'part_id.exists' =>
                        'Phụ tùng không tồn tại hoặc đã ngừng sử dụng.',


                    /*
                    |--------------------------------------------------------------------------
                    | QUANTITY
                    |--------------------------------------------------------------------------
                    */

                    'quantity.required' =>
                        'Vui lòng nhập số lượng phụ tùng cần xuất.',

                    'quantity.integer' =>
                        'Số lượng phụ tùng phải là số nguyên.',

                    'quantity.min' =>
                        'Số lượng phụ tùng phải từ 1 trở lên.',


                    /*
                    |--------------------------------------------------------------------------
                    | NOTE
                    |--------------------------------------------------------------------------
                    */

                    'note.string' =>
                        'Ghi chú xuất phụ tùng không hợp lệ.',

                    'note.max' =>
                        'Ghi chú xuất phụ tùng không được vượt quá 1000 ký tự.',
                ]
            );


        $user =
            Auth::user();


        /*
        |--------------------------------------------------------------------------
        | STOCK TRANSACTION
        |--------------------------------------------------------------------------
        |
        | Lock cả Service Order và Part để tránh:
        |
        | - xuất phụ tùng sau khi phiếu vừa hoàn thành;
        | - hai nhân viên cùng xuất một tồn kho;
        | - stock_quantity bị âm;
        | - dùng phụ tùng vừa bị khóa / ngừng sử dụng.
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


                /*
                |--------------------------------------------------------------------------
                | LOCK PART
                |--------------------------------------------------------------------------
                */

                $part =
                    Part::query()
                        ->whereKey(
                            $validated[
                                'part_id'
                            ]
                        )
                        ->lockForUpdate()
                        ->first();


                if (
                    !$part
                ) {
                    throw ValidationException::withMessages([
                        'part_id' =>
                            'Phụ tùng không còn tồn tại trong hệ thống.',
                    ]);
                }


                if (
                    !$part->is_active
                ) {
                    throw ValidationException::withMessages([
                        'part_id' =>
                            'Phụ tùng này đã ngừng sử dụng.',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | CHECK STOCK
                |--------------------------------------------------------------------------
                */

                $quantity =
                    (int)
                    $validated[
                        'quantity'
                    ];


                $quantityBefore =
                    (int)
                    $part
                        ->stock_quantity;


                if (
                    $quantityBefore <= 0
                ) {
                    throw ValidationException::withMessages([
                        'quantity' =>
                            'Phụ tùng '
                            . $part->name
                            . ' hiện đã hết hàng.',
                    ]);
                }


                if (
                    $quantity
                    >
                    $quantityBefore
                ) {
                    throw ValidationException::withMessages([
                        'quantity' =>
                            'Số lượng yêu cầu vượt quá tồn kho. Hiện chỉ còn '
                            . number_format(
                                $quantityBefore,
                                0,
                                ',',
                                '.'
                            )
                            . ' '
                            . $part->unit
                            . '.',
                    ]);
                }


                $quantityAfter =
                    $quantityBefore
                    -
                    $quantity;


                /*
                |--------------------------------------------------------------------------
                | SERVICE ORDER PART
                |--------------------------------------------------------------------------
                */

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


                if (
                    $existingOrderPart
                ) {
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
                                $validated[
                                    'note'
                                ]
                                ?? null
                            )
                                ? $validated[
                                    'note'
                                ]
                                : $existingOrderPart
                                    ->note,
                    ]);
                } else {
                    $unitPrice =
                        (float)
                        $part
                            ->selling_price;


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
                            $validated[
                                'note'
                            ]
                            ?? null,
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | UPDATE STOCK
                |--------------------------------------------------------------------------
                */

                $part->update([
                    'stock_quantity' =>
                        $quantityAfter,
                ]);


                /*
                |--------------------------------------------------------------------------
                | INVENTORY TRANSACTION
                |--------------------------------------------------------------------------
                */

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
                        . $lockedOrder
                            ->order_code
                        . (
                            !empty(
                                $validated[
                                    'note'
                                ]
                                ?? null
                            )
                                ? ' - '
                                    . $validated[
                                        'note'
                                    ]
                                : ''
                        ),

                    'transaction_at' =>
                        now(),
                ]);


                /*
                |--------------------------------------------------------------------------
                | RECALCULATE ORDER TOTAL
                |--------------------------------------------------------------------------
                */

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


    /**
     * Kiểm tra STAFF / ADMIN.
     */
    private function authorizeStaff(): void
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


    /**
     * Sinh mã phiếu bảo dưỡng.
     */
    private function generateOrderCode(): string
    {
        do {
            $code =
                'SO'
                . now()->format(
                    'Ymd'
                )
                . strtoupper(
                    Str::random(
                        6
                    )
                );
        } while (
            ServiceOrder::where(
                'order_code',
                $code
            )->exists()
        );


        return $code;
    }


    /**
     * Chuẩn hóa text nullable.
     *
     * Chỉ trim đầu/cuối để giữ nguyên
     * xuống dòng trong ghi chú nghiệp vụ.
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