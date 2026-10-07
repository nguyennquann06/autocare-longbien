<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\ServiceOrder;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StaffInvoiceController extends Controller
{
    /**
     * Form lập hóa đơn.
     */
    public function create(
        ServiceOrder $serviceOrder
    ) {
        $this->authorizeStaff();


        $serviceOrder->load([
            'customer',
            'vehicle.brand',
            'vehicle.vehicleModel',
            'items.service',
            'parts.part',
            'invoice',
        ]);


        if (
            $serviceOrder->status
            !== 'COMPLETED'
        ) {
            return redirect()
                ->route(
                    'staff.service-orders.show',
                    $serviceOrder->id
                )
                ->with(
                    'error',
                    'Chỉ phiếu bảo dưỡng đã hoàn thành mới có thể lập hóa đơn.'
                );
        }


        if (
            $serviceOrder->invoice
        ) {
            return redirect()
                ->route(
                    'staff.invoices.show',
                    $serviceOrder
                        ->invoice
                        ->id
                )
                ->with(
                    'error',
                    'Phiếu bảo dưỡng này đã có hóa đơn.'
                );
        }


        $serviceTotal =
            (float)
            $serviceOrder
                ->items
                ->sum(
                    'line_total'
                );


        $partsTotal =
            (float)
            $serviceOrder
                ->parts
                ->sum(
                    'line_total'
                );


        $subtotal =
            $serviceTotal
            +
            $partsTotal;


        return view(
            'staff.invoices.create',
            compact(
                'serviceOrder',
                'serviceTotal',
                'partsTotal',
                'subtotal'
            )
        );
    }


    /**
     * Lưu hóa đơn.
     */
    public function store(
        Request $request,
        ServiceOrder $serviceOrder
    ) {
        $this->authorizeStaff();


        $serviceOrder->load([
            'items',
            'parts',
            'invoice',
        ]);


        if (
            $serviceOrder->status
            !== 'COMPLETED'
        ) {
            return redirect()
                ->route(
                    'staff.service-orders.show',
                    $serviceOrder->id
                )
                ->with(
                    'error',
                    'Chỉ phiếu bảo dưỡng đã hoàn thành mới có thể lập hóa đơn.'
                );
        }


        if (
            $serviceOrder->invoice
        ) {
            return redirect()
                ->route(
                    'staff.invoices.show',
                    $serviceOrder
                        ->invoice
                        ->id
                )
                ->with(
                    'error',
                    'Phiếu bảo dưỡng này đã có hóa đơn.'
                );
        }


        $currentServiceTotal =
            (float)
            $serviceOrder
                ->items
                ->sum(
                    'line_total'
                );


        $currentPartsTotal =
            (float)
            $serviceOrder
                ->parts
                ->sum(
                    'line_total'
                );


        $currentSubtotal =
            $currentServiceTotal
            +
            $currentPartsTotal;


        $request->merge([
            'note' =>
                $this->normalizeNullableText(
                    $request->input(
                        'note'
                    )
                ),
        ]);


        $validated =
            $request->validate(
                [
                    'discount_amount' => [
                        'bail',
                        'nullable',
                        'numeric',
                        'min:0',
                        'max:'.$currentSubtotal,
                    ],

                    'note' => [
                        'bail',
                        'nullable',
                        'string',
                        'max:2000',
                    ],
                ],
                [
                    'discount_amount.numeric' =>
                        'Số tiền giảm giá phải là một giá trị số hợp lệ.',

                    'discount_amount.min' =>
                        'Số tiền giảm giá không được nhỏ hơn 0.',

                    'discount_amount.max' =>
                        'Số tiền giảm giá không được lớn hơn tổng giá trị hóa đơn là '
                        .number_format(
                            $currentSubtotal,
                            0,
                            ',',
                            '.'
                        )
                        .' đ.',

                    'note.string' =>
                        'Ghi chú hóa đơn không hợp lệ.',

                    'note.max' =>
                        'Ghi chú hóa đơn không được vượt quá 2000 ký tự.',
                ]
            );


        $user =
            Auth::user();


        $invoice =
            DB::transaction(
                function () use (
                    $serviceOrder,
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


                    $lockedOrder->load([
                        'items.service',
                        'parts',
                        'invoice',
                    ]);


                    if (
                        $lockedOrder->status
                        !== 'COMPLETED'
                    ) {
                        throw ValidationException::withMessages([
                            'invoice' =>
                                'Phiếu bảo dưỡng không còn ở trạng thái hoàn thành nên chưa thể lập hóa đơn.',
                        ]);
                    }


                    if (
                        $lockedOrder->invoice
                    ) {
                        throw ValidationException::withMessages([
                            'invoice' =>
                                'Phiếu bảo dưỡng này đã có hóa đơn. Không thể lập thêm hóa đơn mới.',
                        ]);
                    }


                    $serviceTotal =
                        (float)
                        $lockedOrder
                            ->items
                            ->sum(
                                'line_total'
                            );


                    $partsTotal =
                        (float)
                        $lockedOrder
                            ->parts
                            ->sum(
                                'line_total'
                            );


                    $subtotal =
                        $serviceTotal
                        +
                        $partsTotal;


                    $discountAmount =
                        (float)
                        (
                            $validated[
                                'discount_amount'
                            ]
                            ?? 0
                        );


                    if (
                        $discountAmount
                        >
                        $subtotal
                    ) {
                        throw ValidationException::withMessages([
                            'discount_amount' =>
                                'Số tiền giảm giá không được lớn hơn tổng giá trị hóa đơn là '
                                .number_format(
                                    $subtotal,
                                    0,
                                    ',',
                                    '.'
                                )
                                .' đ.',
                        ]);
                    }


                    $taxAmount =
                        0;


                    $totalAmount =
                        $subtotal
                        -
                        $discountAmount
                        +
                        $taxAmount;


                    $invoice =
                        Invoice::query()
                            ->create([
                                'invoice_code' =>
                                    $this
                                        ->generateInvoiceCode(),

                                'service_order_id' =>
                                    $lockedOrder->id,

                                'customer_id' =>
                                    $lockedOrder
                                        ->customer_id,

                                'created_by' =>
                                    $user->id,

                                'service_total' =>
                                    $serviceTotal,

                                'parts_total' =>
                                    $partsTotal,

                                'subtotal' =>
                                    $subtotal,

                                'discount_amount' =>
                                    $discountAmount,

                                'tax_amount' =>
                                    $taxAmount,

                                'total_amount' =>
                                    $totalAmount,

                                'payment_status' =>
                                    Invoice::STATUS_UNPAID,

                                'payment_method' =>
                                    null,

                                'issued_at' =>
                                    now(),

                                'paid_at' =>
                                    null,

                                'note' =>
                                    $validated[
                                        'note'
                                    ]
                                    ?? null,
                            ]);


                    foreach (
                        $lockedOrder
                            ->items
                        as $item
                    ) {
                        InvoiceItem::query()
                            ->create([
                                'invoice_id' =>
                                    $invoice->id,

                                'item_type' =>
                                    InvoiceItem::TYPE_SERVICE,

                                'source_id' =>
                                    $item->id,

                                'item_code' =>
                                    $item
                                        ->service
                                        ?->code,

                                'item_name' =>
                                    $item
                                        ->service_name,

                                'unit' =>
                                    'dịch vụ',

                                'unit_price' =>
                                    $item
                                        ->unit_price,

                                'quantity' =>
                                    $item
                                        ->quantity,

                                'line_total' =>
                                    $item
                                        ->line_total,
                            ]);
                    }


                    foreach (
                        $lockedOrder
                            ->parts
                        as $part
                    ) {
                        InvoiceItem::query()
                            ->create([
                                'invoice_id' =>
                                    $invoice->id,

                                'item_type' =>
                                    InvoiceItem::TYPE_PART,

                                'source_id' =>
                                    $part->id,

                                'item_code' =>
                                    $part
                                        ->part_code,

                                'item_name' =>
                                    $part
                                        ->part_name,

                                'unit' =>
                                    $part
                                        ->unit,

                                'unit_price' =>
                                    $part
                                        ->unit_price,

                                'quantity' =>
                                    $part
                                        ->quantity,

                                'line_total' =>
                                    $part
                                        ->line_total,
                            ]);
                    }


                    ActivityLogger::log(
                        action:
                            'INVOICE_CREATED',

                        description:
                            'Đã lập hóa đơn '
                            .$invoice->invoice_code
                            .' cho phiếu '
                            .$lockedOrder->order_code
                            .'.',

                        entity:
                            $invoice,

                        oldValues:
                            null,

                        newValues: [
                            'invoice_code' =>
                                $invoice
                                    ->invoice_code,

                            'service_order_id' =>
                                $lockedOrder->id,

                            'customer_id' =>
                                $invoice
                                    ->customer_id,

                            'service_total' =>
                                (float)
                                $invoice
                                    ->service_total,

                            'parts_total' =>
                                (float)
                                $invoice
                                    ->parts_total,

                            'subtotal' =>
                                (float)
                                $invoice
                                    ->subtotal,

                            'discount_amount' =>
                                (float)
                                $invoice
                                    ->discount_amount,

                            'total_amount' =>
                                (float)
                                $invoice
                                    ->total_amount,

                            'payment_status' =>
                                $invoice
                                    ->payment_status,
                        ]
                    );


                    return $invoice;
                }
            );


        return redirect()
            ->route(
                'staff.invoices.show',
                $invoice->id
            )
            ->with(
                'success',
                'Lập hóa đơn thành công.'
            );
    }


    /**
     * Chi tiết hóa đơn.
     */
    public function show(
        Invoice $invoice
    ) {
        $this->authorizeStaff();


        $invoice->load([
            'customer',
            'creator',
            'items',
            'serviceOrder.vehicle.brand',
            'serviceOrder.vehicle.vehicleModel',
            'serviceOrder.technician',
        ]);


        return view(
            'staff.invoices.show',
            compact(
                'invoice'
            )
        );
    }


    /**
     * Xác nhận thanh toán.
     */
    public function pay(
        Request $request,
        Invoice $invoice
    ) {
        $this->authorizeStaff();


        $validated =
            $request->validate(
                [
                    'payment_method' => [
                        'bail',
                        'required',
                        'string',

                        Rule::in([
                            Invoice::METHOD_CASH,
                            Invoice::METHOD_BANK_TRANSFER,
                            Invoice::METHOD_CARD,
                        ]),
                    ],
                ],
                [
                    'payment_method.required' =>
                        'Vui lòng chọn phương thức thanh toán.',

                    'payment_method.string' =>
                        'Phương thức thanh toán không hợp lệ.',

                    'payment_method.in' =>
                        'Phương thức thanh toán không hợp lệ.',
                ]
            );


        DB::transaction(
            function () use (
                $invoice,
                $validated
            ) {
                $lockedInvoice =
                    Invoice::query()
                        ->whereKey(
                            $invoice->id
                        )
                        ->lockForUpdate()
                        ->firstOrFail();


                if (
                    $lockedInvoice
                        ->payment_status
                    !==
                    Invoice::STATUS_UNPAID
                ) {
                    $message =
                        $lockedInvoice
                            ->payment_status
                        ===
                        Invoice::STATUS_PAID
                            ? 'Hóa đơn này đã được thanh toán trước đó.'
                            : 'Hóa đơn này không còn ở trạng thái chờ thanh toán.';


                    throw ValidationException::withMessages([
                        'payment_method' =>
                            $message,
                    ]);
                }


                $oldValues = [
                    'payment_status' =>
                        $lockedInvoice
                            ->payment_status,

                    'payment_method' =>
                        $lockedInvoice
                            ->payment_method,

                    'paid_at' =>
                        $lockedInvoice
                            ->paid_at
                            ?->toDateTimeString(),
                ];


                $lockedInvoice->update([
                    'payment_status' =>
                        Invoice::STATUS_PAID,

                    'payment_method' =>
                        $validated[
                            'payment_method'
                        ],

                    'paid_at' =>
                        now(),
                ]);


                ActivityLogger::log(
                    action:
                        'INVOICE_PAID',

                    description:
                        'Đã xác nhận thanh toán hóa đơn '
                        .$lockedInvoice
                            ->invoice_code
                        .'.',

                    entity:
                        $lockedInvoice,

                    oldValues:
                        $oldValues,

                    newValues: [
                        'payment_status' =>
                            $lockedInvoice
                                ->payment_status,

                        'payment_method' =>
                            $lockedInvoice
                                ->payment_method,

                        'paid_at' =>
                            $lockedInvoice
                                ->paid_at
                                ?->toDateTimeString(),

                        'total_amount' =>
                            (float)
                            $lockedInvoice
                                ->total_amount,
                    ]
                );
            }
        );


        return redirect()
            ->route(
                'staff.invoices.show',
                $invoice->id
            )
            ->with(
                'success',
                'Xác nhận thanh toán thành công.'
            );
    }


    /**
     * STAFF / ADMIN.
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
                'Bạn không có quyền truy cập chức năng hóa đơn.'
            );
        }
    }


    /**
     * Sinh mã hóa đơn.
     */
    private function generateInvoiceCode(): string
    {
        do {
            $code =
                'INV'
                .now()->format(
                    'Ymd'
                )
                .strtoupper(
                    Str::random(
                        6
                    )
                );
        } while (
            Invoice::query()
                ->where(
                    'invoice_code',
                    $code
                )
                ->exists()
        );


        return $code;
    }


    /**
     * Chuẩn hóa text nullable.
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