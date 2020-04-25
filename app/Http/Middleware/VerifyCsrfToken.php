<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Log;
use Str;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    protected $excludedOrigins = [
        '/graphql-playground',
    ];


    // public function handle($request, Closure $next)
    // {
    //     $referer = Str::replaceFirst('https://', '', $request->headers->get('referer'));

    //     $referer = Str::replaceFirst('http://', '', $referer);
    // }

    /**
     * Get the CSRF token from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function getTokenFromRequest($request)
    {
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN') ?: $request->cookie('XSRF-TOKEN');

        if (!$token &&  $request->hasHeader('X-XSRF-TOKEN') && $header = $request->header('X-XSRF-TOKEN')) {
            $token = $this->encrypter->decrypt($header, static::serialized());
        }

        return $token;
    }
}
