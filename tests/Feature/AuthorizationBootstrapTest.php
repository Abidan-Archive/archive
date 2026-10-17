<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\AppServiceProvider;
use Database\Seeders\AclSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class AuthorizationBootstrapTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
        DB::purge('sqlite');
    }

    public function test_boot_skips_database_permissions_when_a_cached_schema_check_is_stale(): void
    {
        cache()->put('hasPermissions', true);
        cache()->forget('permissions');

        $this->app->getProvider(AppServiceProvider::class)->bootAuth();

        $this->assertFalse(cache()->has('permissions'));
        $this->assertTrue(Gate::has('like'));
        $this->assertTrue(Gate::has('unlike'));
    }

    public function test_boot_registers_permissions_after_tables_are_created_despite_a_stale_cache(): void
    {
        foreach (['2014_10_12_000000_create_users_table', '2023_03_29_214733_create_acl_tables'] as $migration) {
            (require database_path("migrations/{$migration}.php"))->up();
        }
        $this->seed(AclSeeder::class);
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        cache()->put('hasPermissions', false);

        $this->app->getProvider(AppServiceProvider::class)->bootAuth();

        $this->assertTrue(Gate::forUser($admin)->allows('admin_manage_permissions'));
        $this->assertFalse(Gate::forUser(User::factory()->create())->allows('admin_manage_permissions'));
    }
}
