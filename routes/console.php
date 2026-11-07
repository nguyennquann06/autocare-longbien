<?php

use App\Models\User;
use Database\Seeders\DemoAdminSeeder;
use Database\Seeders\KnowledgeBaseSeeder;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('demo:seed', function () {
    /*
     * DemoAdminSeeder chạy cuối cùng nên tài khoản admin là dấu hiệu
     * đã seed xong. Các lần khởi động sau không ghi đè dữ liệu demo.
     */
    if (User::where('email', config('demo.admin.email'))->exists()) {
        $this->info('Dữ liệu demo đã có, bỏ qua seed.');

        return;
    }

    $this->call('db:seed', ['--force' => true]);

    foreach ([KnowledgeBaseSeeder::class, DemoAdminSeeder::class] as $seeder) {
        $this->call('db:seed', ['--class' => $seeder, '--force' => true]);
    }
})->purpose('Seed demo data once on first deployment');
