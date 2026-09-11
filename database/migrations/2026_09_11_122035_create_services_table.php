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
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('service_categories')
                ->restrictOnDelete();

            $table->string('name', 150);

            $table->string('code', 50)
                ->unique();

            $table->text('description')
                ->nullable();

            /**
             * Giá tham khảo của dịch vụ.
             *
             * Giá thực tế sau này có thể khác
             * khi tạo phiếu dịch vụ / hóa đơn.
             */
            $table->decimal(
                'base_price',
                12,
                2
            )->default(0);

            /**
             * Thời gian thực hiện dự kiến.
             */
            $table->unsignedInteger(
                'estimated_duration_minutes'
            )->nullable();

            /**
             * Chu kỳ bảo dưỡng theo số km.
             *
             * Ví dụ:
             * Thay dầu mỗi 5.000 km.
             */
            $table->unsignedInteger(
                'mileage_interval'
            )->nullable();

            /**
             * Chu kỳ bảo dưỡng theo tháng.
             *
             * Ví dụ:
             * 6 tháng/lần.
             */
            $table->unsignedInteger(
                'month_interval'
            )->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};