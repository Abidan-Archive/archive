<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    private function redirectWithError(string $message): RedirectResponse {
        return to_route('login')->with('flash', [
            'message' => $message ?? __('auth.oauth.error'),
            'type' => 'error',
            'timeout' => null
        ]);
    }

    /**
     * For Login and account creation only
     * cannot associate existing account with discord easily.
    */
    public function discord(Request $request): RedirectResponse {
        if ($request->query('error')) return $this->redirectWithError();

        // Get data from oauth service
        $discordUser = Socialite::driver('discord')->user();

        // Get the user with associated auth id
        $user = User::where('discord_id', $discordUser->id)->first();

        // If not found, we intend to create
        if ($user == null) {
            if (User::whereEmail($discordUser->email)->exists())
                return to_route('login')->with('flash', [
                    'message' => __('auth.oauth.email'),
                    'type' => 'warn',
                    'timeout' => null
                ]);
            $user = User::create([
                'discord_id' => $discordUser->id,
                'email' => $discordUser->email,
                'username' => $discordUser->nickname,
            ]);
        } else {
            // User found, update data
            $user->update([
                'email' => $discordUser->email,
                'username' => $discordUser->nickname,
                'email_verified_at' =>
                    $user->email == $discordUser->email
                        ? $user->email_verified_at
                        : null,
            ]);
        }

        Auth::login($user);

        return to_route('verification.notice');
    }
}
