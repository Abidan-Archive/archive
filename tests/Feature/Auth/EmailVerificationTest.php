<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Symfony\Component\Mailer\Exception\TransportException;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(200);
    }

    public function test_email_can_be_verified(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(AppServiceProvider::HOME.'?verified=1');
    }

    public function test_verification_email_can_be_resent(): void
    {
        Notification::fake();
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->from('/verify-email')->post('/email/verification-notification');

        $response->assertRedirect('/verify-email');
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_verification_email_delivery_failure_shows_a_warning(): void
    {
        $user = User::factory()->unverified()->create();
        Notification::shouldReceive('send')->once()->andThrow(new TransportException('Email service unavailable'));

        $response = $this->actingAs($user)->from('/verify-email')->post('/email/verification-notification');

        $response->assertRedirect('/verify-email');
        $response->assertSessionHas('flash.type', 'warn');
        $response->assertSessionHas('flash.message', 'We could not send your verification email. Please try again later.');
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_email_is_not_verified_with_invalid_hash(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($user)->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
}
