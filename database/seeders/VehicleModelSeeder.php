<?php

namespace Database\Seeders;

use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Illuminate\Database\Seeder;

class VehicleModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Toyota' => [
                ['name' => 'Vios', 'vehicle_type' => 'Sedan'],
                ['name' => 'Camry', 'vehicle_type' => 'Sedan'],
                ['name' => 'Corolla Cross', 'vehicle_type' => 'SUV'],
                ['name' => 'Fortuner', 'vehicle_type' => 'SUV'],
                ['name' => 'Innova Cross', 'vehicle_type' => 'MPV'],
            ],

            'Honda' => [
                ['name' => 'City', 'vehicle_type' => 'Sedan'],
                ['name' => 'Civic', 'vehicle_type' => 'Sedan'],
                ['name' => 'CR-V', 'vehicle_type' => 'SUV'],
                ['name' => 'HR-V', 'vehicle_type' => 'SUV'],
            ],

            'Mazda' => [
                ['name' => 'Mazda2', 'vehicle_type' => 'Sedan'],
                ['name' => 'Mazda3', 'vehicle_type' => 'Sedan'],
                ['name' => 'CX-5', 'vehicle_type' => 'SUV'],
                ['name' => 'CX-8', 'vehicle_type' => 'SUV'],
            ],

            'Mitsubishi' => [
                ['name' => 'Attrage', 'vehicle_type' => 'Sedan'],
                ['name' => 'Xforce', 'vehicle_type' => 'SUV'],
                ['name' => 'Outlander', 'vehicle_type' => 'SUV'],
                ['name' => 'Xpander', 'vehicle_type' => 'MPV'],
            ],

            'Nissan' => [
                ['name' => 'Almera', 'vehicle_type' => 'Sedan'],
                ['name' => 'Navara', 'vehicle_type' => 'Pickup'],
                ['name' => 'Kicks', 'vehicle_type' => 'SUV'],
            ],

            'Hyundai' => [
                ['name' => 'Accent', 'vehicle_type' => 'Sedan'],
                ['name' => 'Elantra', 'vehicle_type' => 'Sedan'],
                ['name' => 'Creta', 'vehicle_type' => 'SUV'],
                ['name' => 'Santa Fe', 'vehicle_type' => 'SUV'],
                ['name' => 'Tucson', 'vehicle_type' => 'SUV'],
            ],

            'Kia' => [
                ['name' => 'Soluto', 'vehicle_type' => 'Sedan'],
                ['name' => 'K3', 'vehicle_type' => 'Sedan'],
                ['name' => 'Seltos', 'vehicle_type' => 'SUV'],
                ['name' => 'Sportage', 'vehicle_type' => 'SUV'],
                ['name' => 'Carnival', 'vehicle_type' => 'MPV'],
            ],

            'Ford' => [
                ['name' => 'Ranger', 'vehicle_type' => 'Pickup'],
                ['name' => 'Everest', 'vehicle_type' => 'SUV'],
                ['name' => 'Territory', 'vehicle_type' => 'SUV'],
            ],

            'VinFast' => [
                ['name' => 'VF 3', 'vehicle_type' => 'SUV'],
                ['name' => 'VF 5', 'vehicle_type' => 'SUV'],
                ['name' => 'VF 6', 'vehicle_type' => 'SUV'],
                ['name' => 'VF 7', 'vehicle_type' => 'SUV'],
                ['name' => 'VF 8', 'vehicle_type' => 'SUV'],
                ['name' => 'VF 9', 'vehicle_type' => 'SUV'],
            ],

            'Mercedes-Benz' => [
                ['name' => 'C-Class', 'vehicle_type' => 'Sedan'],
                ['name' => 'E-Class', 'vehicle_type' => 'Sedan'],
                ['name' => 'GLC', 'vehicle_type' => 'SUV'],
                ['name' => 'GLE', 'vehicle_type' => 'SUV'],
            ],

            'BMW' => [
                ['name' => '3 Series', 'vehicle_type' => 'Sedan'],
                ['name' => '5 Series', 'vehicle_type' => 'Sedan'],
                ['name' => 'X3', 'vehicle_type' => 'SUV'],
                ['name' => 'X5', 'vehicle_type' => 'SUV'],
            ],
        ];

        foreach ($data as $brandName => $models) {
            $brand = VehicleBrand::where('name', $brandName)->first();

            if (!$brand) {
                continue;
            }

            foreach ($models as $model) {
                VehicleModel::updateOrCreate(
                    [
                        'brand_id' => $brand->id,
                        'name' => $model['name'],
                    ],
                    [
                        'vehicle_type' => $model['vehicle_type'],
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}