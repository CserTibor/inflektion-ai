<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (!JWTAuth::getToken()) {
                return response()->json(['error' => 'missing_token'], Response::HTTP_UNAUTHORIZED);
            }

            $token = JWTAuth::parseToken();

            if (!$token->authenticate()) {
                return response()->json(['error' => 'unauthorized'], Response::HTTP_UNAUTHORIZED);
            }
        } catch (Exception) {
            return response()->json(['error' => 'unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
