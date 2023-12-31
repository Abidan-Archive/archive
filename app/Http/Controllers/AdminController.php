<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class AdminController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Admin home panel
     */
    public function index(): Response {
        return inertia('Admin/Index');
    }

    /**
     * Log in as another user within the system
     */
    public function assume(User $user): RedirectResponse {
        if (Auth::user()->cannot('assume')) abort(403);

        Auth::login($user);

        return to_route('home')
            ->with('status', "You've successfully assumed the user!");
    }
}
