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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            // Chủ sở hữu xe
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            // Hãng xe
            $table->foreignId('brand_id')
                ->constrained('vehicle_brands')
                ->restrictOnDelete();

            // Dòng xe
            $table->foreignId('model_id')
                ->constrained('vehicle_models')
                ->restrictOnDelete();

            // Biển số xe
            $table->string('license_plate', 20)->unique();

            // Số khung VIN
            $table->string('vin', 50)->nullable()->unique();

            // Năm sản xuất
            $table->unsignedSmallInteger('manufacture_year')->nullable();

            // Màu xe
            $table->string('color', 50)->nullable();

            // Loại nhiên liệu
            // Ví dụ: Xăng, Dầu, Điện, Hybrid
            $table->string('fuel_type', 50)->nullable();

            // Số km hiện tại
            $table->unsignedInteger('current_mileage')->default(0);

            // Ghi chú thêm
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};