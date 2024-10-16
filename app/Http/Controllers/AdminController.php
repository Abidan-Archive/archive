<?php

namespace App\Http\Controllers;

use App\Enums\BanType;
use App\Models\Ban;
use App\Models\Ip;
use App\Models\User;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
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

    /**
    * Manage users
    */
    public function user(): Response
    {
        $users = User::all();

        return inertia('Admin/User', compact('users'));
    }

    /**
    * Api endpoint for form autocomplete
    */
    public function query(Request $request): JsonResponse {
        $request->validate([
            'q' => 'required',
            'type' => ['required', Rule::in(['user', 'ip'])],
        ]);

        $model = match($request->input('type')) {
            'user' => User::class,
            'ip' => Ip::class,
            default => throw new \Exception('Unsupported'),
        };

        $results = $model::autocomplete($request->input('q'))->take(10);

        return response()->json(compact('results'));
    }

    /**
    * Store Ban on a User or IP
    */
    public function ban(Request $request): RedirectResponse
    {
        if (Auth::user()->cannot('admin_ban')) {
            abort(403);
        }

        // Basic
        $validator = Validator::make($request->all(), [
            'type' => ['bail', 'required', Rule::enum(BanType::class)],
            'bannable_id' => ['required'],
            'expires' => ['sometimes', 'date', 'after:now'],
            'reason' => ['required', 'max:255']
        ]);
        // Complex validation
        $validator->sometimes('bannable_id', 'exists:App\Models\User,id',
            fn(Fluent $input) => $input->type === BanType::User);
        $validator->sometimes('bannable_id', 'exists:App\Models\Ip,id',
            fn(Fluent $input) => $input->type === BanType::Ip);

        // Validate and auto redirect
        $validator->validate();

        // Create the ban
        $input = $request->safe()->collect();
        Ban::hammer(
            $input->bannable_id,
            $input->type,
            $input->reason,
            $input->expires
        );

        return back()->with('flash', ['message' => "Ban successfully hammered."]);
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

    /**
    * Reset a users password
    */
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
