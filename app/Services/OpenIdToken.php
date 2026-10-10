<?php

namespace App\Services;

use RuntimeException;

class OpenIdToken
{
    public static function base64Url(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function privateKey(): \OpenSSLAsymmetricKey
    {
        $path = config('oauth.private_key_path');
        $key = is_readable($path) ? openssl_pkey_get_private(file_get_contents($path)) : false;
        if ($key === false) {
            throw new RuntimeException('OIDC signing key missing or invalid. Check OAUTH_PRIVATE_KEY_PATH (SOPS in production), or run php artisan oauth:keys locally.');
        }

        return $key;
    }

    public function jwk(): array
    {
        $details = openssl_pkey_get_details($this->privateKey());
        if (! isset($details['rsa'])) {
            throw new RuntimeException('OIDC requires an RSA signing key.');
        }
        $n = self::base64Url($details['rsa']['n']);

        return [
            'kty' => 'RSA',
            'use' => 'sig',
            'alg' => 'RS256',
            'kid' => hash('sha256', $n),
            'n' => $n,
            'e' => self::base64Url($details['rsa']['e']),
        ];
    }

    public function sign(array $claims): string
    {
        $header = ['typ' => 'JWT', 'alg' => 'RS256', 'kid' => $this->jwk()['kid']];
        $payload = self::base64Url(json_encode($header, JSON_THROW_ON_ERROR)).'.'.
            self::base64Url(json_encode($claims, JSON_THROW_ON_ERROR));
        if (! openssl_sign($payload, $signature, $this->privateKey(), OPENSSL_ALGO_SHA256)) {
            throw new RuntimeException('Unable to sign OIDC ID token.');
        }

        return $payload.'.'.self::base64Url($signature);
    }
}
