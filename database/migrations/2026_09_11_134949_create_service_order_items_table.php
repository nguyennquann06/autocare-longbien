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
            'service_order_items',
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
                 * Dịch vụ gốc.
                 *
                 * Nếu dịch vụ sau này bị xóa,
                 * lịch sử phiếu vẫn cần tồn tại.
                 */
                $table->foreignId('service_id')
                    ->nullable()
                    ->constrained('services')
                    ->nullOnDelete();

                /**
                 * Snapshot tên dịch vụ.
                 *
                 * Giúp lịch sử không thay đổi nếu
                 * Admin đổi tên dịch vụ sau này.
                 */
                $table->string(
                    'service_name',
                    150
                );

                /**
                 * Giá thực tế của dịch vụ.
                 */
                $table->decimal(
                    'unit_price',
                    12,
                    2
                )->default(0);

                /**
                 * Số lượng.
                 *
                 * Dịch vụ thường là 1,
                 * nhưng vẫn để quantity để
                 * thiết kế linh hoạt hơn.
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
                 * Thời gian dự kiến.
                 */
                $table->unsignedInteger(
                    'estimated_duration_minutes'
                )->nullable();

                /**
                 * Trạng thái riêng của hạng mục.
                 *
                 * PENDING
                 * IN_PROGRESS
                 * COMPLETED
                 * CANCELLED
                 */
                $table->string(
                    'status',
                    30
                )->default('PENDING');

                /**
                 * Ghi chú kỹ thuật cho từng hạng mục.
                 */
                $table->text(
                    'technician_note'
                )->nullable();

                $table->timestamps();

                /**
                 * Không cho cùng một dịch vụ
                 * xuất hiện hai lần trong
                 * một phiếu bảo dưỡng.
                 */
                $table->unique([
                    'service_order_id',
                    'service_id',
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
            'service_order_items'
        );
    }
};