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
        Schema::create('vehicle_brands', function (Blueprint $table) {
            $table->id();

            // Tên hãng xe
            $table->string('name', 100)->unique();

            // Quốc gia xuất xứ
            $table->string('country', 100)->nullable();

            // Mô tả thêm
            $table->text('description')->nullable();

            // Trạng thái sử dụng
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_brands');
    }
};