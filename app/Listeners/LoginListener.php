<?php

namespace App\Listeners;

use App\Models\Ip;
use Illuminate\Auth\Events\Login;

// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Queue\InteractsWithQueue;

class LoginListener
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $ip = Ip::firstOrCreate(['ip' => request()->ip()]);
        $user = $event->user;
        if (! $user->ips->contains($ip)) {
            $user->ips()->attach($ip);
        }
        $user->login_at = now();
        $user->save();
    }
}
