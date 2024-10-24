<?php

namespace App\Http\Controllers;

use App\Http\Requests\LikeRequest;
use App\Http\Requests\UnlikeRequest;
use Illuminate\Http\RedirectResponse;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function like(LikeRequest $request): RedirectResponse
    {
        $request->user()->like($request->likeable());

        return back();
    }

    public function unlike(UnlikeRequest $request): RedirectResponse
    {
        $request->user()->unlike($request->likeable());

        return back();
    }
}
