<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OpenIdToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class OAuthProviderController extends Controller
{
    /**
     * OpenID Connect Discovery Endpoint
     * Allows clients to auto-discover OAuth endpoints
     */
    public function discovery(Request $request): JsonResponse
    {
        $baseUrl = rtrim(config('app.url'), '/');
        $config = [
            'issuer' => $baseUrl,
            'authorization_endpoint' => $baseUrl.'/oauth/authorize',
            'token_endpoint' => $baseUrl.'/oauth/token',
            'userinfo_endpoint' => $baseUrl.'/oauth/userinfo',
            'jwks_uri' => $baseUrl.'/oauth/jwks',
            'scopes_supported' => ['openid', 'profile', 'email'],
            'response_types_supported' => ['code'],
            'grant_types_supported' => ['authorization_code'],
            'subject_types_supported' => ['public'],
            'id_token_signing_alg_values_supported' => ['RS256'],
            'code_challenge_methods_supported' => ['S256'],
            'token_endpoint_auth_methods_supported' => ['client_secret_post'],
            'claims_supported' => [
                'sub',
                'name',
                'preferred_username',
                'email',
                'email_verified',
            ],
        ];

        return response()->json($config)
            ->header('Cache-Control', 'public, max-age=3600');
    }

    public function jwks(OpenIdToken $tokens): JsonResponse
    {
        return response()->json(['keys' => [$tokens->jwk()]]);
    }

    /**
     * OAuth2 Authorization Endpoint
     * External apps redirect users here to authenticate
     */
    public function authorization(Request $request): Response
    {
        $request->validate([
            'client_id' => ['required', 'alpha_dash', 'max:100'],
            'redirect_uri' => ['required', 'url', 'max:500'],
            'response_type' => ['required', 'in:code'],
            'state' => ['max:500'],
            'scope' => ['max:200'],
            'nonce' => ['nullable', 'string', 'max:500'],
            'code_challenge' => ['required', 'regex:/^[A-Za-z0-9_-]{43}$/'],
            'code_challenge_method' => ['required', 'in:S256'],
        ]);

        // User is already authenticated via Laravel session from middleware
        $user = Auth::user();

        // Verify client_id matches configured OAuth clinents
        $client = $this->getOAuthClient($request->client_id);
        if (! $client) {
            abort(400, 'Invalid client_id');
        }
        // Never send codes to an unregistered callback, even for a valid client.
        if (! in_array($request->redirect_uri, $client['redirect_uris'], true)) {
            abort(400, 'Invalid redirect_uri');
        }
        $scope = $request->scope ?? 'openid profile email';
        $scopes = preg_split('/\s+/', trim($scope));
        if (! in_array('openid', $scopes, true) || array_diff($scopes, ['openid', 'profile', 'email'])) {
            abort(400, 'Invalid scope');
        }

        // Generate authorization code
        $authorizationCode = Str::random(40);

        $cacheKey = "oauth_provider:auth_code:{$authorizationCode}";
        cache()->put($cacheKey, [
            'user_id' => $user->id,
            'client_id' => $request->client_id,
            'redirect_uri' => $request->redirect_uri,
            'scope' => $scope,
            'nonce' => $request->nonce,
            'code_challenge' => $request->code_challenge,
        ], now()->addMinutes(5));

        $separator = str_contains($request->redirect_uri, '?') ? '&' : '?';
        $redirectUrl = $request->redirect_uri.$separator.http_build_query([
            'code' => $authorizationCode,
            'state' => $request->state,
        ]);

        // Login forms use Inertia: leave its AJAX flow for the external wiki callback.
        return Inertia::location($redirectUrl);
    }

    /**
     * OAuth2 Token Endpoint
     * External apps exchange authorization code for access token
     */
    public function token(Request $request, OpenIdToken $tokens): JsonResponse
    {
        $request->validate([
            'grant_type' => ['required', 'in:authorization_code'],
            'code' => ['required', 'alpha_num', 'size:40'],
            'redirect_uri' => ['required', 'url', 'max:500'],
            'client_id' => ['required', 'alpha_dash', 'max:100'],
            'client_secret' => ['required', 'string'],
            'code_verifier' => ['required', 'regex:/^[A-Za-z0-9._~-]{43,128}$/'],
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
        if (! $client || ! is_string($client['secret']) || ! hash_equals($client['secret'], $request->client_secret)) {
            RateLimiter::hit($rateLimitKey, 300); // 5 minute decay

            return response()->json(['error' => 'invalid_client'], 401);
        }

        // Retrieve and validate authorization code
        $cacheKey = "oauth_provider:auth_code:{$request->code}";
        $authData = cache()->pull($cacheKey);
        if (! $authData ||
            $authData['client_id'] !== $request->client_id ||
            $authData['redirect_uri'] !== $request->redirect_uri ||
            ! hash_equals($authData['code_challenge'], OpenIdToken::base64Url(hash('sha256', $request->code_verifier, true)))) {

            RateLimiter::hit($rateLimitKey, 300); // 5 minute decay

            return response()->json(['error' => 'invalid_grant'], 400);
        }

        RateLimiter::clear($rateLimitKey);

        // Generate access token with Sanctum
        $user = User::findOrFail($authData['user_id']);
        $tokenName = "oauth_provider:{$request->client_id}";
        $expiresAt = now()->addHour();
        $accessToken = $user->createToken($tokenName, explode(' ', $authData['scope']), $expiresAt)->plainTextToken;
        $claims = [
            'iss' => rtrim(config('app.url'), '/'),
            'sub' => (string) $user->id,
            'aud' => $authData['client_id'],
            'iat' => now()->timestamp,
            'exp' => $expiresAt->timestamp,
            'at_hash' => OpenIdToken::base64Url(substr(hash('sha256', $accessToken, true), 0, 16)),
        ];
        if ($authData['nonce'] !== null) {
            $claims['nonce'] = $authData['nonce'];
        }
        if (in_array('profile', explode(' ', $authData['scope']), true)) {
            $claims['name'] = $user->username;
            $claims['preferred_username'] = $user->username;
        }
        if (in_array('email', explode(' ', $authData['scope']), true)) {
            $claims['email'] = $user->email;
            $claims['email_verified'] = $user->hasVerifiedEmail();
        }

        return response()->json([
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'expires_in' => 3600, // 1 hour
            'scope' => $authData['scope'],
            'id_token' => $tokens->sign($claims),
        ])->header('Cache-Control', 'no-store')->header('Pragma', 'no-cache');
    }

    /**
     * OpenID Connect UserInfo Endpoint
     * External apps fetch user details using the access token
     */
    public function userinfo(Request $request): JsonResponse
    {
        $user = $request->user();

        $token = $user->currentAccessToken();
        if (! $token || ! $token->can('openid')) {
            return response()->json(['error' => 'insufficient_scope'], 403);
        }
        $claims = ['sub' => (string) $user->id];
        if ($token->can('profile')) {
            $claims['name'] = $user->username;
            $claims['preferred_username'] = $user->username;
        }
        if ($token->can('email')) {
            $claims['email'] = $user->email;
            $claims['email_verified'] = $user->hasVerifiedEmail();
        }

        return response()->json($claims)->header('Cache-Control', 'no-store');
    }

    /**
     * Get OAuth client configuration by client_id
     */
    private function getOAuthClient(string $clientId): ?array
    {
        $clients = config('oauth.clients', []);

        $client = $clients[$clientId] ?? null;
        if ($client !== null && ! empty($client['secret_file'])) {
            // Read SOPS's current runtime file, not its contents during config:cache.
            // A configured file never falls back to an older .env client secret.
            $client['secret'] = is_readable($client['secret_file'])
                ? trim(file_get_contents($client['secret_file']))
                : null;
        }

        return $client;
    }
}
