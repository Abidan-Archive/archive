<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Permission;
use App\Models\Report;
use App\Models\Tag;
use App\Models\User;
use App\Providers\AppServiceProvider;
use Database\Seeders\AclSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpgradeCompatibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_permissions_and_tags_roundtrip_through_a_restricted_persistent_cache(): void
    {
        $path = sys_get_temp_dir().'/abidan-cache-'.bin2hex(random_bytes(8));
        config(['cache.default' => 'file', 'cache.stores.file.path' => $path]);
        $this->app['cache']->forgetDriver('file');
        try {
            $this->assertFalse(config('cache.serializable_classes'));
            $this->seed(AclSeeder::class);
            // Simulate the old payloads and the maintenance-window cache clear.
            cache()->put('permissions', Permission::with('roles')->get());
            cache()->put('tagNames', collect(['legacy-tag']));
            cache()->flush();
            $admin = User::factory()->create();
            $admin->assignRole('admin');
            $other = User::factory()->create();
            $provider = $this->app->getProvider(AppServiceProvider::class);
            $provider->bootAuth();
            $this->assertIsArray(cache()->get('permissions'));
            // The second boot reads the on-disk payload, exercising unserialization.
            $provider->bootAuth();
            $this->assertTrue(Gate::forUser($admin)->allows('admin_view'));
            $this->assertFalse(Gate::forUser($other)->allows('admin_view'));
            Tag::create(['name' => 'cradle', 'color' => '#123456']);
            $this->assertSame(['cradle'], Tag::allNames()->all());
            $this->assertSame(['cradle'], Tag::allNames()->all());
            $this->assertSame(['cradle'], cache()->get('tagNames'));
            Tag::create(['name' => 'elder-empire', 'color' => '#654321']);
            $this->assertCount(2, Tag::allNames());
        } finally {
            File::deleteDirectory($path);
        }
    }

    public function test_report_submission_and_markdown_rendering_still_work(): void
    {
        config(['scout.driver' => 'collection']);
        Http::fake(['challenges.cloudflare.com/*' => Http::response(['success' => true, 'error-codes' => []])]);
        $event = Event::create(['name' => 'Upgrade test event', 'date' => '2026-10-17']);
        $response = $this->actingAs(User::factory()->create())->post('/report', [
            'event_id' => $event->id,
            'date' => '2026-10-17',
            'dialogues' => [['speaker' => 'Test speaker', 'line' => '**Test** <script>alert(1)</script>']],
            'tags' => [],
            'turnstile' => 'test-token',
        ]);
        $response->assertSessionHasNoErrors()->assertRedirect();
        $report = Report::firstOrFail();
        $this->assertStringContainsString('<strong>Test</strong>', $report->dialogues->first()->line_html);
        $this->assertStringNotContainsString('<script>', $report->dialogues->first()->line_html);
        $this->get(route('report.show', $report))->assertOk();
    }

    public function test_existing_storage_paths_and_session_format_are_preserved(): void
    {
        $this->assertSame(storage_path('app'), config('filesystems.disks.local.root'));
        $this->assertSame(storage_path('app/public'), config('filesystems.disks.public.root'));
        $this->assertSame('php', config('session.serialization'));
        $this->assertSame(
            env('REDIS_PREFIX', Str::slug((string) env('APP_NAME', 'laravel'), '_').'_database_'),
            config('database.redis.options.prefix')
        );
    }
}
