<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Part;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StaffPartController extends Controller
{
    /**
     * Danh sách phụ tùng trong kho.
     */
    public function index()
    {
        $this->authorizeStaff();


        $parts =
            Part::query()
                ->orderBy('category')
                ->orderBy('name')
                ->get();


        /**
         * Tổng số loại phụ tùng.
         */
        $totalParts =
            $parts->count();


        /**
         * Tổng số lượng phụ tùng đang tồn.
         */
        $totalStockQuantity =
            $parts->sum(
                'stock_quantity'
            );


        /**
         * Số loại phụ tùng đang hoạt động
         * cần chú ý vì tồn kho <= tồn tối thiểu.
         */
        $lowStockCount =
            $parts
                ->filter(
                    function ($part) {
                        return
                            $part->is_active
                            &&
                            (int)
                            $part->stock_quantity
                            <=
                            (int)
                            $part->minimum_stock;
                    }
                )
                ->count();


        /**
         * Giá trị tồn kho hiện tại
         * tính theo giá nhập gần nhất.
         */
        $inventoryCostValue =
            $parts->sum(
                function ($part) {
                    return
                        (float)
                        $part->cost_price
                        *
                        (int)
                        $part->stock_quantity;
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
        if (
            !$part->is_active
        ) {
            return redirect()
                ->route(
                    'staff.parts.index'
                )
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


        /*
        |--------------------------------------------------------------------------
        | BUSINESS GUARD
        |--------------------------------------------------------------------------
        */

        if (
            !$part->is_active
        ) {
            return redirect()
                ->route(
                    'staff.parts.index'
                )
                ->with(
                    'error',
                    'Không thể nhập kho cho phụ tùng đã ngừng sử dụng.'
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
                    'quantity' => [
                        'bail',
                        'required',
                        'integer',
                        'min:1',
                    ],

                    'unit_cost' => [
                        'bail',
                        'required',
                        'numeric',
                        'min:0.01',
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
                    | QUANTITY
                    |--------------------------------------------------------------------------
                    */

                    'quantity.required' =>
                        'Vui lòng nhập số lượng phụ tùng cần nhập.',

                    'quantity.integer' =>
                        'Số lượng nhập kho phải là số nguyên.',

                    'quantity.min' =>
                        'Số lượng nhập kho phải từ 1 trở lên.',


                    /*
                    |--------------------------------------------------------------------------
                    | UNIT COST
                    |--------------------------------------------------------------------------
                    */

                    'unit_cost.required' =>
                        'Vui lòng nhập giá nhập trên mỗi đơn vị.',

                    'unit_cost.numeric' =>
                        'Giá nhập phải là một giá trị số hợp lệ.',

                    'unit_cost.min' =>
                        'Giá nhập phải lớn hơn 0.',


                    /*
                    |--------------------------------------------------------------------------
                    | NOTE
                    |--------------------------------------------------------------------------
                    */

                    'note.string' =>
                        'Ghi chú nhập kho không hợp lệ.',

                    'note.max' =>
                        'Ghi chú nhập kho không được vượt quá 1000 ký tự.',
                ]
            );


        $user =
            Auth::user();


        /*
        |--------------------------------------------------------------------------
        | STOCK TRANSACTION
        |--------------------------------------------------------------------------
        |
        | Lock bản ghi Part để tránh hai nhân viên
        | cùng đọc một stock_quantity cũ và cập nhật
        | sai tồn kho.
        |
        */

        DB::transaction(
            function () use (
                $part,
                $validated,
                $user
            ) {
                $lockedPart =
                    Part::query()
                        ->whereKey(
                            $part->id
                        )
                        ->lockForUpdate()
                        ->first();


                if (
                    !$lockedPart
                ) {
                    throw ValidationException::withMessages([
                        'part' =>
                            'Phụ tùng không còn tồn tại trong hệ thống.',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | RECHECK ACTIVE STATUS
                |--------------------------------------------------------------------------
                |
                | Phải kiểm tra lại sau khi lock,
                | vì trạng thái có thể đã thay đổi
                | sau thời điểm form được mở.
                |
                */

                if (
                    !$lockedPart->is_active
                ) {
                    throw ValidationException::withMessages([
                        'part' =>
                            'Phụ tùng này đã ngừng sử dụng nên không thể nhập kho.',
                    ]);
                }


                $quantityBefore =
                    (int)
                    $lockedPart
                        ->stock_quantity;


                $quantityAdded =
                    (int)
                    $validated[
                        'quantity'
                    ];


                $quantityAfter =
                    $quantityBefore
                    +
                    $quantityAdded;


                $unitCost =
                    (float)
                    $validated[
                        'unit_cost'
                    ];


                /*
                |--------------------------------------------------------------------------
                | UPDATE STOCK
                |--------------------------------------------------------------------------
                |
                | cost_price được sử dụng như
                | giá nhập gần nhất.
                |
                */

                $lockedPart->update([
                    'stock_quantity' =>
                        $quantityAfter,

                    'cost_price' =>
                        $unitCost,
                ]);


                /*
                |--------------------------------------------------------------------------
                | INVENTORY TRANSACTION
                |--------------------------------------------------------------------------
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
                        $validated[
                            'note'
                        ]
                        ?? null,

                    'transaction_at' =>
                        now(),
                ]);
            }
        );


        return redirect()
            ->route(
                'staff.parts.index'
            )
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
                'Bạn không có quyền truy cập khu vực quản lý kho.'
            );
        }
    }


    /**
     * Chuẩn hóa text nullable.
     *
     * Giữ nguyên xuống dòng,
     * chỉ loại khoảng trắng đầu/cuối.
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