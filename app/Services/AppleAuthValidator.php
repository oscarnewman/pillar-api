<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;

class AppleAuthValidator
{
    private JWKRetrieval $jwkRetrieval;

    public function __construct(Cache $cache)
    {
        $this->jwkRetrieval = new JWKRetrieval('apple');
        $this->cache = $cache;
    }

    public function validateIdToken($idToken)
    {
        $kid = $this->extractKeyIDFromJwt($idToken);
        $publicKey = $this->jwkRetrieval->publicKeyForKId(config('services.apple.jwks'), $kid);

        $decoded = (array) JWT::decode($idToken, $publicKey, ['RS256']);
        return $decoded;
    }

    public function extractKeyIDFromJwt($jwt)
    {
        $decoded = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $jwt)[0]))), true);
        $keyToUse = $decoded['kid'];
        return $keyToUse;
    }

    public function extractAppleIDFromJwt($jwt)
    {
        $decoded = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $jwt)[1]))), true);
        $sub = $decoded['sub'];
        return $sub;
    }
}
