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
            'invoice_items',
            function (Blueprint $table) {
                $table->id();

                /**
                 * Hóa đơn.
                 */
                $table->foreignId('invoice_id')
                    ->constrained('invoices')
                    ->cascadeOnDelete();

                /**
                 * Loại dòng hóa đơn:
                 *
                 * SERVICE
                 * PART
                 */
                $table->string(
                    'item_type',
                    30
                );

                /**
                 * ID dữ liệu nguồn.
                 *
                 * SERVICE:
                 * service_order_items.id
                 *
                 * PART:
                 * service_order_parts.id
                 *
                 * Chỉ dùng tham chiếu lịch sử,
                 * không tạo foreign key vì có
                 * hai loại bảng nguồn khác nhau.
                 */
                $table->unsignedBigInteger(
                    'source_id'
                )->nullable();

                /**
                 * Mã mặt hàng/dịch vụ snapshot.
                 */
                $table->string(
                    'item_code',
                    50
                )->nullable();

                /**
                 * Tên snapshot.
                 */
                $table->string(
                    'item_name',
                    150
                );

                /**
                 * Đơn vị tính.
                 *
                 * Với dịch vụ có thể dùng
                 * "dịch vụ".
                 */
                $table->string(
                    'unit',
                    30
                )->nullable();

                /**
                 * Đơn giá.
                 */
                $table->decimal(
                    'unit_price',
                    12,
                    2
                )->default(0);

                /**
                 * Số lượng.
                 */
                $table->unsignedInteger(
                    'quantity'
                )->default(1);

                /**
                 * Thành tiền.
                 */
                $table->decimal(
                    'line_total',
                    12,
                    2
                )->default(0);

                $table->timestamps();

                $table->index('item_type');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(
            'invoice_items'
        );
    }
};