<?php

namespace Database\Seeders;

use App\Models\Part;
use Illuminate\Database\Seeder;

class PartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parts = [
            [
                'code' => 'PT-OIL-5W30',
                'name' => 'Dầu động cơ 5W-30',
                'category' => 'Dầu nhớt',
                'unit' => 'lít',
                'cost_price' => 180000,
                'selling_price' => 220000,
                'stock_quantity' => 40,
                'minimum_stock' => 10,
                'description' =>
                    'Dầu động cơ 5W-30 dùng cho các dòng xe phù hợp.',
                'is_active' => true,
            ],

            [
                'code' => 'PT-OIL-FILTER',
                'name' => 'Lọc dầu động cơ',
                'category' => 'Bộ lọc',
                'unit' => 'cái',
                'cost_price' => 120000,
                'selling_price' => 180000,
                'stock_quantity' => 25,
                'minimum_stock' => 5,
                'description' =>
                    'Lọc dầu động cơ dùng khi thay dầu định kỳ.',
                'is_active' => true,
            ],

            [
                'code' => 'PT-AIR-FILTER',
                'name' => 'Lọc gió động cơ',
                'category' => 'Bộ lọc',
                'unit' => 'cái',
                'cost_price' => 220000,
                'selling_price' => 320000,
                'stock_quantity' => 20,
                'minimum_stock' => 5,
                'description' =>
                    'Lọc bụi và tạp chất trong không khí trước khi vào động cơ.',
                'is_active' => true,
            ],

            [
                'code' => 'PT-CABIN-FILTER',
                'name' => 'Lọc gió điều hòa',
                'category' => 'Bộ lọc',
                'unit' => 'cái',
                'cost_price' => 180000,
                'selling_price' => 280000,
                'stock_quantity' => 20,
                'minimum_stock' => 5,
                'description' =>
                    'Lọc bụi và tạp chất trong hệ thống điều hòa xe.',
                'is_active' => true,
            ],

            [
                'code' => 'PT-BRAKE-PAD-FRONT',
                'name' => 'Má phanh trước',
                'category' => 'Phanh',
                'unit' => 'bộ',
                'cost_price' => 750000,
                'selling_price' => 1100000,
                'stock_quantity' => 12,
                'minimum_stock' => 3,
                'description' =>
                    'Bộ má phanh trước dùng thay thế khi má phanh mòn.',
                'is_active' => true,
            ],

            [
                'code' => 'PT-BRAKE-FLUID-DOT4',
                'name' => 'Dầu phanh DOT 4',
                'category' => 'Phanh',
                'unit' => 'chai',
                'cost_price' => 120000,
                'selling_price' => 180000,
                'stock_quantity' => 20,
                'minimum_stock' => 5,
                'description' =>
                    'Dầu phanh tiêu chuẩn DOT 4.',
                'is_active' => true,
            ],

            [
                'code' => 'PT-BATTERY-45AH',
                'name' => 'Ắc quy 12V 45Ah',
                'category' => 'Điện - Ắc quy',
                'unit' => 'bình',
                'cost_price' => 1200000,
                'selling_price' => 1600000,
                'stock_quantity' => 8,
                'minimum_stock' => 2,
                'description' =>
                    'Ắc quy 12V dung lượng 45Ah.',
                'is_active' => true,
            ],

            [
                'code' => 'PT-AC-R134A',
                'name' => 'Gas điều hòa R134a',
                'category' => 'Điều hòa',
                'unit' => 'lon',
                'cost_price' => 130000,
                'selling_price' => 180000,
                'stock_quantity' => 30,
                'minimum_stock' => 8,
                'description' =>
                    'Gas lạnh R134a dùng cho hệ thống điều hòa phù hợp.',
                'is_active' => true,
            ],

            [
                'code' => 'PT-SPARK-IRIDIUM',
                'name' => 'Bugi Iridium',
                'category' => 'Động cơ',
                'unit' => 'cái',
                'cost_price' => 180000,
                'selling_price' => 260000,
                'stock_quantity' => 40,
                'minimum_stock' => 10,
                'description' =>
                    'Bugi Iridium dùng cho các động cơ xăng phù hợp.',
                'is_active' => true,
            ],

            [
                'code' => 'PT-ATF-OIL',
                'name' => 'Dầu hộp số tự động ATF',
                'category' => 'Truyền động',
                'unit' => 'lít',
                'cost_price' => 200000,
                'selling_price' => 280000,
                'stock_quantity' => 24,
                'minimum_stock' => 6,
                'description' =>
                    'Dầu hộp số tự động ATF dùng cho xe phù hợp.',
                'is_active' => true,
            ],

            [
                'code' => 'PT-COOLANT',
                'name' => 'Nước làm mát động cơ',
                'category' => 'Động cơ',
                'unit' => 'lít',
                'cost_price' => 100000,
                'selling_price' => 150000,
                'stock_quantity' => 30,
                'minimum_stock' => 8,
                'description' =>
                    'Dung dịch làm mát cho hệ thống giải nhiệt động cơ.',
                'is_active' => true,
            ],

            [
                'code' => 'PT-WIPER',
                'name' => 'Gạt mưa ô tô',
                'category' => 'Phụ kiện',
                'unit' => 'bộ',
                'cost_price' => 220000,
                'selling_price' => 350000,
                'stock_quantity' => 15,
                'minimum_stock' => 4,
                'description' =>
                    'Bộ gạt mưa thay thế cho các kích thước phù hợp.',
                'is_active' => true,
            ],
        ];


        foreach ($parts as $part) {
            Part::updateOrCreate(
                [
                    'code' => $part['code'],
                ],
                $part
            );
        }
    }
}