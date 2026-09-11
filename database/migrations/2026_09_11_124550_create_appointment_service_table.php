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
        Schema::create('appointment_service', function (Blueprint $table) {
            $table->id();

            /**
             * Lịch hẹn.
             */
            $table->foreignId('appointment_id')
                ->constrained('appointments')
                ->cascadeOnDelete();

            /**
             * Dịch vụ được chọn.
             */
            $table->foreignId('service_id')
                ->constrained('services')
                ->restrictOnDelete();

            /**
             * Giá dịch vụ tại thời điểm đặt lịch.
             *
             * Không lấy trực tiếp base_price mãi mãi
             * vì sau này Admin có thể thay đổi giá.
             */
            $table->decimal(
                'price',
                12,
                2
            )->default(0);

            /**
             * Thời gian thực hiện dự kiến
             * tại thời điểm đặt lịch.
             */
            $table->unsignedInteger(
                'estimated_duration_minutes'
            )->nullable();

            $table->timestamps();

            /**
             * Một dịch vụ không được xuất hiện
             * hai lần trong cùng một lịch hẹn.
             */
            $table->unique([
                'appointment_id',
                'service_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(
            'appointment_service'
        );
    }
};