<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Str;

class OAuthProviderController extends Controller
{
    /**
     * OAuth2 Authorization Endpoint
     * External apps redirect users here to authenticate
     */
    public function authorize(Request $request): RedirectResponse
    {
        $request->validate([
            'client_id' => 'required|string',
            'redirect_uri' => 'required|url',
            'response_type' => 'required|in:code',
            'state' => 'nullable|string',
            'scope' => 'nullable|string,',
        ]);

        // User is already authenticated via Laravel session from middleware
        $user = Auth::user();

        // Verify client_id matches configured OAuth clinents
        $client = $this->getOAuthClient($request->client_id);
        if (! $client) {
            abort(400, 'Invalid client_id');
        }

        // Generate authorization code
        $authorizationCode = Str::random(40);

        $cacheKey = "oauth_provider:auth_code:{$authorizationCode}";
        cache()->put($cacheKey, [
            'user_id' => $user->id,
            'client_id' => $request->client_id,
            'redirect_uri' => $request->redirect_uri,
            'scope' => $request->scope ?? 'openid profile email',
            'used' => false,
        ], now()->addMinutes(5));

        $redirectUrl = $request->redirect_url.'?'.http_build_query([
            'code' => $authorizationCode,
            'state' => $request->state,
        ]);

        return redirect($redirectUrl);
    }

    /**
     * OAuth2 Token Endopiont
     * External apps exchange authorization code for access token
     */
    public function token(Request $request): JsonResponse
    {
        $request->validate([
            'grant_type' => 'required|in:authorization_code',
            'code' => 'required|string',
            'redirect_uri' => 'required|url',
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
        ]);

        // Additional rate protections around client_id
        $rateLimitKey = 'oauth_token_attempt:'.$request->client_id;
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);

            return response()->json([
                'error' => 'too_many_requests',
                'error_description' => "Too many failed attempts. Try again in {$seconds} seconds.",
            ], 429);
        }

        // Verify client_id matches configured OAuth clinents
        $client = $this->getOAuthClient($request->client_id);
        if (! $client || $client['secret'] !== $request->client_secret) {
            RateLimiter::hit($rateLimitKey, 300); // 5 minute decay

            return response()->json(['error' => 'invalid_client'], 401);
        }

        // Retrieve and validate authorization code
        $cacheKey = "oauth_provider:auth_code:{$request->code}";
        $authData = cache()->pull($cacheKey);
        if (! $authData ||
            $authData['client_id'] !== $request->client_id ||
            $authData['redirect_uri'] !== $request->redirect_uri) {

            RateLimiter::hit($rateLimitKey, 300); // 5 minute decay

            return response()->json(['error' => 'invalid_grant'], 400);
        }

        RateLimiter::clear($rateLimitKey);

        // Generate access token with Sanctum
        $user = User::findOrFail($authData['user_id']);
        $tokenName = "oauth_provider:{$request->client_id}";
        $accessToken = $user->createToken($tokenName, [$authData['scope']])->plainTextToken;

        return response()->json([
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'expires_in' => 3600, // 1 hour
            'scope' => $authData['scope'],
        ]);
    }

    /**
     * OpenID Connect UserInfo Endpoint
     * External apps fetch user details using the access token
     */
    public function userinfo(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'sub' => (string) $user->id,
            'name' => $user->username,
            'preferred_username' => $user->username,
            'eamil' => $user->eamil,
            'email_verified' => $user->hasVerifiedEmail(),
            'roles' => $user->roles->pluck('name'),
        ]);
    }

    /**
     * Get OAuth client configuration by client_id
     */
    private function getOAuthClient(string $clientId): ?array
    {
        $clients = config('oauth.clients', []);

        return $clients[$clientId] ?? null;
    }
}
