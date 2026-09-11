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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            /**
             * Mã lịch hẹn.
             *
             * Ví dụ:
             * BK202609110001
             */
            $table->string('appointment_code', 30)
                ->unique();

            /**
             * Khách hàng đặt lịch.
             */
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->restrictOnDelete();

            /**
             * Xe được mang tới bảo dưỡng.
             */
            $table->foreignId('vehicle_id')
                ->constrained('vehicles')
                ->restrictOnDelete();

            /**
             * Thông tin liên hệ tại thời điểm đặt lịch.
             *
             * Lưu riêng để sau này nếu khách hàng
             * thay đổi thông tin tài khoản thì lịch
             * hẹn cũ vẫn giữ thông tin ban đầu.
             */
            $table->string('contact_name', 150);

            $table->string('contact_phone', 20);

            $table->string('contact_email', 150)
                ->nullable();

            /**
             * Ngày và giờ khách hàng mong muốn.
             */
            $table->date('appointment_date');

            $table->time('appointment_time');

            /**
             * Tổng giá tham khảo tại thời điểm đặt lịch.
             */
            $table->decimal(
                'estimated_total',
                12,
                2
            )->default(0);

            /**
             * Tổng thời gian dự kiến của các dịch vụ.
             */
            $table->unsignedInteger(
                'estimated_duration_minutes'
            )->default(0);

            /**
             * Trạng thái lịch hẹn.
             *
             * PENDING
             * CONFIRMED
             * IN_PROGRESS
             * COMPLETED
             * CANCELLED
             */
            $table->string('status', 30)
                ->default('PENDING');

            /**
             * Ghi chú của khách hàng.
             */
            $table->text('customer_note')
                ->nullable();

            /**
             * Ghi chú nội bộ của nhân viên.
             */
            $table->text('staff_note')
                ->nullable();

            $table->timestamps();

            /**
             * Index phục vụ tìm kiếm lịch hẹn
             * theo ngày và trạng thái.
             */
            $table->index('appointment_date');

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};