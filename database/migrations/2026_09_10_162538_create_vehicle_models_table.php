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
        Schema::create('vehicle_models', function (Blueprint $table) {
            $table->id();

            // Hãng xe
            $table->foreignId('brand_id')
                ->constrained('vehicle_brands')
                ->cascadeOnDelete();

            // Tên dòng xe
            // Ví dụ: Vios, Camry, City, CX-5...
            $table->string('name', 100);

            // Phân khúc xe
            // Ví dụ: Sedan, SUV, Hatchback, MPV...
            $table->string('vehicle_type', 50)->nullable();

            // Trạng thái sử dụng
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Không cho phép trùng tên dòng xe trong cùng một hãng
            $table->unique(['brand_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_models');
    }
};