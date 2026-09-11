<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,

            VehicleBrandSeeder::class,
            VehicleModelSeeder::class,

            ServiceCategorySeeder::class,
            ServiceSeeder::class,
        ]);
    }
}