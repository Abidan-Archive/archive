<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Inertia\Response;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Admin home panel
     */
    public function index(): Response
    {
        return inertia('Admin/Index');
    }

    public function user(): Response
    {
        $users = User::all();

        return inertia('Admin/User', compact('users'));
    }

    /**
     * Log in as another user within the system
     */
    public function assume(User $user): RedirectResponse
    {
        if (Auth::user()->cannot('admin_assume_user')) {
            abort(403);
        }

        Log::info('Admin assumed user', ['admin' => Auth::user()->id, 'user' => $user->id]);
        Auth::login($user);

        return to_route('home')
            ->with('flash', ['message' => "You've successfully assumed the user!"]);
    }

    public function resetPassword(User $user): RedirectResponse
    {
        if (Auth::user()->cannot('admin_reset_password')) {
            abort(403);
        }

        if ($user->email_verified_at == null) {
            return back()
                ->with('flash', ['message' => 'User has not verified their email, cannot reset password until verified.', 'type' => 'warn', 'autohide' => false]);
        }

        Log::info('Admin reset user password', ['admin' => Auth::user()->id, 'user' => $user->id]);

        $user->password = Hash::make(Str::password());
        $user->save();

        $status = Password::sendResetLink($user->email);

        return $status == Password::RESET_LINK_SENT
            ? back()->with('flash', ['message' => 'Set the users password, reset link sent.'])
            : back()->with('flash', ['message' => __($status), 'autohide' => false]);
    }
}
