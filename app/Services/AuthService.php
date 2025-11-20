<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
    /**
     * @param array $requestData
     * @return array
     */
    public function login(array $requestData): array
    {
        $user = User::where('email', '=', $requestData['email'])->first();

        if (!$user) {
            throw new UnauthorizedException('invalid_credentials', Response::HTTP_UNAUTHORIZED);
        }

        $token = $this->createToken($requestData);

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ];
    }

    /**
     * @return bool
     */
    public function logout(): bool
    {
        try {
            JWTAuth::invalidate(true);

            return true;
        } catch (Exception $exception) {
            Log::error(
                'logout_exception',
                [
                    'message' => $exception->getMessage(),
                    'error' => $exception->getTraceAsString(),
                ]
            );
            return false;
        }
    }


    /**
     * @param array $credentials
     * @return false|string
     */
    private function createToken(array $credentials): false|string
    {
        $ttl = config('jwt.ttl');

        return JWTAuth::claims(['exp' => time() + $ttl * 60])->attempt($credentials);
    }
}
