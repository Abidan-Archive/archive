<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Log;

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

    /**
     * Log in as another user within the system
     */
    public function assume(User $user): RedirectResponse
    {
        if (Auth::user()->cannot('assume')) {
            abort(403);
        }

        Log::info('Admin assumed user', ['admin' => Auth::user()->id, 'user' => $user->id]);
        Auth::login($user);

        return to_route('home')
            ->with('flash', ['message' => "You've successfully assumed the user!"]);
    }
}
