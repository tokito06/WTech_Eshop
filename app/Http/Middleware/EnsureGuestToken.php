<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class EnsureGuestToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('guest_token');

        if (!$token) {
            $token = (string) Str::uuid();
        }

        $request->attributes->set('guest_token', $token);

        $response = $next($request);

        if (!$request->cookie('guest_token')) {
            $response->withCookie(
                cookie('guest_token', $token, 60 * 24 * 365, '/', null, false, true)
            );
        }

        return $response;
    }

    public static function token(Request $request): string
    {
        return $request->attributes->get('guest_token', '');
    }
}
