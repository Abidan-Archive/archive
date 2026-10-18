<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\Mailer\Exception\TransportException;
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
        Notification::fake();
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
        Notification::assertSentTo(User::where('email', 'test@example.com')->firstOrFail(), VerifyEmail::class);
    }

    public function test_registration_requires_a_turnstile_token_before_creating_an_account(): void
    {
        Notification::fake();
        Http::fake();

        $response = $this->post('/register', [
            'username' => 'Test User',
            'email' => 'test@example.com',
            'email_confirmation' => 'test@example.com',
            'password' => 'test-password',
            'password_confirmation' => 'test-password',
        ]);

        $response->assertSessionHasErrors('turnstile');
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
        Notification::assertNothingSent();
        Http::assertNothingSent();
    }

    public function test_registration_rejects_an_invalid_turnstile_token_before_creating_an_account(): void
    {
        Notification::fake();
        Http::fake(['challenges.cloudflare.com/*' => Http::response(['success' => false, 'error-codes' => ['invalid-input-response']])]);

        $response = $this->post('/register', [
            'username' => 'Test User',
            'email' => 'test@example.com',
            'email_confirmation' => 'test@example.com',
            'password' => 'test-password',
            'password_confirmation' => 'test-password',
            'turnstile' => 'invalid-token',
        ]);

        $response->assertSessionHasErrors('turnstile');
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
        Notification::assertNothingSent();
    }

    public function test_registration_completes_with_a_warning_when_verification_email_delivery_fails(): void
    {
        Http::fake(['challenges.cloudflare.com/*' => Http::response(['success' => true, 'error-codes' => []])]);
        Notification::shouldReceive('send')->once()->andThrow(new TransportException('Email service unavailable'));

        $response = $this->post('/register', [
            'username' => 'Test User',
            'email' => 'test@example.com',
            'email_confirmation' => 'test@example.com',
            'password' => 'test-password',
            'password_confirmation' => 'test-password',
            'turnstile' => 'test-turnstile-token',
        ]);

        $user = User::where('email', 'test@example.com')->firstOrFail();

        $this->assertAuthenticatedAs($user);
        $this->assertFalse($user->hasVerifiedEmail());
        $this->assertDatabaseCount('users', 1);
        $response->assertRedirect(route('verification.notice'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('flash.type', 'warn');
        $response->assertSessionHas('flash.message', 'Your account was created, but we could not send your verification email. Please try resending it later.');
    }
}
