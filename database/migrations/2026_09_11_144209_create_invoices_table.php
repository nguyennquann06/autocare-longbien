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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            /**
             * Mã hóa đơn.
             *
             * Ví dụ:
             * INV20260911ABC123
             */
            $table->string('invoice_code', 30)
                ->unique();

            /**
             * Một phiếu bảo dưỡng
             * chỉ có tối đa một hóa đơn.
             */
            $table->foreignId('service_order_id')
                ->unique()
                ->constrained('service_orders')
                ->restrictOnDelete();

            /**
             * Khách hàng của hóa đơn.
             */
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->restrictOnDelete();

            /**
             * Nhân viên lập hóa đơn.
             */
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /**
             * Tổng tiền dịch vụ tại thời điểm
             * lập hóa đơn.
             */
            $table->decimal(
                'service_total',
                12,
                2
            )->default(0);

            /**
             * Tổng tiền phụ tùng.
             */
            $table->decimal(
                'parts_total',
                12,
                2
            )->default(0);

            /**
             * Tổng trước giảm giá.
             */
            $table->decimal(
                'subtotal',
                12,
                2
            )->default(0);

            /**
             * Số tiền giảm giá.
             */
            $table->decimal(
                'discount_amount',
                12,
                2
            )->default(0);

            /**
             * Thuế.
             *
             * Hiện mặc định bằng 0.
             * Có thể dùng khi mở rộng sau này.
             */
            $table->decimal(
                'tax_amount',
                12,
                2
            )->default(0);

            /**
             * Tổng tiền cuối cùng.
             *
             * total_amount =
             * subtotal
             * - discount_amount
             * + tax_amount
             */
            $table->decimal(
                'total_amount',
                12,
                2
            )->default(0);

            /**
             * Trạng thái thanh toán:
             *
             * UNPAID
             * PAID
             * CANCELLED
             */
            $table->string(
                'payment_status',
                30
            )->default('UNPAID');

            /**
             * Phương thức thanh toán:
             *
             * CASH
             * BANK_TRANSFER
             * CARD
             *
             * Null khi chưa thanh toán.
             */
            $table->string(
                'payment_method',
                30
            )->nullable();

            /**
             * Thời điểm lập hóa đơn.
             */
            $table->dateTime('issued_at');

            /**
             * Thời điểm thanh toán.
             */
            $table->dateTime('paid_at')
                ->nullable();

            /**
             * Ghi chú hóa đơn.
             */
            $table->text('note')
                ->nullable();

            $table->timestamps();

            $table->index('payment_status');

            $table->index('issued_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};