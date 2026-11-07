<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DemoAdminSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Tests\TestCase;

class DemoAdminSeederTest extends TestCase
{
    use RefreshDatabase;

    private const EMAIL = 'admin@example.test';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
    }

    public function test_it_creates_an_admin_with_the_configured_password(): void
    {
        config([
            'demo.admin.email' => self::EMAIL,
            'demo.admin.password' => 'first-password',
        ]);

        $this->seed(DemoAdminSeeder::class);

        $admin = User::where('email', self::EMAIL)->firstOrFail();

        $this->assertSame('ADMIN', $admin->role->code);
        $this->assertTrue(Hash::check('first-password', $admin->password));
    }

    public function test_it_keeps_the_existing_password_when_run_again(): void
    {
        config([
            'demo.admin.email' => self::EMAIL,
            'demo.admin.password' => 'first-password',
        ]);

        $this->seed(DemoAdminSeeder::class);

        config(['demo.admin.password' => 'second-password']);

        $this->seed(DemoAdminSeeder::class);

        $admin = User::where('email', self::EMAIL)->firstOrFail();

        $this->assertTrue(Hash::check('first-password', $admin->password));
    }

    public function test_it_fails_when_the_password_is_missing(): void
    {
        config([
            'demo.admin.email' => self::EMAIL,
            'demo.admin.password' => null,
        ]);

        $this->expectException(RuntimeException::class);

        $this->seed(DemoAdminSeeder::class);
    }
}
