<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(
            'inventory_transactions',
            function (Blueprint $table) {
                $table->id();

                /**
                 * Phụ tùng được thay đổi tồn kho.
                 */
                $table->foreignId('part_id')
                    ->constrained('parts')
                    ->restrictOnDelete();

                /**
                 * Phiếu bảo dưỡng liên quan.
                 *
                 * Có giá trị khi xuất phụ tùng
                 * cho một Service Order.
                 */
                $table->foreignId(
                    'service_order_id'
                )
                    ->nullable()
                    ->constrained('service_orders')
                    ->nullOnDelete();

                /**
                 * Người thực hiện giao dịch kho.
                 */
                $table->foreignId(
                    'performed_by'
                )
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                /**
                 * Loại giao dịch:
                 *
                 * IN
                 * OUT
                 * ADJUSTMENT
                 */
                $table->string(
                    'transaction_type',
                    30
                );

                /**
                 * Số lượng thay đổi.
                 *
                 * Luôn lưu số dương.
                 * IN hay OUT được xác định bằng
                 * transaction_type.
                 */
                $table->unsignedInteger(
                    'quantity'
                );

                /**
                 * Tồn trước giao dịch.
                 */
                $table->unsignedInteger(
                    'quantity_before'
                );

                /**
                 * Tồn sau giao dịch.
                 */
                $table->unsignedInteger(
                    'quantity_after'
                );

                /**
                 * Giá nhập / giá tham chiếu
                 * tại thời điểm giao dịch.
                 */
                $table->decimal(
                    'unit_cost',
                    12,
                    2
                )->nullable();

                /**
                 * Ghi chú giao dịch.
                 *
                 * Ví dụ:
                 * Nhập hàng nhà cung cấp A
                 * Xuất cho phiếu SO...
                 * Điều chỉnh kiểm kê
                 */
                $table->text('note')
                    ->nullable();

                /**
                 * Thời điểm giao dịch thực tế.
                 */
                $table->dateTime(
                    'transaction_at'
                );

                $table->timestamps();

                $table->index(
                    'transaction_type'
                );

                $table->index(
                    'transaction_at'
                );
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(
            'inventory_transactions'
        );
    }
};