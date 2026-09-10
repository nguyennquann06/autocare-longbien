<?php

namespace Database\Seeders;

use App\Models\VehicleBrand;
use Illuminate\Database\Seeder;

class VehicleBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Toyota',
                'country' => 'Nhật Bản',
                'description' => 'Hãng ô tô Nhật Bản.',
            ],
            [
                'name' => 'Honda',
                'country' => 'Nhật Bản',
                'description' => 'Hãng ô tô Nhật Bản.',
            ],
            [
                'name' => 'Mazda',
                'country' => 'Nhật Bản',
                'description' => 'Hãng ô tô Nhật Bản.',
            ],
            [
                'name' => 'Mitsubishi',
                'country' => 'Nhật Bản',
                'description' => 'Hãng ô tô Nhật Bản.',
            ],
            [
                'name' => 'Nissan',
                'country' => 'Nhật Bản',
                'description' => 'Hãng ô tô Nhật Bản.',
            ],
            [
                'name' => 'Hyundai',
                'country' => 'Hàn Quốc',
                'description' => 'Hãng ô tô Hàn Quốc.',
            ],
            [
                'name' => 'Kia',
                'country' => 'Hàn Quốc',
                'description' => 'Hãng ô tô Hàn Quốc.',
            ],
            [
                'name' => 'Ford',
                'country' => 'Hoa Kỳ',
                'description' => 'Hãng ô tô Hoa Kỳ.',
            ],
            [
                'name' => 'VinFast',
                'country' => 'Việt Nam',
                'description' => 'Hãng ô tô Việt Nam.',
            ],
            [
                'name' => 'Mercedes-Benz',
                'country' => 'Đức',
                'description' => 'Hãng ô tô Đức.',
            ],
            [
                'name' => 'BMW',
                'country' => 'Đức',
                'description' => 'Hãng ô tô Đức.',
            ],
        ];

        foreach ($brands as $brand) {
            VehicleBrand::updateOrCreate(
                [
                    'name' => $brand['name'],
                ],
                [
                    'country' => $brand['country'],
                    'description' => $brand['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}