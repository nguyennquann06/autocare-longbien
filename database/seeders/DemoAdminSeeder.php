<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class DemoAdminSeeder extends Seeder
{
    /**
     * Tạo tài khoản ADMIN đầu tiên.
     *
     * Tài khoản đã tồn tại thì giữ nguyên,
     * để không ghi đè mật khẩu đã đổi.
     */
    public function run(): void
    {
        $admin =
            config('demo.admin');

        if (
            empty($admin['email'])
            || empty($admin['password'])
        ) {
            throw new RuntimeException(
                'Thiếu DEMO_ADMIN_EMAIL hoặc DEMO_ADMIN_PASSWORD.'
            );
        }

        $adminRole =
            Role::where(
                'code',
                'ADMIN'
            )->firstOrFail();

        User::firstOrCreate(
            [
                'email' =>
                    $admin['email'],
            ],
            [
                'role_id' =>
                    $adminRole->id,

                'name' =>
                    $admin['name'],

                'email_verified_at' =>
                    now(),

                'password' =>
                    $admin['password'],
            ]
        );
    }
}
