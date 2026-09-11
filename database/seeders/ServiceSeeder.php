<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Seed danh sách dịch vụ bảo dưỡng.
     */
    public function run(): void
    {
        $services = [
            /*
            |--------------------------------------------------------------------------
            | Bảo dưỡng định kỳ
            |--------------------------------------------------------------------------
            */
            [
                'category_code' => 'PERIODIC_MAINTENANCE',
                'name' => 'Bảo dưỡng định kỳ 5.000 km',
                'code' => 'MAINTENANCE_5000',
                'description' => 'Kiểm tra cơ bản và bảo dưỡng xe ở mốc 5.000 km.',
                'base_price' => 500000,
                'estimated_duration_minutes' => 60,
                'mileage_interval' => 5000,
                'month_interval' => 6,
            ],
            [
                'category_code' => 'PERIODIC_MAINTENANCE',
                'name' => 'Bảo dưỡng định kỳ 10.000 km',
                'code' => 'MAINTENANCE_10000',
                'description' => 'Kiểm tra tổng quát và bảo dưỡng xe ở mốc 10.000 km.',
                'base_price' => 900000,
                'estimated_duration_minutes' => 90,
                'mileage_interval' => 10000,
                'month_interval' => 12,
            ],
            [
                'category_code' => 'PERIODIC_MAINTENANCE',
                'name' => 'Kiểm tra tổng quát xe',
                'code' => 'GENERAL_INSPECTION',
                'description' => 'Kiểm tra tổng quát tình trạng xe và phát hiện các hạng mục cần bảo dưỡng.',
                'base_price' => 300000,
                'estimated_duration_minutes' => 45,
                'mileage_interval' => null,
                'month_interval' => 6,
            ],

            /*
            |--------------------------------------------------------------------------
            | Dầu nhớt và bộ lọc
            |--------------------------------------------------------------------------
            */
            [
                'category_code' => 'OIL_FILTER',
                'name' => 'Thay dầu động cơ',
                'code' => 'ENGINE_OIL_CHANGE',
                'description' => 'Thay dầu bôi trơn động cơ theo tiêu chuẩn phù hợp với từng loại xe.',
                'base_price' => 650000,
                'estimated_duration_minutes' => 30,
                'mileage_interval' => 5000,
                'month_interval' => 6,
            ],
            [
                'category_code' => 'OIL_FILTER',
                'name' => 'Thay lọc dầu động cơ',
                'code' => 'OIL_FILTER_CHANGE',
                'description' => 'Thay lọc dầu nhằm đảm bảo dầu động cơ được lọc sạch.',
                'base_price' => 250000,
                'estimated_duration_minutes' => 20,
                'mileage_interval' => 10000,
                'month_interval' => 12,
            ],
            [
                'category_code' => 'OIL_FILTER',
                'name' => 'Thay lọc gió động cơ',
                'code' => 'ENGINE_AIR_FILTER_CHANGE',
                'description' => 'Thay lọc gió giúp động cơ nhận đủ không khí sạch.',
                'base_price' => 350000,
                'estimated_duration_minutes' => 20,
                'mileage_interval' => 20000,
                'month_interval' => 12,
            ],
            [
                'category_code' => 'OIL_FILTER',
                'name' => 'Thay lọc gió điều hòa',
                'code' => 'CABIN_FILTER_CHANGE',
                'description' => 'Thay lọc gió khoang hành khách giúp cải thiện chất lượng không khí trong xe.',
                'base_price' => 300000,
                'estimated_duration_minutes' => 20,
                'mileage_interval' => 15000,
                'month_interval' => 12,
            ],

            /*
            |--------------------------------------------------------------------------
            | Lốp và phanh
            |--------------------------------------------------------------------------
            */
            [
                'category_code' => 'TIRE_BRAKE',
                'name' => 'Đảo lốp xe',
                'code' => 'TIRE_ROTATION',
                'description' => 'Đảo vị trí bánh xe nhằm giúp lốp mòn đều hơn.',
                'base_price' => 200000,
                'estimated_duration_minutes' => 30,
                'mileage_interval' => 10000,
                'month_interval' => null,
            ],
            [
                'category_code' => 'TIRE_BRAKE',
                'name' => 'Cân bằng động bánh xe',
                'code' => 'WHEEL_BALANCING',
                'description' => 'Cân bằng bánh xe giúp giảm rung lắc khi xe vận hành.',
                'base_price' => 300000,
                'estimated_duration_minutes' => 40,
                'mileage_interval' => 10000,
                'month_interval' => null,
            ],
            [
                'category_code' => 'TIRE_BRAKE',
                'name' => 'Kiểm tra hệ thống phanh',
                'code' => 'BRAKE_INSPECTION',
                'description' => 'Kiểm tra má phanh, đĩa phanh và dầu phanh.',
                'base_price' => 200000,
                'estimated_duration_minutes' => 30,
                'mileage_interval' => 10000,
                'month_interval' => 6,
            ],
            [
                'category_code' => 'TIRE_BRAKE',
                'name' => 'Thay má phanh',
                'code' => 'BRAKE_PAD_REPLACEMENT',
                'description' => 'Thay má phanh khi bị mòn nhằm đảm bảo an toàn vận hành.',
                'base_price' => 1200000,
                'estimated_duration_minutes' => 60,
                'mileage_interval' => 40000,
                'month_interval' => null,
            ],

            /*
            |--------------------------------------------------------------------------
            | Điện và ắc quy
            |--------------------------------------------------------------------------
            */
            [
                'category_code' => 'ELECTRICAL_BATTERY',
                'name' => 'Kiểm tra ắc quy',
                'code' => 'BATTERY_INSPECTION',
                'description' => 'Kiểm tra điện áp, khả năng khởi động và tình trạng ắc quy.',
                'base_price' => 150000,
                'estimated_duration_minutes' => 20,
                'mileage_interval' => null,
                'month_interval' => 6,
            ],
            [
                'category_code' => 'ELECTRICAL_BATTERY',
                'name' => 'Thay ắc quy',
                'code' => 'BATTERY_REPLACEMENT',
                'description' => 'Thay mới ắc quy phù hợp với thông số kỹ thuật của xe.',
                'base_price' => 1800000,
                'estimated_duration_minutes' => 30,
                'mileage_interval' => null,
                'month_interval' => 30,
            ],
            [
                'category_code' => 'ELECTRICAL_BATTERY',
                'name' => 'Kiểm tra hệ thống điện',
                'code' => 'ELECTRICAL_INSPECTION',
                'description' => 'Kiểm tra hệ thống điện và các thiết bị điện trên xe.',
                'base_price' => 350000,
                'estimated_duration_minutes' => 45,
                'mileage_interval' => null,
                'month_interval' => 12,
            ],

            /*
            |--------------------------------------------------------------------------
            | Điều hòa
            |--------------------------------------------------------------------------
            */
            [
                'category_code' => 'AIR_CONDITIONING',
                'name' => 'Vệ sinh điều hòa',
                'code' => 'AC_CLEANING',
                'description' => 'Vệ sinh hệ thống điều hòa và đường gió trong xe.',
                'base_price' => 500000,
                'estimated_duration_minutes' => 60,
                'mileage_interval' => null,
                'month_interval' => 12,
            ],
            [
                'category_code' => 'AIR_CONDITIONING',
                'name' => 'Kiểm tra và nạp gas điều hòa',
                'code' => 'AC_GAS_RECHARGE',
                'description' => 'Kiểm tra khả năng làm lạnh và bổ sung gas điều hòa khi cần thiết.',
                'base_price' => 700000,
                'estimated_duration_minutes' => 60,
                'mileage_interval' => null,
                'month_interval' => 24,
            ],

            /*
            |--------------------------------------------------------------------------
            | Động cơ và truyền động
            |--------------------------------------------------------------------------
            */
            [
                'category_code' => 'ENGINE_TRANSMISSION',
                'name' => 'Vệ sinh kim phun',
                'code' => 'INJECTOR_CLEANING',
                'description' => 'Vệ sinh kim phun giúp cải thiện khả năng cung cấp nhiên liệu.',
                'base_price' => 800000,
                'estimated_duration_minutes' => 90,
                'mileage_interval' => 30000,
                'month_interval' => null,
            ],
            [
                'category_code' => 'ENGINE_TRANSMISSION',
                'name' => 'Vệ sinh họng ga',
                'code' => 'THROTTLE_BODY_CLEANING',
                'description' => 'Vệ sinh họng ga nhằm giúp động cơ hoạt động ổn định hơn.',
                'base_price' => 450000,
                'estimated_duration_minutes' => 45,
                'mileage_interval' => 20000,
                'month_interval' => null,
            ],
            [
                'category_code' => 'ENGINE_TRANSMISSION',
                'name' => 'Thay dầu hộp số',
                'code' => 'TRANSMISSION_OIL_CHANGE',
                'description' => 'Thay dầu hộp số theo khuyến nghị bảo dưỡng.',
                'base_price' => 1800000,
                'estimated_duration_minutes' => 90,
                'mileage_interval' => 40000,
                'month_interval' => 24,
            ],

            /*
            |--------------------------------------------------------------------------
            | Chăm sóc xe
            |--------------------------------------------------------------------------
            */
            [
                'category_code' => 'CAR_CARE',
                'name' => 'Rửa xe tiêu chuẩn',
                'code' => 'STANDARD_CAR_WASH',
                'description' => 'Rửa ngoại thất và vệ sinh cơ bản xe.',
                'base_price' => 120000,
                'estimated_duration_minutes' => 30,
                'mileage_interval' => null,
                'month_interval' => null,
            ],
            [
                'category_code' => 'CAR_CARE',
                'name' => 'Vệ sinh nội thất',
                'code' => 'INTERIOR_CLEANING',
                'description' => 'Vệ sinh ghế, sàn, taplo và khoang nội thất.',
                'base_price' => 800000,
                'estimated_duration_minutes' => 120,
                'mileage_interval' => null,
                'month_interval' => 6,
            ],
            [
                'category_code' => 'CAR_CARE',
                'name' => 'Đánh bóng ngoại thất',
                'code' => 'EXTERIOR_POLISHING',
                'description' => 'Đánh bóng bề mặt sơn giúp cải thiện độ bóng ngoại thất.',
                'base_price' => 1500000,
                'estimated_duration_minutes' => 180,
                'mileage_interval' => null,
                'month_interval' => 12,
            ],
        ];

        foreach ($services as $serviceData) {
            $category = ServiceCategory::where(
                'code',
                $serviceData['category_code']
            )->firstOrFail();

            Service::updateOrCreate(
                [
                    'code' => $serviceData['code'],
                ],
                [
                    'category_id' => $category->id,
                    'name' => $serviceData['name'],
                    'description' => $serviceData['description'],
                    'base_price' => $serviceData['base_price'],
                    'estimated_duration_minutes' =>
                        $serviceData['estimated_duration_minutes'],
                    'mileage_interval' =>
                        $serviceData['mileage_interval'],
                    'month_interval' =>
                        $serviceData['month_interval'],
                    'is_active' => true,
                ]
            );
        }
    }
}