<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): Response
    {
        $likes = $user->likes->map->likeable;

        return inertia('User/Show', compact('user', 'likes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): Response
    {
        $this->authorize('edit', auth()->user());

        return inertia('User/Edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): \Illuminate\Http\Response
    {
        $validated = $request->validated();

        if ($validated['username'] != null || $validated['username'] != $user->username) {
            $user->username = $validated['username'];
        }

        if ($validated['email'] != null || $validated['email'] != $user->email) {
            $user->email = $validated['email'];
            $user->email_verified_at = null;
        }

        if ($validated['new_password'] != null) {

        }

        if ($user->isDirty()) {
            $user->save();
        }

        return to_route('user.edit', compact('user'))
            ->with('flash', ['message' => 'Account successfully updated.']);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): void
    {
        //
    }
}
