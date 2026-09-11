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
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();

            /**
             * Mã phiếu bảo dưỡng.
             *
             * Ví dụ:
             * SO202609120001
             */
            $table->string('order_code', 30)
                ->unique();

            /**
             * Lịch hẹn nguồn.
             *
             * Có thể null vì sau này khách
             * có thể mang xe tới trực tiếp
             * mà không đặt lịch trước.
             */
            $table->foreignId('appointment_id')
                ->nullable()
                ->unique()
                ->constrained('appointments')
                ->nullOnDelete();

            /**
             * Khách hàng sở hữu xe.
             */
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->restrictOnDelete();

            /**
             * Xe được tiếp nhận bảo dưỡng.
             */
            $table->foreignId('vehicle_id')
                ->constrained('vehicles')
                ->restrictOnDelete();

            /**
             * Nhân viên tiếp nhận phiếu.
             *
             * Đây là user có role STAFF hoặc ADMIN.
             */
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /**
             * Kỹ thuật viên chính được phân công.
             *
             * Hiện sử dụng bảng users.
             * Sau này tài khoản này phải có
             * role TECHNICIAN.
             */
            $table->foreignId('technician_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /**
             * ODO thực tế khi xe vào gara.
             */
            $table->unsignedInteger(
                'received_mileage'
            )->default(0);

            /**
             * Trạng thái phiếu bảo dưỡng.
             *
             * RECEIVED
             * IN_PROGRESS
             * COMPLETED
             * CANCELLED
             */
            $table->string('status', 30)
                ->default('RECEIVED');

            /**
             * Thời điểm tiếp nhận xe.
             */
            $table->dateTime('received_at')
                ->nullable();

            /**
             * Thời điểm bắt đầu bảo dưỡng.
             */
            $table->dateTime('started_at')
                ->nullable();

            /**
             * Thời điểm hoàn thành.
             */
            $table->dateTime('completed_at')
                ->nullable();

            /**
             * Mô tả tình trạng xe khi tiếp nhận.
             */
            $table->text('vehicle_condition')
                ->nullable();

            /**
             * Kết quả kiểm tra / chẩn đoán.
             */
            $table->text('diagnosis')
                ->nullable();

            /**
             * Ghi chú của nhân viên.
             */
            $table->text('staff_note')
                ->nullable();

            /**
             * Ghi chú của kỹ thuật viên.
             */
            $table->text('technician_note')
                ->nullable();

            /**
             * Tổng tiền công/dịch vụ thực tế.
             *
             * Chưa bao gồm phụ tùng.
             */
            $table->decimal(
                'service_total',
                12,
                2
            )->default(0);

            /**
             * Tổng tiền phụ tùng.
             *
             * Sẽ được sử dụng khi làm
             * module kho/phụ tùng.
             */
            $table->decimal(
                'parts_total',
                12,
                2
            )->default(0);

            /**
             * Tổng giá trị phiếu.
             */
            $table->decimal(
                'total_amount',
                12,
                2
            )->default(0);

            $table->timestamps();

            $table->index('status');

            $table->index('received_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_orders');
    }
};