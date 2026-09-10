<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::updateOrCreate(
            ['code' => 'ADMIN'],
            [
                'name' => 'Quản trị viên',
                'description' => 'Quản lý toàn bộ hệ thống',
            ]
        );

        Role::updateOrCreate(
            ['code' => 'STAFF'],
            [
                'name' => 'Nhân viên',
                'description' => 'Tiếp nhận khách hàng và quản lý lịch bảo dưỡng',
            ]
        );

        Role::updateOrCreate(
            ['code' => 'TECHNICIAN'],
            [
                'name' => 'Kỹ thuật viên',
                'description' => 'Thực hiện và cập nhật công việc bảo dưỡng',
            ]
        );

        Role::updateOrCreate(
            ['code' => 'CUSTOMER'],
            [
                'name' => 'Khách hàng',
                'description' => 'Đặt lịch và theo dõi thông tin bảo dưỡng xe',
            ]
        );
    }
}