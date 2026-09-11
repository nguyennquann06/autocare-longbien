<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Seed các nhóm dịch vụ.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Bảo dưỡng định kỳ',
                'code' => 'PERIODIC_MAINTENANCE',
                'description' => 'Các gói kiểm tra và bảo dưỡng xe theo số km hoặc thời gian.',
                'is_active' => true,
            ],
            [
                'name' => 'Dầu nhớt và bộ lọc',
                'code' => 'OIL_FILTER',
                'description' => 'Thay dầu động cơ và các loại bộ lọc trên xe.',
                'is_active' => true,
            ],
            [
                'name' => 'Lốp và hệ thống phanh',
                'code' => 'TIRE_BRAKE',
                'description' => 'Kiểm tra, bảo dưỡng và thay thế lốp, má phanh và các bộ phận liên quan.',
                'is_active' => true,
            ],
            [
                'name' => 'Điện và ắc quy',
                'code' => 'ELECTRICAL_BATTERY',
                'description' => 'Kiểm tra hệ thống điện, ắc quy, đèn và các thiết bị điện trên xe.',
                'is_active' => true,
            ],
            [
                'name' => 'Điều hòa ô tô',
                'code' => 'AIR_CONDITIONING',
                'description' => 'Kiểm tra, vệ sinh và sửa chữa hệ thống điều hòa.',
                'is_active' => true,
            ],
            [
                'name' => 'Động cơ và truyền động',
                'code' => 'ENGINE_TRANSMISSION',
                'description' => 'Kiểm tra và bảo dưỡng động cơ, hộp số và hệ thống truyền động.',
                'is_active' => true,
            ],
            [
                'name' => 'Chăm sóc và vệ sinh xe',
                'code' => 'CAR_CARE',
                'description' => 'Các dịch vụ vệ sinh, chăm sóc và làm đẹp xe.',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            ServiceCategory::updateOrCreate(
                [
                    'code' => $category['code'],
                ],
                $category
            );
        }
    }
}