<?php

namespace App\Services;

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

use function PHPSTORM_META\map;

class JWKRetrieval
{

    public string $provider;

    public function __construct(string $provider)
    {
        $this->provider = $provider;
    }

    public function publicKeyForKId(string $url, string $kid)
    {
        return Cache::remember(
            $this->cacheKey($kid), // Return the pkey if we've cached it
            3600 * 24,
            function () use (&$url, &$kid) { // Otherwise regenerate
                $keys = $this->retrieveKeys($url);
                if (array_key_exists($kid, $keys)) {
                    return $keys[$kid];
                }
                throw new ValidationException("No valid public key was found for the given JWT");
            }
        );
    }

    private function retrieveKeys(string $url): array
    {
        $res = Http::get($url);
        $jsonKeys = $res->json();

        $keys = JWK::parseKeySet($jsonKeys);
        $keys = array_map(fn ($k) => openssl_pkey_get_details($k)['key'], $keys);

        return $keys;
    }



    private function cacheKey(string $kid): string
    {
        return "jwk:$this->provider:$kid";
    }
}
