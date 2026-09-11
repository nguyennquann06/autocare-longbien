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
            'service_order_parts',
            function (Blueprint $table) {
                $table->id();

                /**
                 * Phiếu bảo dưỡng.
                 */
                $table->foreignId(
                    'service_order_id'
                )
                    ->constrained('service_orders')
                    ->cascadeOnDelete();

                /**
                 * Liên kết phụ tùng hiện tại.
                 *
                 * Cho phép null để lịch sử vẫn
                 * tồn tại nếu dữ liệu phụ tùng
                 * sau này không còn.
                 */
                $table->foreignId('part_id')
                    ->nullable()
                    ->constrained('parts')
                    ->nullOnDelete();

                /**
                 * Snapshot mã phụ tùng.
                 */
                $table->string(
                    'part_code',
                    50
                );

                /**
                 * Snapshot tên phụ tùng.
                 */
                $table->string(
                    'part_name',
                    150
                );

                /**
                 * Snapshot đơn vị tính.
                 */
                $table->string(
                    'unit',
                    30
                );

                /**
                 * Giá bán tại thời điểm sử dụng.
                 */
                $table->decimal(
                    'unit_price',
                    12,
                    2
                )->default(0);

                /**
                 * Số lượng sử dụng.
                 */
                $table->unsignedInteger(
                    'quantity'
                )->default(1);

                /**
                 * Thành tiền.
                 *
                 * line_total =
                 * unit_price * quantity
                 */
                $table->decimal(
                    'line_total',
                    12,
                    2
                )->default(0);

                /**
                 * Ghi chú.
                 */
                $table->text('note')
                    ->nullable();

                $table->timestamps();

                /**
                 * Một loại phụ tùng chỉ xuất hiện
                 * một lần trong một phiếu.
                 */
                $table->unique([
                    'service_order_id',
                    'part_id',
                ]);
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(
            'service_order_parts'
        );
    }
};