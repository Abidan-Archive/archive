<?php

namespace App\Http\Controllers;

use App\Enums\BanType;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Ban;
use App\Models\Ip;
use App\Models\Report;
use App\Models\Role;
use App\Models\Scopes\ReviewedScope;
use App\Models\Source;
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
        $this->middleware(['auth', 'can:admin_view']);
    }

    /**
     * Admin home panel
     */
    public function index(): Response
    {
        return inertia('Admin/Index');
    }

    public function reviewIndex(): Response
    {
        if (Auth::user()->cannot('review_report')) {
            abort(403);
        }

        $reports = Report::with(['stub', 'stub.source'])
            ->withoutGlobalScope(ReviewedScope::class)
            ->whereNull('reviewed_at')
            ->orderBy('created_at', 'ASC')
            ->paginate(20);

        return inertia('Admin/Review', compact('reports'));
    }

    public function reviewApprove(int $id): RedirectResponse
    {
        if (Auth::user()->cannot('review_report')) {
            abort(403);
        }
        $report = Report::withoutGlobalScope(ReviewedScope::class)->findOrFail($id);
        $report->update(['reviewed_at' => now()]);
        $report->stub?->delete(); // Approved, so now delete stub

        return back()->with('flash', ['message' => "Report $report->id approved! Now Public."]);
    }

    /**
     * Updates a report prior to it being reviewed
     */
    public function updateReport(UpdateReportRequest $request, int $id): RedirectResponse
    {
        if (Auth::user()->cannot('edit_report')) {
            abort(403);
        }

        $report = Report::withoutGlobalScope(ReviewedScope::class)->firstOrFail($id);
        $report->patch($request);

        return back()->with('flash', ['message' => "Report $report->id updated. Still needs to be approved."]);
    }

    /**
     * Manage users
     */
    public function user(Request $request): Response
    {
        if (Auth::user()->cannot('admin_manage_user')) {
            abort(403);
        }

        $query = User::query()->with(['roles:id,name']);

        // Allow generic searching
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('id', 'like', "%{$searchTerm}%")
                    ->orWhere('username', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        // Handle column filtering
        if ($request->has('column')) {
            $columns = $request->input('column');
            foreach ($columns as $column => $value) {
                if (! empty($value)) {
                    $query->where($column, 'like', "%{$value}%");
                }
            }
        }

        // Handle sorting
        if ($request->has('sort') && $request->has('direction')) {
            $sortColumn = $request->input('sort', 'id');
            $sortDirection = $request->input('direction', 'asc');
            $query->orderBy($sortColumn, $sortDirection);
        }

        $users = $query->paginate(20)
            ->withQueryString()
            ->through(function ($user) {
                $user->makeVisible('email');
                if (! Auth::user()->hasRole('admin')) {
                    $user->email = maskEmail($user->email);
                }

                return $user;
            });

        return inertia('Admin/User', [
            'users' => $users,
            'roles' => fn () => Role::select(['id', 'name', 'label'])
                ->with('permissions:id,label')
                ->get(),
        ]);
    }

    /**
     * Manage sources
     */
    public function source(): Response
    {
        $sources = Source::with('event')->with('stub')->all();

        return inertia('Admin/Source', compact('source'));
    }

    /**
     * Api endpoint for form autocomplete
     */
    public function query(Request $request): JsonResponse
    {
        if (Auth::user()->cannot('admin_ban')) {
            abort(403);
        }

        $request->validate([
            'q' => 'required',
            'type' => ['required', Rule::in(['user', 'ip'])],
        ]);

        $model = match ($request->input('type')) {
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
            'reason' => ['required', 'max:255'],
        ]);
        // Complex validation
        $validator->sometimes(
            'bannable_id',
            'exists:App\Models\User,id',
            fn (Fluent $input) => $input->type === BanType::User
        );
        $validator->sometimes(
            'bannable_id',
            'exists:App\Models\Ip,id',
            fn (Fluent $input) => $input->type === BanType::Ip
        );

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

        return back()->with('flash', ['message' => 'Ban successfully hammered.']);
    }

    /**
     * Log in as another user within the system
     */
    public function assume(Request $request): RedirectResponse
    {
        $auth = Auth::user();
        if ($auth->cannot('admin_assume_user')) {
            abort(403);
        }

        $data = $request->validate([
            'user_id' => ['required', 'exists:App\Models\User,id'],
        ]);
        $user = User::findOrFail($data['user_id']);

        Log::info('Admin assumed user', [
            'admin' => "$auth->username ($auth->id)",
            'user' => "$user->username ($user->id)",
        ]);
        Auth::login($user);

        return to_route('home')
            ->with('flash', ['message' => "You've successfully assumed the user!"]);
    }

    /**
     * Reset a users password
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        if (Auth::user()->cannot('admin_reset_password')) {
            abort(403);
        }

        $data = $request->validate([
            'user_id' => ['required', 'exists:App\Models\User,id'],
        ]);
        $user = User::findOrFail($data['user_id']);

        if ($user->email_verified_at == null) {
            return back()
                ->with('flash', [
                    'message' => 'User has not verified their email, cannot reset password until verified.',
                    'type' => 'warn',
                    'autohide' => false,
                ]);
        }

        Log::info('Staff reset user password', ['staff' => Auth::user()->username, 'user' => $user->username]);

        $user->password = Hash::make(Str::password());
        $user->save();

        $status = Password::sendResetLink($user->only('email'));

        return $status == Password::RESET_LINK_SENT
            ? back()->with('flash', ['message' => 'Set the users password, reset link sent.'])
            : back()->with('flash', ['message' => __($status), 'autohide' => false]);
    }

    public function assignRole(Request $request): RedirectResponse
    {
        $auth = Auth::user();
        if ($auth->cannot('admin_manage_permissions')) {
            abort(403);
        }

        $data = $request->validate([
            'user_id' => ['required', 'exists:App\Models\User,id'],
            'roles' => ['list'],
            'roles.*' => ['exists:App\Models\Role,name'],
        ]);

        $assignee = User::findOrFail($data['user_id']);

        // Don't allow users below the role of admin change admins
        if (($assignee->hasRole('admin') || in_array('admin', $data['roles'])) && ! $auth->hasRole('admin')) {
            // You thought I'm not going to log this?
            Log::info("$auth->username tried to change $assignee->username admin role.", [
                'roles' => $data['roles'],
            ]);

            return back()->with('flash', ['message' => "I'm sorry Dave, I'm afraid I can't do that. This action has been reported.", 'type' => 'error', 'autohide' => false]);
        }

        $roles = Role::select('id')->whereIn('name', $data['roles'])->get();
        $assignee->roles()->sync($roles);

        return back()->with('flash', ['message' => 'Updated user role successfully.']);
    }
}
