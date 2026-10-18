<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Backend tests run before frontend assets exist in a clean CI checkout.
        $this->withoutVite();
        config(['inertia.ssr.enabled' => false]);
        // HTTP responses are faked by individual tests; never require a real
        // Cloudflare credential just to construct the Turnstile client.
        config(['services.turnstile.secret' => 'test-turnstile-secret']);
    }
}
