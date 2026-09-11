<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\ServiceOrder;
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
            $serviceOrder->status !==
            'COMPLETED'
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

        if ($serviceOrder->invoice) {
            return redirect()
                ->route(
                    'staff.invoices.show',
                    $serviceOrder->invoice->id
                )
                ->with(
                    'error',
                    'Phiếu bảo dưỡng này đã có hóa đơn.'
                );
        }

        $serviceTotal = (float)
            $serviceOrder
                ->items
                ->sum('line_total');

        $partsTotal = (float)
            $serviceOrder
                ->parts
                ->sum('line_total');

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

        $validated = $request->validate(
            [
                'discount_amount' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'note' => [
                    'nullable',
                    'string',
                    'max:2000',
                ],
            ],
            [
                'discount_amount.numeric' =>
                    'Số tiền giảm giá phải là số.',

                'discount_amount.min' =>
                    'Số tiền giảm giá không được âm.',

                'note.max' =>
                    'Ghi chú không được vượt quá 2000 ký tự.',
            ]
        );

        $user = Auth::user();

        $invoice = DB::transaction(
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

                $lockedOrder->load([
                    'items.service',
                    'parts',
                    'invoice',
                ]);

                if (
                    $lockedOrder->status !==
                    'COMPLETED'
                ) {
                    throw ValidationException::withMessages([
                        'invoice' =>
                            'Phiếu bảo dưỡng chưa hoàn thành.',
                    ]);
                }

                if ($lockedOrder->invoice) {
                    throw ValidationException::withMessages([
                        'invoice' =>
                            'Phiếu bảo dưỡng này đã có hóa đơn.',
                    ]);
                }

                $serviceTotal = (float)
                    $lockedOrder
                        ->items
                        ->sum('line_total');

                $partsTotal = (float)
                    $lockedOrder
                        ->parts
                        ->sum('line_total');

                $subtotal =
                    $serviceTotal
                    +
                    $partsTotal;

                $discountAmount = (float)
                    (
                        $validated[
                            'discount_amount'
                        ]
                        ?? 0
                    );

                if (
                    $discountAmount >
                    $subtotal
                ) {
                    throw ValidationException::withMessages([
                        'discount_amount' =>
                            'Số tiền giảm giá không được lớn hơn tổng giá trị hóa đơn.',
                    ]);
                }

                $taxAmount = 0;

                $totalAmount =
                    $subtotal
                    -
                    $discountAmount
                    +
                    $taxAmount;

                $invoice = Invoice::create([
                    'invoice_code' =>
                        $this->generateInvoiceCode(),

                    'service_order_id' =>
                        $lockedOrder->id,

                    'customer_id' =>
                        $lockedOrder->customer_id,

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
                        !empty(
                            $validated['note']
                            ?? null
                        )
                            ? trim(
                                $validated['note']
                            )
                            : null,
                ]);

                foreach (
                    $lockedOrder->items
                    as $item
                ) {
                    InvoiceItem::create([
                        'invoice_id' =>
                            $invoice->id,

                        'item_type' =>
                            InvoiceItem::TYPE_SERVICE,

                        'source_id' =>
                            $item->id,

                        'item_code' =>
                            $item->service?->code,

                        'item_name' =>
                            $item->service_name,

                        'unit' =>
                            'dịch vụ',

                        'unit_price' =>
                            $item->unit_price,

                        'quantity' =>
                            $item->quantity,

                        'line_total' =>
                            $item->line_total,
                    ]);
                }

                foreach (
                    $lockedOrder->parts
                    as $part
                ) {
                    InvoiceItem::create([
                        'invoice_id' =>
                            $invoice->id,

                        'item_type' =>
                            InvoiceItem::TYPE_PART,

                        'source_id' =>
                            $part->id,

                        'item_code' =>
                            $part->part_code,

                        'item_name' =>
                            $part->part_name,

                        'unit' =>
                            $part->unit,

                        'unit_price' =>
                            $part->unit_price,

                        'quantity' =>
                            $part->quantity,

                        'line_total' =>
                            $part->line_total,
                    ]);
                }

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
            compact('invoice')
        );
    }


    /**
     * Xác nhận thanh toán hóa đơn.
     */
    public function pay(
        Request $request,
        Invoice $invoice
    ) {
        $this->authorizeStaff();

        $validated = $request->validate(
            [
                'payment_method' => [
                    'required',

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

                'payment_method.in' =>
                    'Phương thức thanh toán không hợp lệ.',
            ]
        );

        DB::transaction(
            function () use (
                $invoice,
                $validated
            ) {
                /**
                 * Khóa hóa đơn để tránh
                 * xác nhận thanh toán hai lần.
                 */
                $lockedInvoice =
                    Invoice::whereKey(
                        $invoice->id
                    )
                        ->lockForUpdate()
                        ->firstOrFail();

                /**
                 * Chỉ hóa đơn UNPAID
                 * mới được thanh toán.
                 */
                if (
                    $lockedInvoice->payment_status !==
                    Invoice::STATUS_UNPAID
                ) {
                    throw ValidationException::withMessages([
                        'payment_method' =>
                            'Hóa đơn này không còn ở trạng thái chờ thanh toán.',
                    ]);
                }

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
     * Quyền STAFF / ADMIN.
     */
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
                . now()->format('Ymd')
                . strtoupper(
                    Str::random(6)
                );
        } while (
            Invoice::where(
                'invoice_code',
                $code
            )->exists()
        );

        return $code;
    }
}