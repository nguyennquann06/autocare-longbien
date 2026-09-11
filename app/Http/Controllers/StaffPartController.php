<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Part;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StaffPartController extends Controller
{
    /**
     * Danh sách phụ tùng trong kho.
     */
    public function index()
    {
        $this->authorizeStaff();

        $parts = Part::query()
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        /**
         * Tổng số loại phụ tùng.
         */
        $totalParts = $parts->count();

        /**
         * Tổng số lượng phụ tùng đang tồn.
         */
        $totalStockQuantity = $parts->sum(
            'stock_quantity'
        );

        /**
         * Số loại phụ tùng cần chú ý.
         */
        $lowStockCount = $parts
            ->filter(
                function ($part) {
                    return
                        $part->stock_quantity <=
                        $part->minimum_stock;
                }
            )
            ->count();

        /**
         * Giá trị tồn kho hiện tại
         * tính theo giá nhập gần nhất.
         */
        $inventoryCostValue = $parts->sum(
            function ($part) {
                return
                    (float) $part->cost_price
                    *
                    (int) $part->stock_quantity;
            }
        );

        return view(
            'staff.parts.index',
            compact(
                'parts',
                'totalParts',
                'totalStockQuantity',
                'lowStockCount',
                'inventoryCostValue'
            )
        );
    }


    /**
     * Form nhập kho cho một phụ tùng.
     */
    public function showStockInForm(
        Part $part
    ) {
        $this->authorizeStaff();

        /**
         * Phụ tùng ngừng sử dụng
         * không cho nhập kho.
         */
        if (!$part->is_active) {
            return redirect()
                ->route('staff.parts.index')
                ->with(
                    'error',
                    'Không thể nhập kho cho phụ tùng đã ngừng sử dụng.'
                );
        }

        return view(
            'staff.parts.stock-in',
            compact('part')
        );
    }


    /**
     * Xử lý nhập kho.
     */
    public function stockIn(
        Request $request,
        Part $part
    ) {
        $this->authorizeStaff();

        if (!$part->is_active) {
            return redirect()
                ->route('staff.parts.index')
                ->with(
                    'error',
                    'Không thể nhập kho cho phụ tùng đã ngừng sử dụng.'
                );
        }

        $validated = $request->validate(
            [
                'quantity' => [
                    'required',
                    'integer',
                    'min:1',
                ],

                'unit_cost' => [
                    'required',
                    'numeric',
                    'min:0.01',
                ],

                'note' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ],
            [
                'quantity.required' =>
                    'Vui lòng nhập số lượng.',

                'quantity.integer' =>
                    'Số lượng phải là số nguyên.',

                'quantity.min' =>
                    'Số lượng nhập phải lớn hơn 0.',

                'unit_cost.required' =>
                    'Vui lòng nhập giá nhập.',

                'unit_cost.numeric' =>
                    'Giá nhập phải là số.',

                'unit_cost.min' =>
                    'Giá nhập phải lớn hơn 0.',

                'note.max' =>
                    'Ghi chú không được vượt quá 1000 ký tự.',
            ]
        );

        $user = Auth::user();

        DB::transaction(
            function () use (
                $part,
                $validated,
                $user
            ) {
                /**
                 * Đọc lại bản ghi và khóa
                 * cho tới khi transaction kết thúc.
                 *
                 * Việc này giúp tránh hai nhân viên
                 * cùng cập nhật tồn kho sai lệch.
                 */
                $lockedPart = Part::whereKey(
                    $part->id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                /**
                 * Kiểm tra lại sau khi khóa.
                 */
                if (!$lockedPart->is_active) {
                    abort(
                        422,
                        'Phụ tùng đã ngừng sử dụng.'
                    );
                }

                $quantityBefore =
                    (int) $lockedPart
                        ->stock_quantity;

                $quantityAdded =
                    (int) $validated[
                        'quantity'
                    ];

                $quantityAfter =
                    $quantityBefore
                    +
                    $quantityAdded;

                $unitCost =
                    (float) $validated[
                        'unit_cost'
                    ];

                /**
                 * Cập nhật tồn kho.
                 *
                 * cost_price được coi là
                 * giá nhập gần nhất.
                 */
                $lockedPart->update([
                    'stock_quantity' =>
                        $quantityAfter,

                    'cost_price' =>
                        $unitCost,
                ]);

                /**
                 * Ghi nhật ký nhập kho.
                 */
                InventoryTransaction::create([
                    'part_id' =>
                        $lockedPart->id,

                    'service_order_id' =>
                        null,

                    'performed_by' =>
                        $user->id,

                    'transaction_type' =>
                        InventoryTransaction::TYPE_IN,

                    'quantity' =>
                        $quantityAdded,

                    'quantity_before' =>
                        $quantityBefore,

                    'quantity_after' =>
                        $quantityAfter,

                    'unit_cost' =>
                        $unitCost,

                    'note' =>
                        !empty(
                            $validated['note']
                            ?? null
                        )
                            ? trim(
                                $validated['note']
                            )
                            : null,

                    'transaction_at' =>
                        now(),
                ]);
            }
        );

        return redirect()
            ->route('staff.parts.index')
            ->with(
                'success',
                'Nhập kho phụ tùng thành công.'
            );
    }


    /**
     * Chỉ STAFF và ADMIN được
     * truy cập chức năng quản lý kho.
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
                'Bạn không có quyền truy cập khu vực quản lý kho.'
            );
        }
    }
}