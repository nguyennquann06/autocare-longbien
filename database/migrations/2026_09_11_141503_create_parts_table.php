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
        Schema::create('parts', function (Blueprint $table) {
            $table->id();

            /**
             * Mã phụ tùng.
             *
             * Ví dụ:
             * PT-OIL-001
             */
            $table->string('code', 50)
                ->unique();

            /**
             * Tên phụ tùng.
             */
            $table->string('name', 150);

            /**
             * Nhóm phụ tùng.
             *
             * Ví dụ:
             * Dầu nhớt
             * Bộ lọc
             * Phanh
             * Điện - Ắc quy
             */
            $table->string('category', 100)
                ->nullable();

            /**
             * Đơn vị tính.
             *
             * Ví dụ:
             * chai
             * cái
             * bộ
             * lít
             */
            $table->string('unit', 30)
                ->default('cái');

            /**
             * Giá nhập gần nhất.
             */
            $table->decimal(
                'cost_price',
                12,
                2
            )->default(0);

            /**
             * Giá bán mặc định.
             */
            $table->decimal(
                'selling_price',
                12,
                2
            )->default(0);

            /**
             * Tồn kho hiện tại.
             */
            $table->unsignedInteger(
                'stock_quantity'
            )->default(0);

            /**
             * Mức tồn tối thiểu.
             *
             * Sau này dùng cho cảnh báo
             * sắp hết hàng.
             */
            $table->unsignedInteger(
                'minimum_stock'
            )->default(0);

            /**
             * Mô tả / ghi chú phụ tùng.
             */
            $table->text('description')
                ->nullable();

            /**
             * Không xóa phụ tùng đã từng sử dụng,
             * chỉ chuyển inactive.
             */
            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            $table->index('category');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parts');
    }
};