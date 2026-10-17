<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        Http::fake(['challenges.cloudflare.com/*' => Http::response(['success' => true, 'error-codes' => []])]);
        $response = $this->post('/register', [
            'username' => 'Test User',
            'email' => 'test@example.com',
            'email_confirmation' => 'test@example.com',
            'password' => 'test-password',
            'password_confirmation' => 'test-password',
            'turnstile' => 'test-turnstile-token',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.notice'));
    }
}
