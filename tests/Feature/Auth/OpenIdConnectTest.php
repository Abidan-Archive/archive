<?php

namespace Tests\Feature\Auth;

use App\Http\Middleware\EnsureBansRejectAccess;
use App\Models\User;
use App\Services\OpenIdToken;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class OpenIdConnectTest extends TestCase
{
    private string $keyPath;

    private string $callback = 'http://wiki.localhost/wiki/Special:PluggableAuthLogin';

    protected function setUp(): void
    {
        parent::setUp();

        // Isolate the provider's tables from the application's development database.
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'app.url' => 'http://localhost:8000',
            'oauth.clients.mediawiki.secret' => 'test-secret',
            'oauth.clients.mediawiki.secret_file' => null,
            'oauth.clients.mediawiki.redirect_uris' => [$this->callback],
        ]);
        DB::purge('sqlite');
        foreach (['2014_10_12_000000_create_users_table', '2019_12_14_000001_create_personal_access_tokens_table'] as $migration) {
            (require database_path("migrations/{$migration}.php"))->up();
        }
        $this->withoutMiddleware(EnsureBansRejectAccess::class);

        $this->keyPath = tempnam(sys_get_temp_dir(), 'oidc-test-');
        File::delete($this->keyPath);
        config(['oauth.private_key_path' => $this->keyPath]);
        $this->artisan('oauth:keys')->assertSuccessful();
    }

    protected function tearDown(): void
    {
        if (isset($this->keyPath)) {
            File::delete($this->keyPath);
        }
        parent::tearDown();
    }

    private function authorize(array $overrides = []): string
    {
        $this->actingAs(User::factory()->create());
        $response = $this->get('/oauth/authorize?'.http_build_query(array_merge([
            'client_id' => 'mediawiki',
            'redirect_uri' => $this->callback,
            'response_type' => 'code',
            'scope' => 'openid profile email',
            'state' => 'wiki-state',
            'nonce' => 'wiki-nonce',
            'code_challenge' => OpenIdToken::base64Url(hash('sha256', str_repeat('a', 43), true)),
            'code_challenge_method' => 'S256',
        ], $overrides)));
        $response->assertRedirect();
        parse_str(parse_url($response->headers->get('Location'), PHP_URL_QUERY), $query);
        $this->assertSame('wiki-state', $query['state']);

        return $query['code'];
    }

    private function exchange(string $code, array $overrides = []): \Illuminate\Testing\TestResponse
    {
        return $this->postJson('/oauth/token', array_merge([
            'grant_type' => 'authorization_code',
            'client_id' => 'mediawiki',
            'client_secret' => 'test-secret',
            'redirect_uri' => $this->callback,
            'code' => $code,
            'code_verifier' => str_repeat('a', 43),
        ], $overrides));
    }

    public function test_discovery_uses_one_canonical_issuer_and_advertises_signed_tokens(): void
    {
        $this->getJson('/.well-known/openid-configuration')->assertOk()->assertJson([
            'issuer' => 'http://localhost:8000',
            'token_endpoint' => 'http://localhost:8000/oauth/token',
            'jwks_uri' => 'http://localhost:8000/oauth/jwks',
            'id_token_signing_alg_values_supported' => ['RS256'],
            'code_challenge_methods_supported' => ['S256'],
        ]);
    }

    public function test_key_can_be_printed_for_sops_without_modifying_the_local_key(): void
    {
        $original = File::get($this->keyPath);
        $this->assertSame(0, Artisan::call('oauth:keys', ['--show' => true]));
        $pem = Artisan::output();
        $key = openssl_pkey_get_private($pem);
        $this->assertNotFalse($key);
        $this->assertSame(OPENSSL_KEYTYPE_RSA, openssl_pkey_get_details($key)['type']);
        $this->assertSame($original, File::get($this->keyPath));
    }

    public function test_token_exchange_reads_the_current_runtime_client_secret_file(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'oidc-client-test-');
        config(['oauth.clients.mediawiki.secret_file' => $path]);
        try {
            File::put($path, "runtime-secret\n");
            $this->exchange($this->authorize(), ['client_secret' => 'runtime-secret'])->assertOk();
            File::put($path, 'rotated-secret');
            $code = $this->authorize();
            $this->exchange($code, ['client_secret' => 'runtime-secret'])->assertStatus(401);
            $this->exchange($code, ['client_secret' => 'rotated-secret'])->assertOk();
            File::delete($path);
            $this->exchange($this->authorize())->assertStatus(401);
        } finally {
            File::delete($path);
        }
    }

    public function test_key_helper_does_not_create_unmanaged_production_keys(): void
    {
        File::delete($this->keyPath);
        $this->app['env'] = 'production';
        $this->artisan('oauth:keys')
            ->expectsOutput('Production signing keys are managed by SOPS. Use --show locally to generate a PEM for import.')
            ->assertExitCode(1);
        $this->assertFalse(File::exists($this->keyPath));
    }

    public function test_code_flow_returns_a_verifiable_id_token_and_single_use_code(): void
    {
        $code = $this->authorize();
        $user = auth()->user();
        $response = $this->exchange($code)->assertOk()->assertHeader('Cache-Control', 'no-store, private');
        [$header, $payload, $signature] = explode('.', $response->json('id_token'));
        $claims = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
        $this->assertSame('http://localhost:8000', $claims['iss']);
        $this->assertSame((string) $user->id, $claims['sub']);
        $this->assertSame('mediawiki', $claims['aud']);
        $this->assertSame('wiki-nonce', $claims['nonce']);
        $this->assertSame($user->username, $claims['preferred_username']);
        $this->assertSame($user->email, $claims['email']);
        $this->assertSame(3600, $claims['exp'] - $claims['iat']);
        $publicKey = openssl_pkey_get_details(openssl_pkey_get_private(File::get($this->keyPath)))['key'];
        $this->assertSame(1, openssl_verify($header.'.'.$payload, base64_decode(strtr($signature, '-_', '+/')), $publicKey, OPENSSL_ALGO_SHA256));
        $jwk = $this->getJson('/oauth/jwks')->assertOk()->json('keys.0');
        $this->assertSame($jwk['kid'], json_decode(base64_decode(strtr($header, '-_', '+/')), true)['kid']);
        $this->assertArrayNotHasKey('d', $jwk);
        $this->assertNotNull($user->tokens()->first()->expires_at);
        auth()->forgetGuards();
        $this->withToken($response->json('access_token'))->getJson('/oauth/userinfo')->assertOk()->assertJson([
            'sub' => (string) $user->id,
            'preferred_username' => $user->username,
            'email' => $user->email,
            'email_verified' => true,
        ]);
        $this->exchange($code)->assertStatus(400)->assertJson(['error' => 'invalid_grant']);
    }

    public function test_guest_authorization_redirects_to_main_site_login(): void
    {
        $this->get('/oauth/authorize')->assertRedirect('/login');
    }

    private function authorizationUrl(): string
    {
        return '/oauth/authorize?'.http_build_query([
            'client_id' => 'mediawiki',
            'redirect_uri' => $this->callback,
            'response_type' => 'code',
            'scope' => 'openid profile email',
            'state' => 'wiki-state',
            'nonce' => 'wiki-nonce',
            'code_challenge' => OpenIdToken::base64Url(hash('sha256', str_repeat('a', 43), true)),
            'code_challenge_method' => 'S256',
        ]);
    }

    public function test_password_login_resumes_authorization_and_leaves_inertia_for_wiki(): void
    {
        Event::fake([Login::class]); // IP/login telemetry is separate from the redirect flow.
        $user = User::factory()->create();
        Http::fake(['challenges.cloudflare.com/*' => Http::response(['success' => true, 'error-codes' => []])]);
        $url = $this->authorizationUrl();
        $this->get($url)->assertRedirect('/login');
        $intended = session('url.intended');
        parse_str(parse_url($url, PHP_URL_QUERY), $expectedQuery);
        parse_str(parse_url($intended, PHP_URL_QUERY), $savedQuery);
        $this->assertEquals($expectedQuery, $savedQuery);
        $login = $this->withHeader('X-Inertia', 'true')->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'turnstile' => 'test-turnstile-token',
        ])->assertRedirect($intended);
        $response = $this->withHeader('X-Inertia-Version', Inertia::getVersion())
            ->get($login->headers->get('Location'))->assertStatus(409);
        $location = $response->headers->get('X-Inertia-Location');
        $this->assertStringStartsWith($this->callback.'?', $location);
        parse_str(parse_url($location, PHP_URL_QUERY), $query);
        $this->assertSame('wiki-state', $query['state']);
        $this->exchange($query['code'])->assertOk();
    }

    public function test_discord_login_resumes_the_saved_wiki_authorization_request(): void
    {
        Event::fake([Login::class]);
        $user = User::factory()->create(['discord_id' => '123456789']);
        Socialite::shouldReceive('driver')->with('discord')->andReturnSelf();
        Socialite::shouldReceive('user')->andReturn((object) [
            'id' => $user->discord_id,
            'email' => $user->email,
            'nickname' => $user->username,
        ]);
        $url = $this->authorizationUrl();
        $this->get($url)->assertRedirect('/login');
        $intended = session('url.intended');
        $this->get('/auth/social/discord/callback')->assertRedirect($intended);
        $this->assertAuthenticatedAs($user);
    }

    public function test_unregistered_callback_is_rejected(): void
    {
        $this->actingAs(User::factory()->create());
        $this->get('/oauth/authorize?'.http_build_query([
            'client_id' => 'mediawiki',
            'redirect_uri' => 'http://other.localhost/callback',
            'response_type' => 'code',
            'code_challenge' => str_repeat('a', 43),
            'code_challenge_method' => 'S256',
        ]))->assertStatus(400);
    }

    public function test_wrong_pkce_verifier_is_rejected(): void
    {
        $this->exchange($this->authorize(), ['code_verifier' => str_repeat('b', 43)])
            ->assertStatus(400)->assertJson(['error' => 'invalid_grant']);
    }

    public function test_wrong_client_secret_is_rejected_without_consuming_code(): void
    {
        $code = $this->authorize();
        $this->exchange($code, ['client_secret' => 'incorrect'])->assertStatus(401)
            ->assertJson(['error' => 'invalid_client']);
        $this->exchange($code)->assertOk();
    }

    public function test_token_exchange_does_not_require_browser_csrf_token(): void
    {
        $code = $this->authorize();
        // Laravel normally skips CSRF during tests; force it on for this regression.
        $this->app['env'] = 'local';
        $this->exchange($code)->assertOk();
        $this->postJson('/login', [])->assertStatus(302); // Existing 419 handler redirects back.
    }
}
