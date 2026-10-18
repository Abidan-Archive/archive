<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Response;
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return inertia('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'confirmed', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'turnstile' => ['required', new Turnstile],
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        try {
            event(new Registered($user));
        } catch (TransportExceptionInterface $exception) {
            report($exception);

            $request->session()->flash('flash', [
                'type' => 'warn',
                'message' => 'Your account was created, but we could not send your verification email. Please try resending it later.',
                'autohide' => false,
            ]);
        }

        Auth::login($user);

        return to_route('verification.notice');
    }
}
